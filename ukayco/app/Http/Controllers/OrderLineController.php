<?php

namespace App\Http\Controllers;
use App\Models\Orderline;
use App\Http\Requests\OrderLineRequest;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\ShoppingCart;
use Notifications;
use App\http\Controllers\ShoppingCartController;
use App\Notifications\welcomeEmailNotification;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\OrderReceivedNotification;


class OrderLineController extends Controller
{
    public function __invoke()
    {
    $userId = auth()->user()->id;
    $orderLines = OrderLine::with([
        'user',
        'shippingAddress',
        'orderStatus',
        'productItem.productImages',
        'productItem.product'
    ])->where('user_id', $userId)->get();
    return response()->json($orderLines);
    }

    public function getAllOrderLines()
    {
    $orderLines = OrderLine::with([
        'user',
        // 'shippingAddress',
        // 'orderStatus',
        // 'productItem.productImages',
        'productItem.product'
    ])->get();

    return response()->json($orderLines);
    }

    public function getSingleOrderLine($id)
    {
        $orderLine = OrderLine::with([
            'user',
            'paymentMethod',
            'shippingAddress',
            'orderStatus',
            'shippingMethod',
            'productItem.productImages',
            'productItem.product'
        ])->find($id);

        if (!$orderLine) {
            return response()->json(['message' => 'Order line not found'], 404);
        }
        return response()->json($orderLine);
    }

    public function store(OrderLineRequest $request)
    {
    $validatedData = $request->validated();
    
    $sku = uniqid();
    $userId = auth()->user()->id;
    $validatedData['SKU'] = $sku;
    $validatedData['user_id'] = $userId;
    $firstSM = ShippingMethod::first();
    $validatedData['shipping_method_id'] = $firstSM ? $firstSM->id : null;
    $validatedData['order_date'] = now()->format('Y-m-d');    
    $validatedData['order_status_id'] = 1;

    // create orderLine
    try {
        //buyer
        $orderLine = OrderLine::create($validatedData);
        $orderLine->load('user', 'paymentMethod', 'shippingAddress.country', 'shippingMethod', 'productItem.user', 'productItem.product');        
        $user = auth()->user();
        $user->notify(new OrderPlacedNotification($orderLine));
        
        //clear shopping cart
        // $shoppingCartController = new ShoppingCartController();
        // $shoppingCartController->deleteAllShoppingCartItems($user->id);    
        $shoppingCart = ShoppingCart::where('user_id', $userId)->first();
        $shoppingCart->items()->delete();

        //seller
        $productOwner = $orderLine->productItem->user;
        $productOwner->notify(new OrderReceivedNotification($orderLine));
        
        return response()->json(['message' => 'success'], 201);
    } catch (\Exception $e) {
        return response()->json(['message' => 'error'], 500); 
    }
    }   

    public function getOrderLinesFromProductListings()
    {
    $userId = auth()->user()->id;

    $orderLines = OrderLine::whereIn('product_item_id', function ($query) use ($userId) {
        $query->select('id')
            ->from('product_items')
            ->where('user_id', $userId);
    })->with([
        'user',
        'paymentMethod',
        'shippingAddress',
        'orderStatus',
        'shippingMethod',
        'productItem.productImages',
        'productItem.product'
    ])->get();

    return response()->json($orderLines);
    }

    public function getOrderLinesByUser()
{
    // Get the authenticated user's ID
    $userId = auth()->user()->id;

    $orderLines = OrderLine::with([
        'user' => function ($query) {
            $query->select('id', 'username', 'first_name', 'last_name', 'date_of_birth', 'email', 'contact_number', 'gender' );
        },
        'paymentMethod',
        'shippingAddress',
        'orderStatus',
        'shippingMethod',
        'productItem.productImages',
        'productItem.product'
    ])->where('user_id', $userId)
      ->get();

    if ($orderLines->isEmpty()) {
        return response()->json(['message' => 'No order lines found for the user'], 404);
    }

    return $orderLines;
}

public function deleteOrderLine($ID)
{
$result = OrderLine::where('id', '=', $ID)->delete();
return response()->json([
    'status' => $result,
    'msg' => $result ? 'success' : 'fuck'
]);
}






}
