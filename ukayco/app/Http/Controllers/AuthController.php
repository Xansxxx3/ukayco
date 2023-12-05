<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\ShoppingCart;
use App\Models\UserPaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Notifications;
use App\Notifications\welcomeEmailNotification;
use App\Notifications\EmailVerificationCodeNotification;
use App\Notifications\WelcomeSMSNotification;

use App\Notifications\SMSVerificationCodeNotification;





class AuthController extends Controller{


    public function __invoke()
{
    $users = User::with('shoppingCart')->get();
    
    return response()->json([
        'message' => 'success',
        'data' => $users
    ]);
}

    public function login(Request $request){

        try {
            $credentials = $request->validate([
                'email' => 'required|string|email|exists:users,email',
                'password' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response([
                'message' => 'Invalid Email or Password'
            ], 404);
        }


        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'Invalid Password of Email'
            ], 404);
        }
        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;
        $user->load('shoppingCart');

        // $user->notify(new WelcomeSMSNotification($user));


        return response()->json([ 
            'message'=> 'Authorized',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'username' => $user->username,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'user_id' => $user->id,
            'shopping_cart' => $user->shoppingCart
            ]);     
        }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out Successfully']);
    }

    public function register(UserRequest $request){
    try {
    
    $validatedData = $request->validated(); 
    $user = User::create($validatedData);

    // $userPaymentMethodData = [
    //     'user_id' => $user->id,
    //     'payment_type_id' => 1, // Assuming payment_type_id is 1
    //     'provider' => ' ',
    //     'account_number' => ' ',
    //     'expiry_date' => ' ',
    //     'is_default' => true,
    // ];
    // $userPaymentMethod = UserPaymentMethod::create($userPaymentMethodData);
;
    // dd($userData);
        $shoppingCart = null; 
    if (!$user->shoppingCart) {
        $shoppingCart = ShoppingCart::create([
        'user_id' => $user->id,
        ]);
    }

        
    $user = User::with('shoppingCart')->find($user->id);
    $token = $user->createToken('auth-token')->plainTextToken;

    $user->notify(new welcomeEmailNotification($user));
        
    return response()->json([ 
        'message'=> 'success',
        // 'User' => $userData,
        'access_token' => $token,
        'token_type' => 'Bearer',
        'expires_in' => null,
        'username' => $user->username,
        'user_id' => $user->id,
        'email' => $user->email,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'contact_number' => $user->contact_number,
        'ShoppingCart' => $shoppingCart,
        ]);     
    } catch (Exception $e) {
        return response()->json([
        'message' => 'Error',
        ], 500);
    }
}
public function checkEmail(Request $request)
{
    try {
        $email = $request->input('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Input should be a valid email address'], 422);
        }

        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            return response()->json(['message' => 'Email already taken'], 422);
        }

        return response()->json(['message' => 'Email is available'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred while checking email'], 500);
    }
}

public function changePassword(Request $request)
{
    try {
        $email = $request->input('email');
        $newPassword = $request->input('new_password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Input should be a valid email address'], 422);
        }

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            // Update the user's password
            $existingUser->password = bcrypt($newPassword); // Assuming password is hashed using bcrypt

            // Save the updated password
            $existingUser->save();

            return response()->json(['message' => 'Password changed successfully'], 200);
        }

        return response()->json(['message' => 'User not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred while changing password'], 500);
    }
}
public function getUserByEmail(Request $request){

    try {
        $email = $request->input('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Input should be a valid email address'], 422);
        }

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            return response()->json(['success' => $existingUser->contact_number], 200);
        }

        return response()->json(['message' => 'Cannot Find Email'], 422);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred while checking email'], 500);
    }
}

public function checkUsername(Request $request)
{
    try {
        $username = $request->input('username');

        if (!preg_match('/\d/', $username)) {
            return response()->json(['message' => 'Username should contain numbers'], 422);
        }

        $existingUser = User::where('username', $username)->first();

        if ($existingUser) {
            return response()->json(['message' => 'Username already taken'], 422);
        }

        return response()->json(['message' => 'Username is available'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred while checking the username'], 500);
    }
}

    



    public function getShoppingCartByUser($id){

        $user = User::find($id); 
        if (!$user) {
            return "User Not Found"; 
        }
        $shoppingCart = $user->shoppingCart;
        if (!$shoppingCart) {
            return "Shopping Cart Not Found."; 
        }
        $shoppingCart->load('items.productItem.productImages', 'items.productItem.product');
    
        return $shoppingCart;  
        return response()->json(['success' =>  $shoppingCart], 200);
        }
    

    public function getUser(){

        $userId = auth()->user();
        if (!$userId) {
            return "User Not Found"; 
        }
       
    
        return $userId;
        }

    public function googleRedirect(Request $request){
        try {
            $email = $request->input('email');
            $user = User::where('email', $email)->first();   
            
            if ($user) {
                Auth::login($user);
                $token = $user->createToken('auth-token')->plainTextToken;
                return response()->json([ 
                    'message' => 'Success',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => null,
                    'username' => $user->username,
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'contact_number' => $user->contact_number,
                    'shopping_cart' => $user->shoppingCart
                ]);
            }
             else {
                // No user found with the provided email
                return response()->json(['message' => 'registerFirst'], 200);
            }
        } catch (\Exception $e) {
            // Handle exceptions if they occur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function facebookRedirect(Request $request){
        try {
            $email = $request->input('email');
            $user = User::where('email', $email)->first();   
            
            if ($user) {
                Auth::login($user);
                $token = $user->createToken('auth-token')->plainTextToken;
                return response()->json([ 
                    'message' => 'Success',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => null,
                    'username' => $user->username,
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'contact_number' => $user->contact_number,
                    'shopping_cart' => $user->shoppingCart
                ]);
            }
             else {
                // No user found with the provided email
                return response()->json(['message' => 'registerFirst'], 200);
            }
        } catch (\Exception $e) {
            // Handle exceptions if they occur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getEmailVerificationCode(Request $email){

        $userEmail = $email->input('email');
        // return $userEmail;

        if ($userEmail !== null) {
            $verificationCode = rand(100000, 999999);
        

            
            $notification = new EmailVerificationCodeNotification($verificationCode); 
            \Notification::route('mail', $userEmail)->notify($notification);

            return response()->json([
                'success' =>$verificationCode
            ],200);
        }
        else{
            return response()->json([
                'message' =>'Error in Verification Code, request, cant find email'
            ]);
        }
    }
    public function SMSVerificationCode(Request $contact_number){

        $user_contact_number = $contact_number->input('contact_number');
        // return $user_contact_number;

        if ($user_contact_number !== null) {
            $verificationCode = rand(100000, 999999);
        

            
            $notification = new SMSVerificationCodeNotification($verificationCode); 
            \Notification::route('vonage', $user_contact_number)->notify($notification);

            return response()->json([
                'success' =>$verificationCode
            ],200);
        }
        else{
            return response()->json([
                'message' =>'Error in Verification Code, request, cant find contact number'
            ]);
        }
    }
}