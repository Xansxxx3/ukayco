<?php

namespace App\Http\Controllers;
use App\Models\VariationOption;
use Illuminate\Http\Request;
use App\Http\Requests\VariationOptionRequest;
use App\Http\Resources\VariationOptionResource;


class VariationOptionController extends Controller
{
    public function __invoke()
    {
        return VariationOption::query()->get();
    }

    public function show($id)
    {
        $variationOption = VariationOption::where('id', '=', $id)->first();
        return response()->json([
            'status' => "Success",
            'Body' => new VariationOptionResource($variationOption),
        ], 200);
    }

    public function store(VariationOptionRequest $request)
    {
        $validatedData = $request->validated();

      
        $variationOption = VariationOption::create([
            'value' => $validatedData['value'],
            'variation_id' => $validatedData['variation_id'],
        ]);

        return response()->json([
            'status' => 'Success',
            'Body' => $variationOption,
        ], 201); 
    }

    public function update(VariationOptionRequest $request, $id)
    {
        $variationOption = VariationOption::find($id);
        $variationOption->update($request->validated());

        return response()->json([
            'status' => 'Success',
            'Body' => new VariationOptionResource($variationOption),
        ], 200);
    }

    public function destroy($id)
    {
    $result = VariationOption::where('id', '=', $id)->delete();
    return response()->json([
        'status' => $result,
        'msg' => $result ? 'success' : 'failed'
    ]);
    }
}
