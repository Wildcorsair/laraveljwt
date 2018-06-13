<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;


class RoleController extends Controller
{
    public function store() {
      //  $role = Role::create(['name' => 'customer']);
      //  $permission = Permission::create(['name' => 'dashboard-view']);

       // Assign permission to role
      //  $role->givePermissionTo($permission);

       // Assign role to permission
       // $permission->assignRole($role);

        // $user = Auth::user();
        $user = User::find(2);
        // dd($user->hasRole('administrator'));
        $user->assignRole('customer');
    }
}
