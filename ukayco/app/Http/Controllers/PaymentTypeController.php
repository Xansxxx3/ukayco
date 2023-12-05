<?php

namespace App\Http\Controllers;
use App\Models\PaymentType;

use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function __invoke()
    {
        $paymentTypes = PaymentType::all();

        return response()->json($paymentTypes);
    }
}
