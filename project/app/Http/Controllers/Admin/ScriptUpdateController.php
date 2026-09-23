<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScriptUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;

class ScriptUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ScriptUpdate::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('script_name', function ($row) {
                    return '<span class="font-weight-bold text-primary">' . e($row->script_name ?? '—') . '</span>';
                })
                ->addColumn('version', function ($row) {
                    return '<span class="font-weight-bold text-dark">' . e($row->version) . '</span>';
                })
                ->addColumn('file', function ($row) {
                    return '<span class="text-muted small">' . e($row->file_name ?? basename($row->file_path)) . '</span>';
                })
                ->addColumn('status', function ($row) {
                    $badge = $row->is_active == 1 ? 'badge-success' : 'badge-secondary';
                    $text = $row->is_active == 1 ? 'Active (Latest)' : 'Archived';
                    return '<span class="badge ' . $badge . '">' . $text . '</span>';
                })
                ->addColumn('date', function ($row) {
                    return $row->created_at->format('d M, Y');
                })
                ->addColumn('action', function ($row) {
                    $btns = '<div class="d-flex gap-1 justify-content-end">';
                    $btns .= '<a href="' . route('admin.script-updates.download', $row->id) . '" class="btn btn-sm btn-outline-primary" title="Download"><i class="fas fa-download"></i></a>';
                    $btns .= '<form action="' . route('admin.script-updates.delete', $row->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\');">';
                    $btns .= csrf_field();
                    $btns .= method_field('DELETE');
                    $btns .= '<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>';
                    $btns .= '</form>';
                    $btns .= '</div>';
                    return $btns;
                })
                ->rawColumns(['script_name', 'version', 'file', 'status', 'date', 'action'])
                ->make(true);
        }

        return view('admin.script-updates.index');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'script_name' => 'required|string|max:100',
                'version' => 'required|string|max:50',
                'release_notes' => 'nullable|string',
                'update_file' => 'required|file|mimes:zip|max:1048576', // max 1GB (in KB)
            ]);

            $file = $request->file('update_file');
            if (!$file || !$file->isValid()) {
                return back()->withInput()->with('error', 'ফাইল আপলোড ব্যর্থ। ফাইল সাইজ বা PHP সেটিংস চেক করুন। upload_max_filesize ও post_max_size অন্তত 1024M (1GB) রাখুন।');
            }

            $dir = 'script-updates';
            $fileName = 'v' . preg_replace('/[^0-9.]/', '', $request->version) . '_' . time() . '.zip';
            $path = $file->storeAs($dir, $fileName, 'public');

            ScriptUpdate::where('script_name', $request->script_name)->where('is_active', 1)->update(['is_active' => 0]);
            ScriptUpdate::create([
                'script_name' => $request->script_name,
                'version' => $request->version,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'release_notes' => $request->release_notes,
                'is_active' => 1,
            ]);

            return back()->with('success', 'Update file uploaded successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Script Update Upload Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'আপলোড ব্যর্থ: ' . $e->getMessage() . ' — storage/app/public ফোল্ডারে লেখার পারমিশন আছে কিনা চেক করুন এবং php.ini এ upload_max_filesize, post_max_size 1024M রাখুন।');
        }
    }

    public function download($id)
    {
        $update = ScriptUpdate::findOrFail($id);
        $fullPath = storage_path('app/public/' . $update->file_path);
        if (!file_exists($fullPath)) {
            return back()->with('error', 'File not found.');
        }
        return response()->download($fullPath, $update->file_name ?? 'update_' . $update->version . '.zip');
    }

    public function destroy($id)
    {
        $update = ScriptUpdate::findOrFail($id);
        if ($update->file_path && Storage::disk('public')->exists($update->file_path)) {
            Storage::disk('public')->delete($update->file_path);
        }
        $update->delete();
        return back()->with('success', 'Update deleted successfully.');
    }
}
