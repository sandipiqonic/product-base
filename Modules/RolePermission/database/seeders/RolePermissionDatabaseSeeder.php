<?php

namespace Modules\RolePermission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\app\Models\Permission;

class RolePermissionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the route entry already exists before inserting it
        $existingRoute = DB::table('routes')->where([
            'name' => 'roles.index',
            'method' => 'GET',
            'uri' => '/',
            'controller' => 'RoleController@index',
            'middleware' => 'web',
        ])->first();

        if (!$existingRoute) {
            // If the route entry doesn't exist, insert it
            DB::table('routes')->insert([
                'name' => 'roles.index',
                'method' => 'GET',
                'uri' => '/',
                'controller' => 'RoleController@index',
                'middleware' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert default permissions if they don't already exist
        $permissions = [
            [
                'name' => 'role',
                'title' => 'Role',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'role add',
                'title' => 'Role Add',
                'guard_name' => 'web',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'role list',
                'title' => 'Role List',
                'guard_name' => 'web',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permission',
                'title' => 'Permission',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permission add',
                'title' => 'Permission Add',
                'guard_name' => 'web',
                'parent_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permission list',
                'title' => 'Permission List',
                'guard_name' => 'web',
                'parent_id' => 4,
                'created_at' => '2021-06-04 06:59:54',
                'updated_at' => '2021-06-04 06:59:54',
            ],
        ];

        foreach ($permissions as $permissionData) {
            $existingPermission = Permission::where('name', $permissionData['name'])->first();
            if (!$existingPermission) {
                Permission::create($permissionData);
            }
        }

        // Retrieve the list of activated plugins
        $activePlugins = DB::table('plugins')->where('status', true)->get();

        foreach ($activePlugins as $plugin) {
            // Define the parent permission for the plugin
            $parentPermissionData = [
                'name' => $plugin->name,
                'title' => ucfirst($plugin->name),
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $parentPermission = Permission::where('name', $parentPermissionData['name'])->first();
            if (!$parentPermission) {
                $parentPermission = Permission::create($parentPermissionData);
            }

            // Define the child permissions for the plugin
            $pluginPermissions = [
                [
                    'name' => $plugin->name,
                    'title' => ucfirst($plugin->name),
                    'guard_name' => 'web',
                    'parent_id' => $parentPermission->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];

            foreach ($pluginPermissions as $permissionData) {
                $existingPermission = Permission::where('name', $permissionData['name'])->first();
                if (!$existingPermission) {
                    Permission::create($permissionData);
                }
            }
        }
    }
}


