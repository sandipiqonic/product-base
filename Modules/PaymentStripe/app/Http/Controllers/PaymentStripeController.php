<?php

namespace Modules\PaymentStripe\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\PaymentConfig\Entities\PaymentConfig;

class PaymentStripeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve the first PaymentConfig record where the 'gateway' field is 'square'
        $config = PaymentConfig::where('gateway', 'square')->first();
        
        // Return the 'paymentstripe::config' view with the retrieved configuration
        return view('paymentstripe::config', compact('config'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paymentstripe::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('paymentstripe::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('paymentstripe::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
