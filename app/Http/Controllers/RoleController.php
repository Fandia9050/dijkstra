<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request){
        $roles = Role::all();

        if($request->ajax()){
            return response()->json([
                'roles' => $roles
            ]);
        }

        return view('roles.index', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string'
        ]);

        $roles = Role::create($request->all());

        return redirect('/roles')->with('success', 'Successfully added role');
    }

    public function roleUpdate(Request $request, Role $role){
        $request->validate([
            'name' => 'required|string'
        ]);

        $role->update($request->all());

        return redirect('/roles')->with('success', 'Successfully updated role');
    }

    public function roleDelete(Role $role){
        $role->delete();
        return redirect('/roles')->with('success', 'Successfully deleted role');
    }

    public function assignRole(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $roleUser = new UserRole();
        $roleUser->user_id = $request->user_id;
        $roleUser->role_id = $request->role_id;
        $roleUser->save();

        return redirect()->back()->with('success', 'Successfully assigned role');
    }

    public function rolePermissions(Request $request){
        if (!$request->has('id')) {
            return redirect('/roles');
        }

        $roleId = $request->id;

        // Ambil semua permission
        $permissions = Permission::all()->map(function ($permission) use ($roleId) {
        // cek apakah permission ini sudah di-assign ke role
        $isAssigned = RolePermission::where('role_id', $roleId)
                                    ->where('permission_id', $permission->id)
                                    ->exists();

        // tambahkan field boolean
        $permission->assigned = $isAssigned;

            return $permission;
            })->groupBy(function($permission) {
                // Grouping sesuai nama
                return explode('-', $permission->name)[1] ?? 'others';
            });


        return view('roles.permissions', [
            'permissions' => $permissions]);
    }

    public function assignPermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id'
        ]);
    
        $exists = RolePermission::where('role_id', $request->role_id)
            ->where('permission_id', $request->permission_id)
            ->first();
    
        if ($exists) {
            $exists->delete();
            $message = "Permission removed successfully";
        } else {
            RolePermission::create([
                'role_id' => $request->role_id,
                'permission_id' => $request->permission_id,
            ]);
            $message = "Permission assigned successfully";
        }
    
        return response()->json([
            'message' => $message
        ]);
    }
    
}
