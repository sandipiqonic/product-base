<?php

namespace App\Http\Controllers\Plugin;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;

class PluginController extends Controller
{

    public function plugin(Request $request)
    {
        // if ($request->ajax()) {
        //     $plugins = Plugin::query();
        //     return DataTables::of($plugins)
        //         ->editColumn('name', function ($plugins) {
        //             return isset($plugins->name) ? $plugins->name : "N/A";
        //         })
        //         ->editColumn('description', function ($plugins) {
        //             return isset($plugins->description) ? $plugins->description : "N/A";
        //         })
        //         ->editColumn('version', function ($plugins) {
        //             return isset($plugins->version) ? $plugins->version : "N/A";
        //         })
        //         ->editColumn('status', function ($plugin) {
        //             $badgeClass = $plugin->status == 'active' ? 'bg-success' : 'bg-danger';
        //             return '<span class="badge rounded-pill ' . $badgeClass . '">' . ucfirst($plugin->status) . '</span>';
        //         })
        //         ->addColumn('action', function ($plugin) {
        //             $statusButton = $plugin->status == 'active'
        //                 ? '<button type="button" class="btn btn-sm btn-danger" onclick="togglePluginStatus(' . $plugin->id . ', \'active\')">Deactivate</button>'
        //                 : '<button type="button" class="btn btn-sm btn-success" onclick="togglePluginStatus(' . $plugin->id . ', \'inactive\')">Activate</button>';
        //                 return $statusButton;
        //             })
        //         ->rawColumns(['action','status'])
        //         ->make(true);
        // }

        $plugins = Plugin::get();
        $totalPlugins = $plugins->count();
        $activePlugins = $plugins->where('status', 'active')->count();
        $inactivePlugins = $plugins->where('status', 'inactive')->count();

        return view('plugin.create', compact('totalPlugins', 'activePlugins', 'inactivePlugins', 'plugins'));
    }

    // public function pluginStore(Request $request)
    // {
    //     // dd($request->all());
    //     // Validate the request
    //     $request->validate([
    //         'zip' => 'required|file|mimes:zip',
    //     ]);

    //     if (!$request->hasFile('zip')) {
    //         return back()->with('error', 'Please upload a valid zip file.');
    //     }

    //     $file = $request->file('zip');
    //     $filename = time() . '_' . $file->getClientOriginalName();
    //     $path = $file->storeAs('temp', $filename);
    //     $zipFile = storage_path('app/' . $path);

    //     $zip = new ZipArchive;
    //     if ($zip->open($zipFile) !== TRUE) {
    //         Storage::delete($path);
    //         return back()->with('error', 'Failed to open the zip file.');
    //     }

    //     $moduleJsonData = $this->getModuleJsonData($zip);
    //     if (!$moduleJsonData) {
    //         $zip->close();
    //         Storage::delete($path);
    //         return back()->with('error', 'The zip file does not contain a valid module.json file.');
    //     }

    //     $moduleName = $moduleJsonData['name'];
    //     $moduleVersion = $moduleJsonData['version'];

    //     if (!$moduleVersion) {
    //         $zip->close();
    //         Storage::delete($path);
    //         return back()->with('error', 'The module.json file does not contain a version.');
    //     }

    //     if ($this->moduleExists($moduleName, $moduleVersion)) {
    //         $zip->close();
    //         Storage::delete($path);
    //         return back()->with('error', 'A module with the same version already exists.');
    //     }

    //     $this->extractModule($zip, base_path('Modules'));
    //     $zip->close();

    //     // Add the plugin name to modules_statuses.json
    //     $this->updateModulesStatus($moduleName, false);

    //     $this->runMigrations($moduleName);
    //     $this->registerModule($moduleName);

    //     Storage::delete($path);

    //     return back()->with('success', 'Plugin uploaded and extracted successfully.');
    // }

