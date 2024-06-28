<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupWizardController extends Controller
{
    public function show()
    {
        // Display the setup wizard form
        return view('vendor.froiden-envato.install_message');
    }

    public function setup(Request $request)
    {
        // Handle the setup process
        // Ensure to set up the database here
        dd(1233);
    }
}
