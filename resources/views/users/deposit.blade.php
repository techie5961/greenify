@extends('layout.users.app') 
@section('title')
    Deposit
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
        <div class="bg-dim br-10 p-10 top-20 w-full max-w-500 x-auto column g-5">
            <div class="cont">
             <b style="padding:10px 20px;background:transparent;border-right:0.1px solid rgba(255,255,255,0.1)" class="row h-full justify-center p-10">&#x20a6;</b>
                <input oninput="MyFunc.AutoDetect(this)" placeholder="0.00" type="number" name="amount" class="inp amount input">
            </div>
        </div>
        <div class="grid-3 grid w-full max-w-500 x-auto no-select g-5">
            <div onclick="AutoFill(1000,document.querySelector('input[name=amount]'),MyFunc.call_back)" class="bg-dim p-10 w-100 column justify-center br-10 pointer clip-10 text-bold">
               &#8358; 1,000
            </div>
               <div  onclick="AutoFill(2000,document.querySelector('input[name=amount]'),MyFunc.call_back)" class="bg-dim p-10 w-100 column justify-center pointer clip-10 br-10 text-bold">
               &#8358; 2,000
            </div>
               <div onclick="AutoFill(5000,document.querySelector('input[name=amount]'),MyFunc.call_back)" class="bg-dim p-10 w-100 column justify-center pointer clip-10 br-10 text-bold">
               &#8358; 5,000
            </div>
               <div onclick="AutoFill(10000,document.querySelector('input[name=amount]'),MyFunc.call_back)" class="bg-dim p-10 w-100 column justify-center pointer clip-10 br-10 text-bold">
               &#8358; 10,000
            </div>
               <div onclick="AutoFill(20000,document.querySelector('input[name=amount]'),MyFunc.call_back)" class="bg-dim p-10 w-100 column justify-center pointer clip-10 br-10 text-bold">
               &#8358; 20,000
            </div>
               <div onclick="AutoFill(50000,document.querySelector('input[name=amount]'),MyFunc.call_back)" class="bg-dim p-10 w-100 column justify-center pointer clip-10 br-10 text-bold">
               &#8358; 50,000
            </div>
               <div onclick="AutoFill(75000,document.querySelector('input[name=amount]'),MyFunc.call_back)" class="bg-dim p-10 w-100 column justify-center pointer clip-10 br-10 text-bold">
               &#8358; 75,000
            </div>
               <div onclick="AutoFill(100000,document.querySelector('input[name=amount]'),MyFunc.call_back)" class="bg-dim p-10 w-100 column justify-center pointer clip-10 br-10 text-bold">
               &#8358; 100,000
            </div>
              <div onclick="AutoFill(200000,document.querySelector('input[name=amount]'),MyFunc.call_back)" class="bg-dim p-10 w-100 column justify-center pointer clip-10 br-10 text-bold">
               &#8358; 200,000
            </div>
        </div>
       </div>
       <button onclick="MyFunc.Initiate(this)"class="btn x-auto disabled" style="width:calc(100% - 20px);margin:10px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" viewBox="0 0 256 256"><path d="M208,80H176V56a48,48,0,0,0-96,0V80H48A16,16,0,0,0,32,96V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V96A16,16,0,0,0,208,80Zm-80,84a12,12,0,1,1,12-12A12,12,0,0,1,128,164Zm32-84H96V56a32,32,0,0,1,64,0Z"></path></svg>

       Proceed</button> 

    
    

    </section>
@endsection
@section('js')
    <script class="js">
        window.MyFunc={
            AutoFocus : function(){
                document.querySelector('input[name=amount]').focus();
            },
            AutoDetect : function(input){
               try{
             
                 if(input.value !== ''){
                    document.querySelector('.btn').classList.remove('disabled');
                } else{
                    document.querySelector('.btn').classList.add('disabled');
                }
               } catch(error){
                alert(error.stack);
               }
            },
            call_back : function(amount,input){
                  if(input.value !== ''){
                    document.querySelector('.btn').classList.remove('disabled');
                } else{
                    document.querySelector('.btn').classList.add('disabled');
                }
            },
            Initiate : function(){
                let amount=document.querySelector('input[name=amount]').value.trim();
                GetRequest(event,`{{ url()->to('users/deposit/initiate/flutterwave') }}?amount=${amount}`,MyFunc.initiated)
            }
            ,
            initiated : function(response){
              let data=JSON.parse(response);
              if(data.status == 'success'){
                
                window.location.href= data.url;
              }else{
               CreateNotify(data.status,data.message);
              }
            }

        }
        MyFunc.AutoFocus();
    </script>
@endsection