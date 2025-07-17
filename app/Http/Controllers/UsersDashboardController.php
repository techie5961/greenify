<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersDashboardController extends Controller
{
    // dashboard
    public function Dashboard(){
        $trx=DB::table('transactions')->where('user_id',Auth::guard('users')->user()->id)->orderBy('date','desc')->limit(2)->get();
        $trx->transform(function($each){
            $each->frame=Carbon::parse($each->date)->diffForHumans();
            return $each;
        });
        return view('users.dashboard',[
            'trx' => $trx,
            'link' => json_decode(DB::table('settings')->where('key','general_settings')->first()->json ?? '{}')->group_link ?? ''
        ]);
    } 
    // deposit
    public function Deposit(){
        return view('users.deposit');
    }
       // withdraw
    public function Withdraw(){
        $json=Auth::guard('users')->user()->json ?? '{}';
        $bank=json_decode($json);
        if(empty($bank->account_number) || empty($bank->bank_key) || empty($bank->account_name)){
            return redirect()->to('users/bank');
        }
        return view('users.withdraw');
    }
//    bank
    public function Bank(){
        $json=Auth::guard('users')->user()->json ?? '{}';
        return view('users.bank',[
            'bank' => json_decode($json)
        ]);
    }
    // transactions
    public function Transactions(){
        $trx=DB::table('transactions')->where('user_id',Auth::guard('users')->user()->id)->orderBy('date','desc')->paginate(100);
      $trx->getCollection()->transform(function($each){
        $each->frame=Carbon::parse($each->date)->diffForHumans();
        return $each;
      });
        return view('users.transactions',[
            'trx' => $trx
        ]);
    }

    // profile
    public function Profile(){
        return view('users.profile',[
            'reg_date' => Carbon::parse(Auth::guard('users')->user()->date)->format('d M Y, H:i:s')
        ]);
    }
    // logout
    public function Logout(){
        Auth::guard('users')->logout();
        return redirect()->to('login');
    }
    // invite
    public function Invite(){
        return view('users.invite',[
            'referral_settings' => json_decode(DB::table('settings')->where('key','referral_settings')->first()->json)
        ]);
    }
    // products
    public function Products(){
        $products=DB::table('products')->orderBy('price','asc')->get();
       $products=$products->filter(function($each){
        $purchased=DB::table('purchased')->where('product_id',$each->id)->where('user_id',Auth::guard('users')->user()->id)->count();
        return $each->limit > $purchased;
       });
        return view('users.products.index',[
            'products' => $products
        ]);
    }
    // purchased products
    public function PurchasedProducts(){
        $purchased=DB::table('purchased')->where('user_id',Auth::guard('users')->user()->id)->where('status','active')->paginate(100);
        $purchased->getCollection()->transform(function($each){
            $each->json=json_decode($each->json);
            $each->expires=Carbon::parse($each->date)->addDays($each->json->validity)->format('D M d,Y H:i:s');
            $each->next_reward=Carbon::parse($each->updated)->addDay()->format('D M d,Y H:i:s');
            return $each;
        });
        return view('users.products.purchased',[
            'purchased' => $purchased
        ]);
    }
    // referrals
    public function Referrals(){
        $username=Auth::guard('users')->user()->username;
       $referrals=DB::table('users')->where('ref',Auth::guard('users')->user()->username)->orWhereIn('ref',function($q) use($username){
        $q->select('username')->from('users')->where('ref',$username);
       })->orderBy('date','desc')->paginate(100);
       $referrals->getCollection()->transform(function($each){
        $each->frame=Carbon::parse($each->date)->diffForHumans();
        $each->total_purchase=DB::table('transactions')->where('type','purchase')->where('user_id',$each->id)->sum('amount');
        $each->total_commission=DB::table('transactions')->where('type','commission')->where('json->user_id',$each->id)->sum('amount');
        return $each;
       });
        return view('users.referrals',[
           'referrals' => $referrals
        ]);
    }
}