    public function pluginStore(Request $request)
    {
        // Validate the request
        $request->validate([
            'zip' => 'required|file|mimes:zip',
        ]);

        if (!$request->hasFile('zip')) {
            return back()->with('error', 'Please upload a valid zip file.');
        }

        $file = $request->file('zip');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('temp', $filename);
        $zipFile = storage_path('app/' . $path);

        $zip = new ZipArchive;
        if ($zip->open($zipFile) !== TRUE) {
            Storage::delete($path);
            return back()->with('error', 'Failed to open the zip file.');
        }

        $moduleJsonData = $this->getModuleJsonData($zip);
        if (!$moduleJsonData) {
            $zip->close();
            Storage::delete($path);
            return back()->with('error', 'The zip file does not contain a valid module.json file.');
        }

        $moduleName = $moduleJsonData['name'];
        $moduleVersion = $moduleJsonData['version'];

        if (!$moduleVersion) {
            $zip->close();
            Storage::delete($path);
            return back()->with('error', 'The module.json file does not contain a version.');
        }

        // Check existing module version
        $existingModuleVersion = $this->getExistingModuleVersion($moduleName);
        if ($existingModuleVersion && version_compare($moduleVersion, $existingModuleVersion, '>')) {
            if (!$request->has('confirm_update')) {
                // Return response to trigger the confirmation popup
                return response()->json([
                    'status' => 'confirm_update',
                    'message' => 'A newer version of this plugin is available. Do you want to update?',
                    'module_name' => $moduleName,
                    'new_version' => $moduleVersion,
                    'existing_version' => $existingModuleVersion,
                ]);
            }
        }

        $this->extractModule($zip, base_path('Modules'));
        $zip->close();

        // Add the plugin name to modules_statuses.json
        $this->updateModulesStatus($moduleName, false);

        $this->runMigrations($moduleName);
        $this->registerModule($moduleName);

        Storage::delete($path);

        return response()->json(['message' => 'Plugin Updated successfully.']);
    }

    private function getExistingModuleVersion($moduleName)
    {
        // Implement this function to retrieve the existing module version
        $moduleJsonFile = base_path('Modules/' . $moduleName . '/module.json');
        if (File::exists($moduleJsonFile)) {
            $moduleJsonData = json_decode(File::get($moduleJsonFile), true);
            return $moduleJsonData['version'] ?? null;
        }
        return null;
    }

    private function updateModulesStatus($moduleName, $status)
    {
        $statusesFile = base_path('modules_statuses.json');
        $statuses = json_decode(File::get($statusesFile), true);
        // Set the status based on $status
        $statuses[$moduleName] = $status == 'active' ? true : false;

        File::put($statusesFile, json_encode($statuses, JSON_PRETTY_PRINT));
    }

