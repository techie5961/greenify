<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UsersPostRequestController extends Controller
{
    // Register
    public function Register(){
        if(DB::table('users')->where('email',request()->input('email'))->count() > 0){
            return response()->json([
                'message' => 'Email address has been taken',
                'status' => 'error'
            ]);
        }
         if(DB::table('users')->where('username',request()->input('username'))->count() > 0){
            return response()->json([
                'message' => 'Username has been taken',
                'status' => 'error'
            ]);
        }
        DB::table('users')->insert([
            'uniqid' => strtoupper(uniqid('USR-')),
            'username' => strtolower(str_replace([' ','-'],'_',request()->input('username'))),
            'email' => request()->input('email'),
            'name' => request()->input('name'),
            'mobile' => request()->input('mobile'),
            'password' => Hash::make(request()->input('password')),
            'status' => 'active',
            'date' => Carbon::now(),
            'updated' => Carbon::now(),
            'photo' => 'avatar.svg'
        ]);
        return response()->json([
            'message' => 'Registration successfull,redirecting to login....',
            'status' => 'success',
            'url' => url('login')
        ]);
    }
    // login
    public function Login(){
        if(DB::table('users')->where('email',request()->input('id'))->orWhere('username',request()->input('id'))->count() == 0){
            return response()->json([
                'message' => 'User not found',
                'status' => 'error'
            ]);
        }
        $select=DB::table('users')->where('username',request()->input('id'))->orWhere('email',request()->input('id'))->first();
        if(Hash::check(request()->input('password'),$select->password)){
            Auth::guard('users')->loginUsingId($select->id);
            return response()->json([
                'message' => 'Login Successfull',
                'status' => 'success',
                'url' => url('users/dashboard')
            ]);
        }
         else{
            return response()->json([
                'message' => 'Invalid password',
                'status' => 'error'
            ]);

         }
    }
}

