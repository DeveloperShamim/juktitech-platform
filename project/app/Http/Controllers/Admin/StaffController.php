<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function __construct()
    {
        // returnToAdmin শুধু web auth দিয়ে access হয়, তাই except করা হয়েছে
        $this->middleware('auth:admin')->except('returnToAdmin');
    }

    public function datatables()
    {
        $datas = User::orderBy('id','desc');
        return Datatables::of($datas)
            ->addColumn('action', function(User $data) {
                $edit   = '<a data-href="'.route('admin.staff.edit',$data->id).'" class="edit" data-toggle="modal" data-target="#modal1"><i class="fas fa-edit"></i>Edit</a>';
                $login  = '<a href="'.route('admin.staff.login_as', $data->id).'" class="btn btn-sm btn-warning ml-1" title="এই ইউজার হিসেবে লগইন করুন" onclick="return confirm(\'আপনি কি \''.$data->name.'\' এর অ্যাকাউন্টে লগইন করতে চান?\')"><i class="fas fa-sign-in-alt"></i> Login As</a>';
                $delete = '<a href="javascript:;" data-href="'.route('admin.staff.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
                return '<div class="action-list">'.$edit.$login.$delete.'</div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * অ্যাডমিন যে কোনো ইউজারের অ্যাকাউন্টে পাসওয়ার্ড ছাড়া লগইন করতে পারবেন
     */
    public function loginAsUser($id)
    {
        $user = User::findOrFail($id);

        // অ্যাডমিনের ID সেশনে সংরক্ষণ করা (ফিরে আসার জন্য)
        session([
            'admin_impersonating'    => Auth::guard('admin')->id(),
            'admin_impersonating_name' => Auth::guard('admin')->user()->name,
        ]);

        // ইউজার হিসেবে লগইন করানো (web guard)
        Auth::guard('web')->loginUsingId($user->id);

        return redirect()->route('user.dashboard')
            ->with('success', 'আপনি এখন "'.$user->name.'" এর অ্যাকাউন্টে আছেন।');
    }

    /**
     * ইউজার অ্যাকাউন্ট থেকে বের হয়ে আবার অ্যাডমিন প্যানেলে ফিরে আসা
     */
    public function returnToAdmin()
    {
        $adminId = session('admin_impersonating');

        if (!$adminId) {
            return redirect()->route('admin.dashboard');
        }

        // ইউজার গার্ড লগআউট
        Auth::guard('web')->logout();

        // সেশন ক্লিয়ার
        session()->forget(['admin_impersonating', 'admin_impersonating_name']);

        // অ্যাডমিন গার্ডে পুনরায় লগইন
        Auth::guard('admin')->loginUsingId($adminId);

        return redirect()->route('admin.staff.index')
            ->with('success', 'আপনি সফলভাবে অ্যাডমিন প্যানেলে ফিরে এসেছেন।');
    }

    public function index()
    {
        return view('admin.staff.index');
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('admin.staff.create');
        }

        return view('admin.staff.create_page');
    }

    // --- নতুন ইউজার তৈরি (Store) ---
    public function store(Request $request)
    {
        // ১. ভ্যালিডেশন
        $rules = [
            'name'      => 'required',
            'email'     => 'required|unique:users', // users টেবিলে চেক হবে
            'phone'     => 'required',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'password'  => 'required',
            // SMS Fields Validation (Optional but recommended)
            'wallet_balance'   => 'nullable|numeric',
            'non_masking_rate' => 'nullable|numeric',
            'masking_rate'     => 'nullable|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        // ২. ডাটা সেভ
        $data = new User();

        if ($request->hasFile('photo')) {
            $data->photo = admin_upload_image($request->file('photo'), 'assets/images/admin');
        }

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->designation = $request->designation; // যদি ফর্ম থেকে আসে
        $data->password = bcrypt($request->password);
        $data->verified = 1;
        $data->email_verified = 'Yes';

        // [নতুন] SMS Reseller Fields
        $data->wallet_balance = $request->wallet_balance ?? 0.00;
        $data->non_masking_rate = $request->non_masking_rate ?? 0.50;
        $data->masking_rate = $request->masking_rate ?? 0.80;
        
        // API Key জেনারেট (যদি না থাকে)
        if(empty($data->api_key)){
            $data->api_key = \Illuminate\Support\Str::random(60);
        }

        $data->save();

        $msg = 'New User Added Successfully';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = User::find($id);
        return view('admin.staff.edit', compact('data'));
    }

    // --- ইউজার আপডেট (Update) ---
    public function update(Request $request, $id)
    {
        // ১. ভ্যালিডেশন
        $rules = [
            'name'      => 'required',
            'email'     => 'required|unique:users,email,'.$id,
            'phone'     => 'required',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'wallet_balance'   => 'nullable|numeric',
            'non_masking_rate' => 'nullable|numeric',
            'masking_rate'     => 'nullable|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        // ২. ডাটা আপডেট
        $data = User::find($id);
        $input = $request->all(); // সব ডাটা ইনপুট হিসেবে নিলাম

        if ($request->hasFile('photo')) {
            admin_delete_image('assets/images/admin', $data?->photo);
            $input['photo'] = admin_upload_image($request->file('photo'), 'assets/images/admin');
        }

        // [নতুন] পাসওয়ার্ড যদি খালি না থাকে তবে আপডেট হবে
        if($request->password){
            $input['password'] = bcrypt($request->password);
        } else {
            unset($input['password']); // পাসওয়ার্ড না দিলে আগেরটাই থাকবে
        }

        // [নতুন] SMS ফিল্ডগুলো নিশ্চিত করা
        $input['wallet_balance'] = $request->wallet_balance;
        $input['non_masking_rate'] = $request->non_masking_rate;
        $input['masking_rate'] = $request->masking_rate;

        // [নতুন] লাইসেন্স জেনারেশন ফ্রি অপশন
        $input['free_license_generation'] = $request->has('free_license_generation') ? 1 : 0;

        $data->update($input);

        $msg = 'User Data Updated Successfully';
        return response()->json($msg);
    }

    public function delete($id)
    {
        $data = User::find($id);
        
        admin_delete_image('assets/images/admin', $data?->photo);
        
        $data->delete();
    }
}