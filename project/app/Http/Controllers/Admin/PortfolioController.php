<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use App\Models\Portfolio;

use App\Models\PortfolioCategory;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;



class PortfolioController extends Controller

{

    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin.otp']);
    }

    public function index()

    {

        $portfolios = Portfolio::orderBy('id', 'desc')->get();

        return view('admin.portfolio.index', compact('portfolios'));

    }



    public function create()

    {

        $categories = PortfolioCategory::all();

        return view('admin.portfolio.create', compact('categories'));

    }



    public function store(Request $request)

    {

        $request->validate([

            'title' => 'required',

            'category_id' => 'required',

            'photo' => 'required|image|mimes:jpeg,png,jpg,gif'

        ]);



        $input = $request->all();

        if ($file = $request->file('photo')) {

            $input['photo'] = admin_upload_image($file, 'assets/images/portfolio');

        }

        Portfolio::create($input);

        return redirect()->route('admin.portfolio.index')->with('success', 'Project Added Successfully!');

    }



    public function edit($id)

    {

        $data = Portfolio::findOrFail($id);

        $categories = PortfolioCategory::all();

        return view('admin.portfolio.edit', compact('data', 'categories'));

    }



    public function update(Request $request, $id)

    {

        $data = Portfolio::findOrFail($id);

        $input = $request->all();



        if ($file = $request->file('photo')) {

            admin_delete_image('assets/images/portfolio', $data?->photo);

            $input['photo'] = admin_upload_image($file, 'assets/images/portfolio');

        }



        $data->update($input);

        return redirect()->route('admin.portfolio.index')->with('success', 'Project Updated Successfully!');

    }



    public function destroy($id)

    {

        $data = Portfolio::findOrFail($id);

        admin_delete_image('assets/images/portfolio', $data?->photo);

        $data->delete();

        return redirect()->back()->with('success', 'Project Deleted Successfully!');

    }

}