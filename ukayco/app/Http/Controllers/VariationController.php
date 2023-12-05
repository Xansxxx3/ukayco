<?php

namespace App\Http\Controllers;
use App\Models\Variation;
use App\Models\VariationOption;
use Illuminate\Http\Request;
use App\Http\Requests\VariationRequest;
use App\Http\Resources\VariationResource;

class VariationController extends Controller
{
    public function __invoke()
    {
        return [
            'Variations ' => Variation::query()->get(),
        ];
    }

    public function show($id)
    {
        $variation = Variation::where('id', '=', $id)->first();
        return response()->json([
            'status' => "Success",
            'Body' => $variation,
        ], 200);
    }

    public function getVariationOptions($id)
    {
        $variationOptions = VariationOption::where('variation_id', $id)->get();

    if ($variationOptions->isEmpty()) {
        return response()->json(['error' => 'Variation options not found for the given variation ID'], 404);
    }

    return $variationOptions;
    }


    public function store(VariationRequest $request)
    {
        $validatedData = $request->validated();

        $variation = Variation::create([
            'name' => $validatedData['name'],
            'category_id' => $validatedData['category_id'],
        ]);

        return response()->json([
            'status' => 'Success',
            'Body' => $variation,
        ], 201);
    }

    public function update(VariationRequest $request, $id)
    {
        $variation = Variation::find($id);
        $variation->update($request->validated());

        return response()->json([
            'status' => 'Success',
            'Body' => new VariationResource($variation),
        ], 200);
    }

    public function destroy($id)
    {
    $result = Variation::where('id', '=', $id)->delete();
    return response()->json([
        'status' => $result,
        'msg' => $result ? 'success' : 'failed'
    ]);
    }
    
}
