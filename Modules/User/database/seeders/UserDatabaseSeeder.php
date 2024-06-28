<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingRoute = DB::table('routes')->where([
            'name' => 'user.index',
                'method' => 'GET',
                'uri' => '/',
                'controller' => 'UserController@index',
                'middleware' => 'web',
        ])->first();

        if (!$existingRoute) {
            // If the route entry doesn't exist, insert it
            DB::table('routes')->insert([
                'name' => 'user.index',
                'method' => 'GET',
                'uri' => '/',
                'controller' => 'UserController@index',
                'middleware' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
