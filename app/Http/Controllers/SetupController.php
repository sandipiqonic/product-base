<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SetupController extends Controller
{
    public function index()
    {
        $requirements = [
            'PHP version (>= 8.0.0)' => version_compare(PHP_VERSION, '7.3.0', '>='),
            'BCMath Extension' => extension_loaded('bcmath'),
            'Ctype Extension' => extension_loaded('ctype'),
            'Fileinfo Extension' => extension_loaded('fileinfo'),
            'JSON Extension' => extension_loaded('json'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('pdo'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
            'GD Extension' => extension_loaded('gd'),
            'cURL Extension' => extension_loaded('curl'),
            'Zip Extension' => extension_loaded('zip'),
        ];

        return view('setup.index', compact('requirements'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'terms' => 'required|accepted',
    //         'db_host' => 'required',
    //         'db_port' => 'required|numeric',
    //         'db_database' => 'required',
    //         'db_username' => 'required',
    //         'db_password' => 'nullable',
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     // Save database configuration to .env
    //     $this->setEnv([
    //         'DB_HOST' => $request->db_host,
    //         'DB_PORT' => $request->db_port,
    //         'DB_DATABASE' => $request->db_database,
    //         'DB_USERNAME' => $request->db_username,
    //         'DB_PASSWORD' => $request->db_password,
    //     ]);

    //     // Test database connection
    //     try {
    //         DB::connection()->getPdo();
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withErrors('Could not connect to the database. Please check your configuration.');
    //     }

    //     // Create the admin user
    //     \App\Models\User::create([
    //         'username' => $request->name,
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password),
    //     ]);

    //     return redirect()->route('home'); // Or wherever you want to redirect after setup
    // }

    public function store(Request $request)
    {
        // dd(base_path());
        $request->validate([
            'terms' => 'required|accepted',
            'db_host' => 'required',
            'db_port' => 'required|numeric',
            'db_database' => 'required',
            'db_username' => 'required',
            'db_password' => 'nullable',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // dd(Artisan::call('migrate'));
        $this->setEnv([
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_database,
            'DB_USERNAME' => $request->db_username,
            'DB_PASSWORD' => $request->db_password,
        ]);


        try {
            $pdo = new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port};charset=utf8mb4",
                $request->db_username,
                $request->db_password
            );

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Create the database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$request->db_database}`;");
            // Switch to the specified database for migrations
            $basePath = base_path();
            // exec('php '.$basePath.'\artisan optimize:clear');
            $dbName = env('DB_DATABASE');
            $pdo->exec("USE `{$dbName}`;");

            exec('php '.$basePath.'\artisan migrate:fresh');


            // Check if migrations were successful
            // $output = Artisan::output();
            // dd($output); // Output the migration status if needed
            return redirect()->route('uisheet'); // Or wherever you want to redirect after setup

        } catch (\PDOException $e) {
            // Handle database connection errors here
            dd($e->getMessage()); // You can replace this with appropriate error handling
        }


        // die;
        // Artisan::call('cache:clear');
        // Artisan::call('config:cache');
        // dump('Config cache status: ', Artisan::output());
        // // Artisan::call('route:clear');
        // // Artisan::call('route:cache');
        // // dd($request->all());
        // $databaseName = $request->db_database;

        // dd(DB::statement("CREATE DATABASE IF NOT EXISTS $databaseName"));
        // // Save database configuration to .env
        // // Artisan::call('cache:clear');
        // // Artisan::call('migrate');
        // // dump(Artisan::call('config:cache'));
        // dump(Artisan::call('migrate', [
        //     '--force' => true, // Force migration in case there are any conflicts
        // ]));
        // dump(Artisan::output());
        // dd(env('DB_DATABASE'));
        // // Artisan::call('migrate', [
        // //     '--force' => true, // Force migration in case there are any conflicts
        // // ]);
        // // dd( Artisan::output());
        // // Test database connection
        // // try {
        //     // DB::connection()->getPdo();
        // // } catch (\Exception $e) {
        // //     return redirect()->back()->withErrors('Could not connect to the database. Please check your configuration.');
        // // }

        // // Create the database if it does not exist
        // // $databaseName = $request->db_database;
        // // // try {
        // //     $dbName = DB::connection()->getDatabaseName();
        // //     if ($dbName != $databaseName) {
        // //         // Database doesn't exist, create it
        // //         DB::statement("CREATE DATABASE IF NOT EXISTS $databaseName");
        // //     }
        // // } catch (\Exception $e) {
        // //     return redirect()->back()->withErrors('Could not create the database.');
        // // }

        // // Update .env file with the new database name
        // // $this->setEnv([
        // //     'DB_DATABASE' => $databaseName,
        // // ]);

        // // Artisan::call('config:clear');
        // // Artisan::call('cache:clear');
        // // Artisan::call('config:cache');
        // // Artisan::call('route:clear');
        // // Artisan::call('route:cache');
        // // // dd(1222222);
        // // Run migrations
        // // try {
        //     // Artisan::call('migrate', [
        //     //     '--force' => true, // Force migration in case there are any conflicts
        //     // ]);
        //     // dd(Artisan::call('migrate'));
        //     Log::info('Migration output: ' . Artisan::output());
        //     // dd( Artisan::output());
        // // } catch (\Exception $e) {
        // //     // Handle migration error
        // //     return redirect()->back()->withErrors('Migration failed: ' . $e->getMessage());
        // // }
        // // dd(Artisan::call('migrate'));
        // // Create the admin user
        // \App\Models\User::create([
        //     'username' => $request->name,
        //     'first_name' => $request->name,
        //     'last_name' => $request->name,
        //     'phone_number' => 9909520146,
        //     'status' => 'active',
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        // ]);

    }

    protected function setEnv($data)
    {
        foreach ($data as $key => $value) {
            $path = base_path('.env');
            if (file_exists($path)) {
                file_put_contents(
                    $path,
                    preg_replace(
                        "/^{$key}=.*/m",
                        "{$key}={$value}",
                        file_get_contents($path)
                    )
                );
            }
        }
    }
}
