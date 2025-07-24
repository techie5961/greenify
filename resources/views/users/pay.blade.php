@extends('layout.users.app') 
@section('title')
   Deposit | Pay
@endsection
@section('css')
    <style>
        body,main{
            overflow:hidden;
            
        }
    
    </style>
@endsection
@section('main')
    <section id="x" class="pos-fixed column align-center bg average">
       <div class="p-10 row pos-stick stick-top space-between bg w-full align-center">
        <svg class="pc-pointer" onclick="spa(event,'{{ url()->previous() == request()->fullUrl() ? url()->to('users/dashboard') : url()->previous() }}')" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 256 256"><path d="M228,128a12,12,0,0,1-12,12H69l51.52,51.51a12,12,0,0,1-17,17l-72-72a12,12,0,0,1,0-17l72-72a12,12,0,0,1,17,17L69,116H216A12,12,0,0,1,228,128Z"></path></svg>
        <b>Deposit</b>
         <svg onclick="spa(event,'{{ url()->to('users/dashboard') }}')" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 256 256"><path d="M224,120v96a8,8,0,0,1-8,8H40a8,8,0,0,1-8-8V120a15.87,15.87,0,0,1,4.69-11.32l80-80a16,16,0,0,1,22.62,0l80,80A15.87,15.87,0,0,1,224,120Z"></path></svg>

       </div>
       <div class="column flex-auto w-full overflow-x-auto p-10 g-5">
       <div style="padding:20px 10px" class="w-full align-center max-500 p-10 g-10 column bg-dim clip-10">
        <strong class="desc c-primary u">Account Details</strong>
       <div class="row w-full space-between">
            <span class="text-light">Amount:</span>
            <strong class="c-primary">&#8358;{{ number_format($amount,2) }}</strong>
        </div>
        <div class="row w-full space-between">
            <span class="text-light">Account Number:</span>
           <div class="row g-5 align-center">
             <strong>{{ $bank->account_number }}</strong>
            <div onclick="copy('{{ $bank->account_number }}')" class="bg-primary pc-pointer pos-sticky h-full perfect-square column align-center justify-center c-black">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M216,32H88a8,8,0,0,0-8,8V80H40a8,8,0,0,0-8,8V216a8,8,0,0,0,8,8H168a8,8,0,0,0,8-8V176h40a8,8,0,0,0,8-8V40A8,8,0,0,0,216,32Zm-8,128H176V88a8,8,0,0,0-8-8H96V48H208Z"></path></svg>
       </div>
           </div>
        </div>
            <div class="row w-full space-between">
            <span class="text-light">Bank:</span>
            <strong>{{ $bank->bank }}</strong>
        </div>
          <div class="row w-full space-between">
            <span class="text-light">Account Name:</span>
            <strong>{{ $bank->account_name }}</strong>
        </div>
        <span class="text-light text-dim top-10 bottom-10 text-center">
            Send the exact amount into the account details above and click the button below
        </span>
       </div>
     
       </div>
       <button onclick="SlideUp()"class="btn x-auto" style="width:calc(100% - 20px);margin:10px;">
      I Have Paid</button> 

    
    

    </section>
@endsection
@section('slideup_child')
    <form class="column w-full g-10"  method="POST" onsubmit="PostRequest(event,this,MyFunc.Submitted)" action="{{ url('users/post/complete/deposit/process') }}">
       <input type="hidden" name="amount" value="{{ $amount }}" class="input">
          <input type="hidden" name="_token" class="input" value="{{ csrf_token() }}">
        <strong class="c-primary desc">Complete Deposit</strong>
        <label for="">Name of Bank used in transfer</label>
        <div class="cont required">
            <input type="text" name="account_name" placeholder="E.g First Bank PLC" class="inp input">
            @include('components.sections',[
                'required' => true
            ])
        </div>
        <label for="">Name on Bank used in transfer</label>
        <div class="cont required">
            <input type="text" name="bank_name" placeholder="E.g {{ ucwords(Auth::guard('users')->user()->name) }}" class="inp input">
            @include('components.sections',[
                'required' => true
            ])
        </div>
        <button class="post">Submit Deposit</button>
    </form>
@endsection
@section('js')
  <script class="js">
    MyFunc = {
        Paid : function(element){
            document.querySelector('.bg-whitesmoke.details').classList.add('display-none');
            document.querySelector('.bg-whitesmoke.complete').classList.remove('display-none');
            element.onclick=function(){
                MyFunc.Back(element)
            }
            element.innerHTML='Show Deposit details';
        },
        Back : function(element){
             document.querySelector('.bg-whitesmoke.details').classList.remove('display-none');
            document.querySelector('.bg-whitesmoke.complete').classList.add('display-none');
            element.onclick=function(){
                MyFunc.Paid(element)
            }
             element.innerHTML='I have made the transfer';
        },
        Submitted : function(response,event){
            let data=JSON.parse(response);
            if(data.status == 'success'){
                window.location.href=data.url;
            }
        }
    }
  </script>
@endsection