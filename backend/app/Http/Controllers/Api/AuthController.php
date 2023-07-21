<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegisterRequest;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request) {
        try{
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $token = $user->createToken('user_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (Exception $e) {

            return \response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in AuthController.register',
            ]);
        }        
    }

    public function login(LoginRequest $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {

            return response()->json([
                'success' => false,
                'message' => 'Credential does not match',
            ], 200); 
        } else {
            $user = User::where('email', $request->email)->firstOrFail();
            if(Hash::check($request->password, $user->password)) {
                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'token' => $token
                ], 200);
            }    
        }    
    }

    public function logout(Request $request) {
        try{
            $user = User::findOrFail(auth()->id());
            $user->tokens()->delete();

            return response()->json('User logged out!', 200);
        } catch (Exception $e) {
            return \response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in AuthController.logout',
            ]);
        }        
    }
}
