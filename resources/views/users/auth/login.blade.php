 @extends('layout.users.auth')
 @section('title')
     Login
 @endsection 
 @section('main')
     <section class="section justify-center column w-full p-10">
     <img style="width:150px;" src="{{ asset('images/logo.png') }}" alt="LOGO">
     <div class="hr"></div>
     <form method="POST" action="{{ url('users/post/login/process') }}" onsubmit="PostRequest(event,this,call_back)" style="margin-top:20px" action="" class="w-full c-primary column g-10">
     <input type="hidden" name="_token" value="{{ csrf_token() }}" class="input">
       
      
          
        <div class="cont required row">
            <svg style="height:50px;width:50px;font-size:calc(100% - 20px);padding:10px" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#64ff0a" viewBox="0 0 256 256"><path d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path></svg>
            <input class="input" name="id" type="text" placeholder="Username or Email Address">
                @include('components.sections',[
          'required' => true
        ])
        </div>
       
      
        <div class="cont required row">
            <svg style="height:50px;width:50px;font-size:calc(100% - 20px);padding:10px" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#64ff0a" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm40-104a40,40,0,1,0-65.94,30.44L88.68,172.77A8,8,0,0,0,96,184h64a8,8,0,0,0,7.32-11.23l-13.38-30.33A40.14,40.14,0,0,0,168,112ZM136.68,143l11,25.05H108.27l11-25.05A8,8,0,0,0,116,132.79a24,24,0,1,1,24,0A8,8,0,0,0,136.68,143Z"></path></svg>
            <input class="input" name="password" type="password" placeholder="Password">
                @include('components.sections',[
          'required' => true
        ])
        </div>
      <button style="font-family:pacifico" class="post pointer clip-10 select-none top-10 br-10"><div class="content">Login Safely</div></button>
     </form>
     <div class="row g10 top-10 bottom-10 c-primary space-between w-full">
       <a style="text-decoration: none;font-weight:900;" href="{{ url('register') }}" class="c-primary select-none">Create Account</a>
    <a style="text-decoration: none;font-weight:900;" href="{{ url('forgot') }}" class="c-primary select-none">Forgot Password?</a>
       
    </div>
     </section>
 @endsection
 @section('js')
     <script class="js">
      window.MyFunc=function(){
        window.call_back=function(response){
          if(JSON.parse(response).status == 'success'){
            window.location.href=JSON.parse(response).url;
          }
        }
      }
      MyFunc();
     </script>
 @endsection