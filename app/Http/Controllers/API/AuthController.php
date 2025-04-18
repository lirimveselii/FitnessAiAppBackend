<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     "name" => "required",
        //     "email" => "required|email|unique:users,email",
        //     "password" => "required",
        //     "confirm_password" => "required|same:password",
        //     "age" => "required|integer|min:13",
        //     "gender" => "required|in:male,female,other",
        //     "height_cm" => "required|numeric",
        //     "weight_kg" => "required|numeric",
        //     "user_type" => "required|in:admin,user"  
        // ]);

        dd("test");

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "message" => "Validation errors.",
                "data" => $validator->errors()->all()
            ]);
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "age" => $request->age,
            "gender" => $request->gender,
            "height_cm" => $request->height_cm,
            "weight_kg" => $request->weight_kg,
            'user_type' => $request->user_roles,
        ]);
        event(new Registered($user));

        $response = [];
        $response["token"] = $user->createToken("MyApp")->plainTextToken;
        $response["name"] = $user->name;
        $response["email"] = $user->email;
        $response["age"] = $user->age;
        $response["gender"] = $user->gender;
        $response["height_cm"] = $user->height_cm;
        $response["weight_kg"] = $user->weight_kg;
        $response["user_type"] = $user->user_type;

        return response()->json([
            "status" => 1,
            "message" => "User registered successfully. Please verify your email",
            "data" => $response
        ]);

    
    }
     public function login(Request $request) {

        if (Auth::attempt(["email"=> $request ->email , "password" => $request ->password ])) {
            
            $user = Auth::user();
            $response = [];
            $response["token"] = $user->createToken("MyApp")->plainTextToken;
            $response["name"] = $user->name;
            $response["email"] = $user->email;
            $response["age"] = $user->age;
            $response["gender"] = $user->gender;
            $response["height_cm"] = $user->height_cm;
            $response["weight_kg"] = $user->weight_kg;

    
             return response()->json([
                "status" => 1,
                "message" => "User registered .",
                "data" => $response
            ]);
        }
        return response()->json([
            "status" => 0,
                "message" => "Authetication  error   .",
            "data" =>  null
        ]);
     }
     public function logout(Request $request)
     {
         Auth::logout();
         $request->session()->invalidate();
         $request->session()->regenerateToken();
         return redirect('/');
     }

     public function index() {
        return view("admin.dashboard");

     }
}
