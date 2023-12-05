<?php

namespace App\Http\Controllers;
use App\Models\ProductConfiguration;
use Illuminate\Http\Request;
use App\Http\Resources\ProductConfigurationResource;
use App\Http\Requests\ProductConfigurationRequest;

class ProductConfigurationController extends Controller
{
    public function getAll()
    {
        $productConfigurations = ProductConfiguration::all();

        return response()->json($productConfigurations);
    }

    public function add(ProductConfigurationRequest $request)
    {
        $productConfiguration = ProductConfiguration::create($request->validated());

        return response()->json(['message' => 'success', 'data' => $productConfiguration], 201);
    }
}
