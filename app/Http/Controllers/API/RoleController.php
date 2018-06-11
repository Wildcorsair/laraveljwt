<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function store() {
//        $role = Role::create(['name' => 'administrator']);
//        $permission = Permission::create(['name' => 'management-view']);
//        $role->givePermissionTo($permission);
//        $permission->assignRole($role);

        $user = Auth::user();
        $user->assignRole('administrator');
    }
}
