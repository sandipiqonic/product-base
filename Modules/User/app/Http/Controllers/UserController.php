<?php

namespace Modules\User\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('user::users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('user::users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'username' => 'required|string|max:255|unique:users,username',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15', // Adjust the max length as needed
            'status' => 'required|in:on,off',
        ]);
        $userData = $request->all();
        $userData['password'] = Hash::make($request->password);
        $userData['status'] = $request['status'] === 'on' ? 'active' : 'inactive';
        $user = User::create($userData);
        $user->assignRole($request->roles);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user::users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $userRole = $user->roles->pluck('name','name')->all();
        $roles = Role::pluck('name', 'name')->all();
        return view('user::users.edit', compact('user','roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15',
        ]);

        $user = User::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->status === 'on' ? 'active' : 'inactive';

        // Update user
        $user->update($data);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->roles);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}
