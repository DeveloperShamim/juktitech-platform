<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin.otp']);
    }

    public function index() {
        $teams = Team::orderBy('id', 'desc')->get();
        return view('admin.team.index', compact('teams'));
    }

    public function create() {
        return view('admin.team.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|max:191',
            'designation' => 'required|max:191',
            'photo' => 'required|mimes:jpeg,jpg,png,svg|max:2048',
        ]);

        $input = $request->all();
        if ($file = $request->file('photo')) {
            $input['photo'] = admin_upload_image($file, 'assets/images/team');
        }

        Team::create($input);
        return redirect()->route('admin.team.index')->with('success', 'Team Member Added!');
    }

    public function edit($id) {
        $team = Team::findOrFail($id);
        return view('admin.team.edit', compact('team'));
    }

    public function update(Request $request, $id) {
        $team = Team::findOrFail($id);
        $input = $request->all();

        if ($file = $request->file('photo')) {
            admin_delete_image('assets/images/team', $team->photo ?? null);
            $input['photo'] = admin_upload_image($file, 'assets/images/team');
        }

        $team->update($input);
        return redirect()->route('admin.team.index')->with('success', 'Member Updated!');
    }

    public function destroy($id) {
        $team = Team::findOrFail($id);
        admin_delete_image('assets/images/team', $team->photo ?? null);
        $team->delete();
        return redirect()->back()->with('success', 'Member Deleted!');
    }
}