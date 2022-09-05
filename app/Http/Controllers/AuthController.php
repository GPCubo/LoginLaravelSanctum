<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function register(Request $request){
        $validate = $request->validate([
            'name'=>'required|string|max:100',
            'email'=>['required','string','email','max:100','unique:users'],
            'password'=>'required|string|max:100|min:4|confirmed'
        ]);
        $user = User::create([
            'name'=> $validate['name'],
            'email'=> $validate['email'],
            'password'=>Hash::make($validate['password'])
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token'=> $token,
            // 'type'=>'Bearer'
        ], 200);
    }
    public function login(Request $request){
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'message'=> 'Invalid User details'
            ], 401);
        }
        $user = User::where('email',$request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token'=> $token,
            // 'type'=>'Bearer'
        ], 200);
    }
    public function out (Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['data'=>'TokenDeleted'], 200);
    }
    public function auth(Request $request){
        return Auth::check();
    }
    public function userInfo(Request $request){
        $user = $request->user();
        $data = [
            'email' => $user['email'],
            'name' => $user['name'],
            'created_at'=> $user['created_at'],
            'updated_at'=>$user['updated_at']
        ];
        return response()->json($data, 200);
    }
}
