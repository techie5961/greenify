 @extends('layout.users.auth')
 @section('title')
     Register
 @endsection
 @section('main')
     <section class="section justify-center column w-full p-10">
     <img style="width:150px;" src="{{ asset('images/logo.png') }}" alt="LOGO">
     <div class="hr"></div>
     <form method="POST" action="{{ url('users/post/register/process') }}" onsubmit="PostRequest(event,this,call_back)" style="margin-top:20px" action="" class="w-full c-primary column g-10">
     <input type="hidden" name="_token" value="{{ csrf_token() }}" class="input">
        <div class="cont required align-center row">
        <svg style="height:50px;width:50px;font-size:calc(100% - 20px);padding:10px" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#64ff0a" viewBox="0 0 256 256"><path d="M224,48H32a8,8,0,0,0-8,8V192a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A8,8,0,0,0,224,48ZM98.71,128,40,181.81V74.19Zm11.84,10.85,12,11.05a8,8,0,0,0,10.82,0l12-11.05,58,53.15H52.57ZM157.29,128,216,74.18V181.82Z"></path></svg>
        <input class="input" name="email" type="email" placeholder="Email">
        @include('components.sections',[
          'required' => true
        ])
        </div>
        
       <div class="cont required row">
        <svg style="height:50px;width:50px;font-size:100%;padding:10px" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#64ff0a" viewBox="0 0 256 256"><path d="M128,24a104,104,0,0,0,0,208c21.51,0,44.1-6.48,60.43-17.33a8,8,0,0,0-8.86-13.33C166,210.38,146.21,216,128,216a88,88,0,1,1,88-88c0,26.45-10.88,32-20,32s-20-5.55-20-32V88a8,8,0,0,0-16,0v4.26a48,48,0,1,0,5.93,65.1c6,12,16.35,18.64,30.07,18.64,22.54,0,36-17.94,36-48A104.11,104.11,0,0,0,128,24Zm0,136a32,32,0,1,1,32-32A32,32,0,0,1,128,160Z"></path></svg>
         <input class="input" name="username" type="text" placeholder="Username">
    @include('components.sections',[
          'required' => true
        ])
       </div>
          
        <div class="cont required row">
            <svg style="height:50px;width:50px;font-size:calc(100% - 20px);padding:10px" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#64ff0a" viewBox="0 0 256 256"><path d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path></svg>
            <input class="input" name="name" type="text" placeholder="Full Name">
                @include('components.sections',[
          'required' => true
        ])
        </div>
       
      <div class="cont required row">
        <svg style="height:50px;width:50px;font-size:calc(100% - 20px);padding:10px" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#64ff0a" viewBox="0 0 256 256"><path d="M231.59,90.13h0C175.44,34,80.56,34,24.41,90.13c-20,20-21.92,49.49-4.69,71.71A16,16,0,0,0,32.35,168a15.8,15.8,0,0,0,5.75-1.08l49-17.37.29-.11a16,16,0,0,0,9.75-11.73l5.9-29.52a76.52,76.52,0,0,1,49.68-.11h0l6.21,29.75a16,16,0,0,0,9.72,11.59l.29.11,49,17.39a16,16,0,0,0,18.38-5.06C253.51,139.62,251.58,110.13,231.59,90.13ZM223.67,152l-.3-.12-48.82-17.33-6.21-29.74A16,16,0,0,0,158,93a92.56,92.56,0,0,0-60.34.13,16,16,0,0,0-10.32,12l-5.9,29.51L32.63,151.86c-.1,0-.17.13-.27.17-12.33-15.91-11-36.23,3.36-50.58,25-25,58.65-37.53,92.28-37.53s67.27,12.51,92.28,37.53C234.61,115.8,236,136.12,223.67,152Zm.32,48a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,200Z"></path></svg>
          <input class="input" name="mobile" type="number" placeholder="Mobile Number">
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
      <button style="font-family:pacifico" class="post pointer clip-10 select-none top-10 br-10"><div class="content">Register</div></button>
     </form>
     <div class="row g10 top-10 bottom-10 c-primary">
       <a style="text-decoration: none;font-weight:900;" href="{{ url('login') }}" class="c-primary select-none">Have an account? Login</a>
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