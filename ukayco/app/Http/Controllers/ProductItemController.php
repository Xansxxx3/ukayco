<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductItemResource;
use App\Http\Requests\ProductItemRequest;
use App\Http\Requests\ImageFileRequest;
use App\Models\ProductItem;
use App\Models\ProductImage;
use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductItemController extends Controller
{
    public function __invoke()
    {
        $productItems = ProductItem::with('product', 'productImages', 'productConfigurations.variationOption.variation')->get();    
        return $productItems;
    }

    public function getProductItemsByProductType($productId)
    {
    $productItems = ProductItem::with('product.productCategory', 'productImages', 'productConfigurations.variationOption.variation')
        ->where('product_id', $productId)
        ->get();
    
    return $productItems;
    
    }

    public function getProductItemsByUser()
{
    $userId = auth()->user()->id;

    $productItems = ProductItem::with('product.productCategory', 'productImages', 'productConfigurations.variationOption.variation')
        ->where('user_id', $userId)
        ->get();
    
    return $productItems;
}

    public function store(ProductItemRequest $request)
{
    $user = auth()->user();
    $userId = $user->id;
    $sku = uniqid();
    $productItem = ProductItem::create(array_merge($request->validated(), [
        'user_id' => $userId,
        'SKU' => $sku,
    ]));

    // Handle product images
     if($request->HasFile('product_images')){
            $uploadPath = 'uploads/products/';
            $i=1;
            foreach($request->file('product_images') as $imageFile){
                $extension = $imageFile->getClientOriginalExtension();
                $fileName = time().$i++.'.'.$extension;
                $imageFile->move($uploadPath,$fileName);
                $finalImagePath = $uploadPath.$fileName;

                $productImage = new ProductImage([
                    'product_id' => $productItem->id,
                    'product_image' => asset($finalImagePath),
                ]);
                $productItem->productImages()->save($productImage);
            }
        }
    $productItem->load('product', 'productImages', 'variationOptions.variation');

    return $productItem;
}

    public function show($id)
    {
        $product_item = ProductItem::where('id', '=', $id)->first();
        return response()->json([
            'status' => "Success",
            'Body' => $product_item,
        ], 200);
    }

    public function update(ProductItemRequest $request, $id)
    {
        $productItem = ProductItem::find($id);
        $productItem->update($request->validated());
        return response()->json([
            'status' => "Success",
            'Body' => new ProductItemResource($productItem),

        ], 200);
    }

    public function destroy($id)
    {
    $result = ProductItem::where('id', '=', $id)->delete();
    return response()->json([
        'status' => $result,
        'msg' => $result ? 'success' : 'failed'
    ]);
    }

    public function getProductItem($id)
    {
        $productItem = ProductItem::find($id);
    
        if (!$productItem) {
            return response()->json(['message' => 'ProductItem not found'], 404);
        }
    
        $productItem->load('user', 'product', 'product.productCategory', 'productImages', 'variationOptions.variation');
        return response()->json(['data' => $productItem], 200);
    }

//     public function imageUpload(Request $request)
// {
//     // return $request;
//     if ($request->hasFile('File')) {
//         $file = $request->file('File');
//         $fileName = time() . '_' . $file->getClientOriginalName();
//         $file->move(public_path('uploads'), $fileName); // Save the file to the public/uploads directory
//         return response()->json(['message' => 'File uploaded successfully'],200);
//     } else {    
//         return response()->json(['message' => 'File not provided'], 400);
//     }
// }

public function addListing(ProductItemRequest $request)
{
    $user = auth()->user();
    $userId = $user->id;
    $sku = uniqid();
    $productItem = ProductItem::create(array_merge($request->validated(), [
        'user_id' => $userId,
        'SKU' => $sku,
    ]));
    

    foreach ($request->allFiles() as $fieldName => $file) {
        if ($file->isValid()) {
            $uniqueFileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $uniqueFileName);
            
            $finalImagePath = 'uploads/' . $uniqueFileName; 
            $productImage = new ProductImage([
                'product_id' => $productItem->id,
                'product_image' => asset($finalImagePath),
            ]);
            $productItem->productImages()->save($productImage);
        } else {
             return response()->json(['messages' => 'image not valid'], 200);
        }
    }

    return response()->json(['message' => 'success', 'data' => $productItem], 200);
}


public function deleteListing($id)
{
$result = ProductItem::where('id', '=', $id)->delete();
return response()->json([
    'status' => $result,
    'msg' => $result ? 'success' : 'fuck'
]);
}




}
