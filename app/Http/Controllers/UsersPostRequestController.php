<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
            'withdrawal' => json_decode(DB::table('settings')->where('key','general_settings')->first()->json ?? '{}')->signup_bonus ?? 0,
            'status' => 'active',
            'date' => Carbon::now(),
            'updated' => Carbon::now(),
            'photo' => 'avatar.svg',
            'ref' => request()->input('ref')
        ]);
        DB::table('notifications')->insert([
            'user_id' => DB::table('users')->where('email',request()->input('email'))->first()->id,
            'message' => json_encode([
                'user' => 'Registration Success',
                'admin' => '<a class="b c-primary" href="'.url('admins/user?id='.DB::table('users')->where('email',request()->input('email'))->first()->id.'').'">@'.strtolower(str_replace([' ','-'],'_',request()->input('username'))).'</a> Just Registerd on the site'
            ]),
            'status' => json_encode([
                'user' => 'read',
                'admin' => 'unread'
            ]),
            'updated' => Carbon::now(),
            'date' => Carbon::now()
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
            DB::table('logs')->insert([
                'user_id' => Auth::guard('users')->user()->id,
                'ip' => request()->ip(),
                'date' => Carbon::now()
            ]);
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
    // add bank
    public function AddBank(){
        $account_number=DB::getPdo()->quote(request()->input('account_number'));
        $account_name=DB::getPdo()->quote(request()->input('account_name'));
        $bank_key=DB::getPdo()->quote(request()->input('bank_key'));
        DB::table('users')->where('id',Auth::guard('users')->user()->id)->update([
            'json' => DB::raw("JSON_SET(COALESCE(json,'{}'),'$.account_number',$account_number,'$.account_name',$account_name,'$.bank_key',$bank_key)"),
            'updated' => Carbon::now()
        ]);
         DB::table('notifications')->insert([
            'user_id' => Auth::guard('users')->user()->id,
            'message' => json_encode([
                'user' => 'You just linked a bank account',
                'admin' => '<a class="c-primary b" href="'.url('admins/user?id='.Auth::guard('users')->user()->id.'').'">@'.Auth::guard('users')->user()->username.'</a> Just linked a bank account'
            ]),
            'status' => json_encode([
                'user' => 'unread',
                'admin' => 'unread'
            ]),
            'updated' => Carbon::now(),
            'date' => Carbon::now()
        ]);
        return response()->json([
            'message' => 'Account details updated success',
            'status' => 'success'
        ]);
    }
    // update photo
    public function UpdatePhoto(){
        $name=time().request()->file('photo')->getClientOriginalExtension();
        if(request()->file('photo')->move(public_path('images/users'),$name)){
            DB::table('users')->where('id',Auth::guard('users')->user()->id)->update([
                'photo' => $name,
                'updated' => Carbon::now()
            ]);
              DB::table('notifications')->insert([
            'user_id' => Auth::guard('users')->user()->id,
            'message' => json_encode([
                'user' => 'You just updated your photo',
                'admin' => '<a class="c-primary b" href="'.url('admins/user?user_id='.Auth::guard('users')->user()->id.'').'">@'.Auth::guard('users')->user()->username.'</a> Just updated his/her photo'
            ]),
            'status' => json_encode([
                'user' => 'unread',
                'admin' => 'unread'
            ]),
            'updated' => Carbon::now(),
            'date' => Carbon::now()
        ]);
            return response()->json([
                'message' => 'Profile photo updated successfully',
                'status' => 'success',
                'photo' => asset('images/users/'.$name.'')
            ]);
        }
    }
    // password update
    public function PasswordUpdate(){
        if(!Hash::check(request()->input('current'),Auth::guard('users')->user()->password)){
            return response()->json([
                'message' => 'Invalid current password',
                'status' => 'error'
            ]);
        }
        if(!Hash::check(request()->input('confirm'),Hash::make(request()->input('new')))){
            return response()->json([
                'message' => 'New password and Confirm password must match',
                'status' => 'error'
            ]);
        }
        DB::table('users')->where('id',Auth::guard('users')->user()->id)->update([
            'password' => Hash::make(request()->input('new')),
            'updated' => Carbon::now()
        ]);
         DB::table('notifications')->insert([
            'user_id' => Auth::guard('users')->user()->id,
            'message' => json_encode([
                'user' => 'You just updated your account password',
                'admin' => '<a class="c-primary b" href="'.url('admins/user?user_id='.Auth::guard('users')->user()->id.'').'">@'.Auth::guard('users')->user()->username.'</a> Just updated his/her account password'
            ]),
            'status' => json_encode([
                'user' => 'unread',
                'admin' => 'unread'
            ]),
            'updated' => Carbon::now(),
            'date' => Carbon::now()
        ]);
        return response()->json([
            'message' => 'Account password updated successfully',
            'status' => 'success',
            'url' => url()->to('users/profile')
        ]);
    }
 
}

