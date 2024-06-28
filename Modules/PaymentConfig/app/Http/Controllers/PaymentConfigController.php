<?php

namespace Modules\PaymentConfig\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\PaymentConfig\Entities\PaymentConfig;

class PaymentConfigController extends Controller
{
    public function index()
    {
        // Define the path where the modules are located
        $modulesPath = base_path('Modules');

        // Initialize an empty array for payment configurations
        $paymentConfigs = [];

        // Fetch all payment configurations from the database and index them by the 'gateway' field
        $paymentConfigs = PaymentConfig::all()->keyBy('gateway');
        // Initialize an empty array to store the configurations of the payment modules
        $paymentModules = [];

        // Iterate over each directory in the modules path
        foreach (File::directories($modulesPath) as $modulePath) {
            // Get the base name of the module path (i.e., the module name)
            $moduleName = basename($modulePath);

            // Define the path to the module's configuration file
            $configPath = $modulePath . '/module.json';

            // Check if the configuration file exists
            if (File::exists($configPath)) {
                // Read and decode the JSON configuration file
                $moduleConfig = json_decode(File::get($configPath), true);

                // Store the decoded configuration in the paymentModules array with the module name as the key
                $paymentModules[$moduleName] = $moduleConfig;
            }
        }
        // Iterate over each payment configuration
        foreach ($paymentConfigs as $gateway => $config) {
            // Decode the 'config' field of each payment configuration from JSON to an associative array
            $paymentConfigs[$gateway]->config = json_decode($config->config, true);
        }

        // Return the view with the payment modules and configurations
        return view('paymentconfig::index', compact('paymentModules', 'paymentConfigs'));
    }


    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'gateway' => 'required|string|max:255',
            'config' => 'required|array',
        ]);

        // Encode the 'config' array to a JSON string
        $config = json_encode($request->config);

        // Update the existing record or create a new one in the PaymentConfig table
        PaymentConfig::updateOrCreate(
            ['gateway' => $request->gateway],
            ['config' => $config]
        );

        // Redirect to the payment configuration index page with a success message
        return redirect()->route('paymentconfig.index')->with('success', 'Payment gateway configuration saved successfully.');
    }
}
