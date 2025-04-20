<?php

namespace App\Http\Controllers\API;

use Auth;
use App\Models\User;

use App\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Check if the user is already logged in
        $isLogdIn = Auth::check();
        Log::info("User logged in status during registration: " . ($isLogdIn ? 'true' : 'false'));
    
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                    "name" => "required",
                    "email" => "required|email|unique:users,email",
                    "password" => "required",
                    "confirm_password" => "required|same:password",
                    "age" => "required|integer|min:13",
                    "gender" => "required|in:male,female,other",
                    "height_cm" => "required|numeric",
                    "weight_kg" => "required|numeric",
                    "user_type" => "required"
                ]);
    
            if ($validator->fails()) {
                // Validation failed, return the error messages
                Log::warning("Validation failed during registration: " . implode(", ", $validator->errors()->all()));
                return response()->json([
                    "status" => 0,
                    "message" => "Validation errors.",
                    "data" => $validator->errors()->all()
                ]);
            }
    
            // Create the user in the database
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "age" => $request->age,
                "gender" => $request->gender,
                "height_cm" => $request->height_cm,
                "weight_kg" => $request->weight_kg,
                "user_type" => $request->user_type,
            ]);

            event(new Registered($user));
            
                // Log the user registration success
            Log::info("User registered successfully: " . $user->email);
    
            return response()->json([
                "status" => 1,
                "message" => "User registered successfully. Please verify your email.",
            ]);
    
        } catch (Exception $e) {
            // Catch any unexpected errors
            Log::error("Error during registration: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
    
            return response()->json([
                "status" => 0,
                "message" => "An error occurred during registration. Please try again later.",
                "data" => null
            ]);
        }
    }

     public function login(Request $request) {

        try {
            // 1) Inspect incoming credentials
            // dd('Incoming credentials:', $request->only('email', 'password'));
    
            $credentials = $request->only('email', 'password');
    
            // 2) Attempt to authenticate
          
            if (!Auth::attempt($credentials)) {
                // Authentication failed
                dd('Auth::attempt returned false for:', $credentials);
            }
    
            // 3) Auth::attempt succeeded, user is now logged in
            $user = Auth::user();
    
            // 4) (This code won’t run until you remove the dd above)
            $token = $user->createToken("MyApp")->plainTextToken;

            // dd($token);
    
            return response()->json([
                'token'      => $token,
                'name'       => $user->name,
                'email'      => $user->email,
                'age'        => $user->age,
                'gender'     => $user->gender,
                'height_cm'  => $user->height_cm,
                'weight_kg'  => $user->weight_kg,
            ]);
        }
        catch (\Throwable $th) {
            // 5) Catch any unexpected errors
            dd('Exception thrown:', $th->getMessage(), $th->getTrace());
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

        return response()->json(["message"=> "the userr have loged out succesfuly "]);
     }

     public function index() {
        return view("admin.dashboard");

     }

     public function testIfLogdIn(){

         $islogdin = Auth::check();

         dd($islogdin);
     }
}
