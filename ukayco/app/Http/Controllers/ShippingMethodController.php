<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingMethod;
class ShippingMethodController extends Controller
{
    public function __invoke()
    {
        $shippingMethods = ShippingMethod::all();
        return response()->json($shippingMethods);
    }

    public function getFirst()
    {
    $firstShippingMethod = ShippingMethod::first();

    if ($firstShippingMethod) {
        return response()->json($firstShippingMethod);
    } else {
        return response()->json(['message' => 'No shipping methods found.'], 404);
    }
    }
}
