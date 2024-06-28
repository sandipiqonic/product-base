<?php

namespace Modules\PaymentConfig\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentConfigDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        DB::table('routes')->insert([
            [
                'name' => 'paymentconfig.index',
                'method' => 'GET',
                'uri' => '/',
                'controller' => 'PaymentConfigController@index',
                'middleware' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
