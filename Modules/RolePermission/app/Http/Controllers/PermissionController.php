<?php

namespace Modules\RolePermission\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\RolePermission\app\Models\Permission;
use Modules\RolePermission\app\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permission = Permission::orderBy('name','ASC')->whereNull('parent_id')->with('subpermission')->get();
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.permission')  ]);

        $roles = Role::where('status',1)->orderBy('name','ASC');
        if(!\Auth::user()->hasRole('admin')){
            $roles->where('name','!=','admin');
        }
        $roles = $roles->get();

        $auth_user = authSession();
        return view('rolepermission::permission.index',compact(['roles','permission','pageTitle','auth_user']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rolepermission::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if(demoUserPermission()){
        //     return redirect()->route('permission.index')->withError( __('messages.demo_permission_denied') );
        // }
        // dd($request->all());
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $data = isset($request->permission) ? $request->permission : [];
        $permission_list = Permission::orderBy('name','ASC')->get()->unique('name');
        $roles=Role::whereNotIn('name',['admin'])->get()->map(function($role) use($permission_list){
            $role->revokePermissionTo($permission_list);
        });
        if(count($data)>0){
            foreach ($data as $key => $permission){
                foreach ($permission as $role){
                    $permission = Permission::findOrCreate($key);
                    $guard = Role::findOrCreate($role,'web');
                    $guard->givePermissionTo($permission);
                }
            }
        }
        return redirect()->route('permissions.index')->withSuccess('Permission granted Successfully');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('rolepermission::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('rolepermission::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
