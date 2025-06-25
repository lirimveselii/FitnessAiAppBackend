<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Mail\VerifyEmail;
use App\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\WorkoutController;



class AuthController extends Controller
{
    public $workoutController;

    public function __construct(){

        // $this->workoutController = new WorkoutController;

    }

    public function register(Request $request)
    {
        try {
            

            $validator = Validator::make($request->all(), [
                    "name" => "required",
                    "email" => "required|email|unique:users,email",
                    "password" => "required",
                    "confirm_password" => "required|same:password",
                    "age" => "required|integer|min:13",
                    "gender" => "required|in:male,female,other",
                    "height_cm" => "required|numeric",
                    "weight_kg" => "required|numeric",
                    "user_type" => "required",
                ]);
    
            if ($validator->fails()) {
                Log::warning("Validation failed during registration: " . implode(", ", $validator->errors()->all()));
                return response()->json([
                    "status" => 422,
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
                "user_type" => $request->user_type,
            ]);    
            
            
            Mail::to($request->email)->send(new VerifyEmail($user));

            Log::info("User registered successfully: " . $user->email , );
    
            return response()->json([
                "status" => 1,
                "message" => "User registered successfully. Please verify your email.",
            ]);
    
        } catch (Exception $e) {
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

     public function login(Request $request ) {

        try {
            $credentials = $request->only('email', 'password');
    
            if (!Auth::attempt($credentials)) {
                Log::info('Failed login attempt for email: ' . $credentials['email']);
    
                return response()->json([
                    "status" => 0,
                    "message" => "Incorrect email or password.",
                    "data" => null
                ], 401);
            }
    
            $user = Auth::user();
            $token = $user->createToken("MyApp")->plainTextToken;
    
            return response()->json([
                "status"     => 1,
                "message"    => "Login successful.",
                "token"      => $token,
                "name"       => $user->name,
                "email"      => $user->email,
                "age"        => $user->age,
                "gender"     => $user->gender,
                "height_cm"  => $user->height_cm,
                "weight_kg"  => $user->weight_kg,
                "user_type"  => $user->user_type
            ]);
        } catch (\Throwable $th) {
            Log::error('Login exception: ' . $th->getMessage());
    
            return response()->json([
                "status"  => 0,
                "message" => "Authentication error occurred.",
                "data"    => null
            ], 500);
        }
    
     }

     public function logout(Request $request)
     {
         Auth::logout();

        return response()->json(["message"=> "the userr have loged out succesfuly "]);
     }

     public function verifyEmail(Request $request, $id)
     {
        try {
            $now = now();
            $user = User::findOrFail($id);
    
            if (!$user->hasVerifiedEmail()) {
                $user->email_verified_at = $now;
                $user->save();
    
                Log::info("User with ID {$user->id} verified their email at {$now}.");
            } else {
                Log::info("User with ID {$user->id} attempted to verify an already verified email.");
            }  
            Auth::login($user); 
    
            return response()->json([
                'message' => 'Email verified successfully.',
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken 
            ], 200);
    
        }  catch (\Exception $e) {
             Log::error("Email verification failed for user ID {$id}. Error: " . $e->getMessage());
     
             return response()->json([
                 'message' => 'Something went wrong during email verification.',
                 'error' => $e->getMessage()
             ], 500);
         }
     }

     public function forgetPassword(Request $request){

         $request->validate(['email' => 'required|email|exists:users,email']);
         $token = Str::random(60);
         $email =$request->email;
         $user = User::where('email', $email)->firstOrFail();
         
        //  dd("teete");

         DB::table('password_reset_tokens')->updateOrInsert(
            ['email' =>  $email],
            [
                'token' => bcrypt($token), // optional: can be raw if you don't hash
                'created_at' => Carbon::now()
            ]
        );
       
        Mail::to( $email)->send(new ForgotPasswordEmail($user ,$token, $email));    

     }

     public function  resetPasswordView( Request $request){

       $token = $request->query('token');
       $email = $request->query('email');
        return view('reset-password-view')->with([
            'reset_token' => $token,
            'user_email' => $email,
        ]);


     }

     public function resetPassword(Request $request){

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);
    
        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetRecord || !Hash::check($token, $resetRecord->token)) {
            return response()->json(['message' => 'Invalid or expired token.'], 400);
        }

            // Update the user's password
        $user = User::where('email', $email)->firstOrFail();
        $user->password = Hash::make($password);
        $user->save();

        // Delete the token after use
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json(['message' => 'Password reset successful.']);



     }

}
