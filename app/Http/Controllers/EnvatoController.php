<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Froiden\Envato\FroidenEnvato;
// use FroidenEnvato;


class EnvatoController extends Controller
{
    // public function verify(Request $request)
    // {
    //     $purchaseCode = $request->input('purchase_code');
    //     $purchaseDetails = FroidenEnvato::verifyPurchaseCode($purchaseCode);

    //     if ($purchaseDetails['verify'] == true) {
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $purchaseDetails
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Invalid purchase code'
    //         ]);
    //     }
    // }

    public function verify(Request $request)
    {
        return view('vendor.froiden-envato.install_message');
    }
}