    private function getModuleJsonData($zip)
    {
        $moduleJsonPath = '';
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            if (basename($stat['name']) == 'module.json') {
                $moduleJsonPath = dirname($stat['name']);
                break;
            }
        }
        if (!$moduleJsonPath) {
            return null;
        }
        $moduleJson = $zip->getFromName($moduleJsonPath . '/module.json');
        if (!$moduleJson) {
            return null;
        }
        return json_decode($moduleJson, true);
    }

    private function moduleExists($moduleName, $moduleVersion)
    {
        $existingModulePath = base_path('Modules/' . $moduleName);
        if (!File::exists($existingModulePath)) {
            return false;
        }
        $existingModuleJsonPath = $existingModulePath . '/module.json';
        if (!File::exists($existingModuleJsonPath)) {
            return false;
        }
        $existingModuleData = json_decode(File::get($existingModuleJsonPath), true);
        $existingModuleVersion = $existingModuleData['version'] ?? null;
        return version_compare($moduleVersion, $existingModuleVersion, '<=');
    }

    private function extractModule($zip, $extractPath)
    {
        $zip->extractTo($extractPath);
    }

    private function runMigrations($moduleName)
    {
        Artisan::call('migrate', [
            '--path' => 'Modules/' . $moduleName . '/database/migrations',
            '--force' => true,
        ]);
    }

    private function registerModule($moduleName)
    {
        $moduleJsonPath = base_path('Modules') . '/' . $moduleName . '/module.json';
        if (!File::exists($moduleJsonPath)) {
            return false; // Module.json not found
        }

        $moduleJsonData = json_decode(File::get($moduleJsonPath), true);

        // Check if the plugin already exists
        $existingPlugin = Plugin::where('name', $moduleJsonData['name'])->first();

        if ($existingPlugin) {
            // Update existing plugin details
            $existingPlugin->update([
                'alias' => $moduleJsonData['alias'],
                'description' => $moduleJsonData['description'],
                'version' => $moduleJsonData['version'],
            ]);
        } else {
            // Create a new plugin
            Plugin::create([
                'name' => $moduleJsonData['name'],
                'alias' => $moduleJsonData['alias'],
                'description' => $moduleJsonData['description'],
                'version' => $moduleJsonData['version'],
                'status' => 'inactive',
            ]);
        }

        return true;
    }

    public function pluginActivate(Request $request)
    {
        try {
            $plugin = Plugin::findOrFail($request->id);

            $newStatus = $request->status === 'active' ? 'active' : 'inactive';
            if ($newStatus === 'active') {
                $this->extractPluginZip($plugin->name);
                $this->runPluginSeeder($plugin->name);
            } else {
                // Deactivate all child plugins
                $this->deactivateChildPlugins($plugin);
            }

            $plugin->status = $newStatus;
            $plugin->update();

            // Update the plugin status in modules_statuses.json
            $this->updateModulesStatus($plugin->name, $newStatus == 'active');
            // If the plugin is being activated, run the plugin's seeder
            return response()->json(['success' => 'Plugin status updated successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Plugin not found'], 404);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => 'Error updating plugin status'], 500);
        }
    }

    private function runPluginSeeder($pluginName)
    {
        $seederClass = 'Modules\\'. $pluginName . '\\database\\seeders\\'. $pluginName .'DatabaseSeeder';
        if (class_exists($seederClass)) {
            \Artisan::call('db:seed', ['--class' => $seederClass]);
        } else {
            throw new \Exception('Seeder class not found for plugin: ' . $pluginName);
        }
    }

    // public function pluginRemove(Request $request)
    // {
    //     try {
    //         $plugin = Plugin::find($request->id);
    //         $moduleDirectory = base_path('Modules/');
    //         $pluginFile = $moduleDirectory . $plugin->name;

    //        $modulesStatusFile = base_path('modules_statuses.json');
    //        if (File::exists($modulesStatusFile)) {
    //         File::deleteDirectory($pluginFile);
    //            // Read the JSON data from the file
    //            $jsonContent = File::get($modulesStatusFile);
    //            $modulesStatuses = json_decode($jsonContent, true);

    //            // Search for the plugin's name as a key in the JSON data
    //            // and remove it if found
    //            if (isset($modulesStatuses[$plugin->name])) {
    //                unset($modulesStatuses[$plugin->name]);
    //            }

    //            // Write the modified JSON data back to the file
    //            File::put($modulesStatusFile, json_encode($modulesStatuses, JSON_PRETTY_PRINT));
    //        }
    //         $plugin->delete();
    //         return redirect()->route('plugins')->with('success', 'Plugin deleted successfully.');
    //     } catch (ModelNotFoundException $e) {
    //         // Handle the case where the model is not found
    //         return redirect()->route('plugins')->with('error', 'Model not found.');
    //     } catch (Exception $e) {
    //         // Handle any other exceptions that may occur
    //         \Log::error('Error deleting model: '.$e->getMessage());

    //         return redirect()->route('plugins')->with('error', 'An error occurred while deleting the model.');
    //     }
    // }

    private function extractPluginZip($pluginName)
    {
        $pluginPath = base_path('Modules/' . $pluginName . '/plugin');
        $extractPath = base_path('Modules/');
        if (!is_dir($pluginPath)) {
            return;
        }
        if (!is_dir($pluginPath)) {
            throw new \Exception('Plugin directory not found: ' . $pluginPath);
        }

        $zipFiles = glob($pluginPath . '/*.zip');

        if (empty($zipFiles)) {
            throw new \Exception('No zip files found in plugin directory: ' . $pluginPath);
        }

        $errors = [];
        foreach ($zipFiles as $zipFile) {
            try {

                $this->dependencyplugin($zipFile , $extractPath, $pluginName);
            } catch (\Exception $e) {
                $errors[] = 'Error extracting plugin zip file: ' . $zipFile . ' - ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            throw new \Exception('Errors occurred during extraction: ' . implode(', ', $errors));
        }
    }

    private function dependencyplugin($zipFile, $extractPath, $pluginName)
    {
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            $moduleJsonData = $this->getModuleJsonData($zip);
            if (!$moduleJsonData) {
                $zip->close();
                throw new \Exception('The zip file does not contain a valid module.json file.');
            }
            // Get parent plugin ID if it exists
            $mainPluginId = Plugin::where('name', $pluginName)->first();
            $moduleName = $moduleJsonData['name'] ?? '';
            $moduleVersion = $moduleJsonData['version'] ?? '';
            $moduleDescription = $moduleJsonData['description'] ?? '';
            $modulealias = $moduleJsonData['alias'] ?? '';

            if (!$moduleVersion) {
                $zip->close();
                throw new \Exception('The module.json file does not contain a version.');
            }

            // Extract the module
            $zip->extractTo($extractPath);
            $zip->close();

            //  // Add or update the plugin in the database
            Plugin::updateOrCreate(
                ['name' => $moduleName],
                [
                    'description' => $moduleDescription,
                    'version' => $moduleVersion,
                    'alies' => $modulealias,
                    'parent_id' => $mainPluginId->id,
                    'status' => 'active',
                ]
            );

            $statusesFile = base_path('modules_statuses.json');
            $statuses = json_decode(File::get($statusesFile), true);
            $statuses[$moduleName] =  true;
            File::put($statusesFile, json_encode($statuses, JSON_PRETTY_PRINT));
            // Run migrations for the module
            $this->runMigrations($moduleName);

        } else {
            throw new \Exception('Failed to open plugin zip file: ' . $zipFile);
        }
    }

    private function deactivateChildPlugins($plugin)
    {
        $childPlugins = Plugin::where('parent_id', $plugin->id)->get();
        foreach ($childPlugins as $childPlugin) {
            $childPlugin->status = 'inactive';
            $childPlugin->update();

            // Update the plugin status in modules_statuses.json for the child plugin
            $this->updateModulesStatus($childPlugin->name, false);

            // Recursively deactivate children of the child plugin
            $this->deactivateChildPlugins($childPlugin);
        }
    }

    public function pluginRemove(Request $request)
    {
        try {
            $plugin = Plugin::findOrFail($request->id);
            $this->deletePluginAndChildren($plugin);

            return redirect()->route('plugins')->with('success', 'Plugin and its dependencies deleted successfully.');
        } catch (ModelNotFoundException $e) {
            // Handle the case where the model is not found
            return redirect()->route('plugins')->with('error', 'Plugin not found.');
        } catch (Exception $e) {
            // Handle any other exceptions that may occur
            \Log::error('Error deleting plugin: '.$e->getMessage());

            return redirect()->route('plugins')->with('error', 'An error occurred while deleting the plugin.');
        }
    }

    private function deletePluginAndChildren($plugin)
    {
        // Find all child plugins
        $childPlugins = Plugin::where('parent_id', $plugin->id)->get();

        // Recursively delete each child plugin
        foreach ($childPlugins as $childPlugin) {
            $this->deletePluginAndChildren($childPlugin);
        }

        // Perform deletion operations for the plugin
        $moduleDirectory = base_path('Modules/');
        $pluginFile = $moduleDirectory . $plugin->name;

        $modulesStatusFile = base_path('modules_statuses.json');
        if (File::exists($modulesStatusFile)) {
            File::deleteDirectory($pluginFile);
            // Read the JSON data from the file
            $jsonContent = File::get($modulesStatusFile);
            $modulesStatuses = json_decode($jsonContent, true);

            // Search for the plugin's name as a key in the JSON data
            // and remove it if found
            if (isset($modulesStatuses[$plugin->name])) {
                unset($modulesStatuses[$plugin->name]);
            }

            // Write the modified JSON data back to the file
            File::put($modulesStatusFile, json_encode($modulesStatuses, JSON_PRETTY_PRINT));
        }

        // Delete the plugin from the database
        $plugin->delete();
    }



}
