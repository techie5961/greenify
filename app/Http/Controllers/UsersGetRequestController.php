<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersGetRequestController extends Controller
{
       // flutterwave initiate
    public function FlutterwaveInitiate(){
       
        $ref=strtoupper(uniqid('TRX'));
        $payload=[
            'tx_ref' => $ref,
            'amount' => (float) request()->input('amount'),
            'currency' => 'NGN',
            'payment_options' => 'card,bank transfer',
            'redirect_url' => url()->to('users/deposit/process/flutterwave/{'.$ref.'}'),
            'customer' => [
                'email' => Auth::guard('users')->user()->email,
                'name' => Auth::guard('users')->user()->name
                
            ],
            'customizations' => [
                'title' => 'Deposit Request',
                'description' => 'Payment for Deposit Request'
            ]
        ];
        $response=Http::withToken(env('FLWV_SECRET_KEY'))->post(env('FLWV_BASE_URL').'/payments',$payload)->json();
        if($response['status'] == 'success'){
             DB::table('notifications')->insert([
            'user_id' => Auth::guard('users')->user()->id,
            'message' => json_encode([
                'user' => 'You just initiated a deposit via flutterwave',
                'admin' => '<a class="c-primary b" href="'.url('admins/user?user_id='.Auth::guard('users')->user()->id.'').'">@'.Auth::guard('users')->user()->username.'</a> Just initiated a flutterwave deposit'
            ]),
            'status' => json_encode([
                'user' => 'unread',
                'admin' => 'unread'
            ]),
            'updated' => Carbon::now(),
            'date' => Carbon::now()
        ]);
            return response()->json([
                'message' => 'Payment Initiated success',
                'status' => 'success',
                'url' => $response['data']['link']
            ]);
        }else{
            return response()->json([
                'message' => 'Unable to initiate payment',
                'status' => 'error'
            ]);
        }
    }
    // complete flutterwave payment
    public function FlutterwaveProcess($uniqid){
        $id=request()->input('transaction_id');
        $status=request()->input('status');
        $uniqid=str_replace(['{','}'],'',$uniqid);
        if(DB::table('transactions')->where('uniqid',$uniqid)->where('json->id',$id)->where('status','success')->exists()){
            return redirect()->to('users/dashboard');
        }
        if($status == 'completed' && $id){
            $verify=Http::withToken(env('FLWV_SECRET_KEY'))->get(env('FLWV_BASE_URL')."/transactions/{$id}/verify");
          $json=json_decode($verify);
       //   return $json;
        if($json->status == 'success'){
            DB::table('users')->where('id',Auth::guard('users')->user()->id)->update([
                'deposit' => DB::raw('deposit + '.$json->data->amount.'')
            ]);
            DB::table('transactions')->insert([
                'uniqid' => $uniqid,
                'user_id' => Auth::guard('users')->user()->id,
                'type' => 'deposit',
                'class' => 'credit',
                'amount' => $json->data->amount,
                'json' => json_encode([
                    'gateway' => 'flutterwave',
                    'id' => $id,
                    'fee' => $json->data->app_fee,
                    'total_settle' => $json->data->amount_settled
                ]),
                'status' => 'success',
                'updated' => Carbon::now(),
                'date' => Carbon::now()
                ]);
                 DB::table('notifications')->insert([
            'user_id' => Auth::guard('users')->user()->id,
            'message' => json_encode([
                'user' => 'You completed a deposit',
                'admin' => '<a class="c-primary b" href="'.url('admins/user?user_id='.Auth::guard('users')->user()->id.'').'">@'.Auth::guard('users')->user()->username.'</a> Just completed a deposit'
            ]),
            'status' => json_encode([
                'user' => 'unread',
                'admin' => 'unread'
            ]),
            'updated' => Carbon::now(),
            'date' => Carbon::now()
        ]);
        }
         return redirect()->to('users/dashboard');
        }
    }
    // auto verify
    public function AutoVerify(){
     
    
   
    }
    // withdraw
    public function Withdraw(){
     
        $settings=DB::table('settings')->where('key','finance_settings')->first()->json ?? '{}';
        $settings=json_decode($settings);
          if((float) request()->input('amount') == 0){
         return response()->json([
                'message' => 'Minimum Withdrawal amount is &#8358;'.$settings->min_withdrawal.'',
                'status' => 'error'
            ]);
       }
        if(Auth::guard('users')->user()->withdrawal < (float) request()->input('amount')){
            return response()->json([
                'message' => 'Insufficient balance',
                'status' => 'error'
            ]);
        }
         if((float) request()->input('amount') < $settings->min_withdrawal){
            return response()->json([
                'message' => 'Minimum Withdrawal amount is &#8358;'.$settings->min_withdrawal.'',
                'status' => 'error'
            ]);
         }
        if($settings->withdrawal_status !== 'active'){
            return response()->json([
                'message' => 'Withdrawal is currently closed,please check back later',
                'status' => 'error'
            ]);
        }
        DB::table('users')->where('id',Auth::guard('users')->user()->id)->update([
            'withdrawal' => DB::raw('withdrawal - '.(float) request()->input('amount').''),
            'updated' => Carbon::now()
        ]);
        $amount=request()->input('amount') - (($settings->withdrawal_fee*request()->input('amount'))/100);
        DB::table('transactions')->insert([
            'uniqid' => strtoupper(uniqid('TRX')),
            'user_id' => Auth::guard('users')->user()->id,
            'type' => 'withdrawal',
            'class' => 'debit',
            'amount' => $amount,
            'json' => Auth::guard('users')->user()->json,
            'status' => 'pending',
            'updated' => Carbon::now(),
            'date' => Carbon::now()
        ]);
         DB::table('notifications')->insert([
            'user_id' => Auth::guard('users')->user()->id,
            'message' => json_encode([
                'user' => 'You just submitted a withdrawal request',
                'admin' => '<a class="c-primary b" href="'.url('admins/user?user_id='.Auth::guard('users')->user()->id.'').'">@'.Auth::guard('users')->user()->username.'</a> Just submitted a withdrawal request'
            ]),
            'status' => json_encode([
                'user' => 'unread',
                'admin' => 'unread'
            ]),
            'updated' => Carbon::now(),
            'date' => Carbon::now()
        ]);
        return response()->json([
            'message' => 'Withdrawal placed successfully',
            'status' => 'success',
            'url' => url('users/transactions')
        ]);
    }
    // purchase product
    public function PurchaseProduct(){
        return view('components.sections',[
            'purchase_product' => true,
            'product' => DB::table('products')->where('id',request()->input('id'))->first()
        ]);
    }
    // purchase product confirm
    public function PurchaseProductConfirm(){
        $product=DB::table('products')->where('id',request()->input('id'))->first();
        if($product->price > Auth::guard('users')->user()->deposit){
            return response()->json([
                'message' => 'Insufficient balance to purchase this product,deposit funds to purchase',
                'status' => 'error'
            ]);
        }
        DB::table('users')->where('id',Auth::guard('users')->user()->id)->update([
            'deposit' => DB::raw('deposit - '.$product->price.'')
        ]);
        DB::table('transactions')->insert([
            'uniqid' => strtoupper(uniqid('TRX')),
            'user_id' => Auth::guard('users')->user()->id,
            'type' => 'purchase',
            'class' => 'debit',
            'amount' => $product->price,
            'status' => 'success',
            'updated' => Carbon::now(),
            'date' => Carbon::now(),
            'json' => json_encode($product)
        ]);
        DB::table('purchased')->insert([
            'product_id' => $product->id,
            'user_id' => Auth::guard('users')->user()->id,
            'json' => json_encode($product),
            'status' => 'active',
            'updated' => Carbon::now(),
            'date' => Carbon::now()
        ]);
        $referral_settings=json_decode(DB::table('settings')->where('key','referral_settings')->first()->json);
       
        if($referral_settings->method == 'first'){
            if(DB::table('purchased')->where('user_id',Auth::guard('users')->user()->id)->count() > 0){
            
                return response()->json([
            'message' => 'Product purchased successfully',
            'status' => 'success',
            'url' => url('users/products/purchased')
        ]); 
            }
        }
            if(trim(Auth::guard('users')->user()->ref) !== ''){
                $ref=Auth::guard('users')->user()->ref;
                $first_level=($referral_settings->first_level*$product->price)/100;
              
                DB::table('users')->where('username',$ref)->update([
                    'withdrawal' => DB::raw('withdrawal + '.$first_level.'')
                ]);
                 DB::table('transactions')->insert([
            'uniqid' => strtoupper(uniqid('TRX')),
            'user_id' => DB::table('users')->where('username',$ref)->first()->id,
            'type' => 'commission',
            'class' => 'credit',
            'amount' => $first_level,
            'status' => 'success',
            'updated' => Carbon::now(),
            'date' => Carbon::now(),
            'json' => json_encode([
                'user_id' => Auth::guard('users')->user()->id,
                'level' => 'first'
            ])
        ]);
        $referral=DB::table('users')->where('username',$ref)->first();
        $second=$referral->ref ?? '';
       if(trim($second) !== ''){
        $second_level=($referral_settings->second_level * $product->price)/100;
           DB::table('users')->where('username',$second)->update([
                    'withdrawal' => DB::raw('withdrawal + '.$second_level.'')
                ]);
                 DB::table('transactions')->insert([
            'uniqid' => strtoupper(uniqid('TRX')),
            'user_id' => DB::table('users')->where('username',$second)->first()->id,
            'type' => 'commission',
            'class' => 'credit',
            'amount' => $second_level,
            'status' => 'success',
            'updated' => Carbon::now(),
            'date' => Carbon::now(),
            'json' => json_encode([
                'user_id' => Auth::guard('users')->user()->id,
                'level' => 'second'
            ])
             ]);
             
       }

            }
        DB::table('notifications')->insert([
            'user_id' => Auth::guard('users')->user()->id,
            'message' => json_encode([
                'user' => 'You just purchased a product',
                'admin' => '<a class="c-primary b" href="'.url('admins/user?user_id='.Auth::guard('users')->user()->id.'').'">@'.Auth::guard('users')->user()->username.'</a> Just purchased a product'
            ]),
            'status' => json_encode([
                'user' => 'unread',
                'admin' => 'unread'
            ]),
            'updated' => Carbon::now(),
            'date' => Carbon::now()
        ]);
        return response()->json([
            'message' => 'Product purchased successfully',
            'status' => 'success',
            'url' => url('users/products/purchased')
        ]);
    }
}
