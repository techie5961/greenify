@extends('layout.users.app')
@section('title')
    Invite and Earn
@endsection
@section('css')
    <style>
        .banner{
            height:200px;
            background-image:url('{{ asset('images/gift.svg') }}');
            background-size:cover;
            background-position:center;
            position:relative;
           
        }
        .banner::before{
            content:'';
            position:absolute;
            top:0;
            bottom:0;
            left:0;
            right:0;
            background:rgba(0,0,0,0.5);
            z-index:10;
            
        }
        body{
            overflow:hidden;
        }
        @media(min-width:800px){
            .body{
                padding:10px 10vw;
            }
            
        }
    </style>
@endsection
@section('main')
    <section id="x" class="pos-fixed column overflow-y-auto align-center bg average">
       <div class="p-10 high row pos-stick stick-top space-between bg w-full align-center">
        <svg class="pc-pointer" onclick="spa(event,'{{ url()->previous() == request()->fullUrl() ? url()->to('users/dashboard') : url()->previous() }}')" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 256 256"><path d="M228,128a12,12,0,0,1-12,12H69l51.52,51.51a12,12,0,0,1-17,17l-72-72a12,12,0,0,1,0-17l72-72a12,12,0,0,1,17,17L69,116H216A12,12,0,0,1,228,128Z"></path></svg>
        <b>Invite and Earn</b>
         <svg onclick="spa(event,'{{ url()->to('users/dashboard') }}')" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 256 256"><path d="M224,120v96a8,8,0,0,1-8,8H40a8,8,0,0,1-8-8V120a15.87,15.87,0,0,1,4.69-11.32l80-80a16,16,0,0,1,22.62,0l80,80A15.87,15.87,0,0,1,224,120Z"></path></svg>

       </div>
       <div class="column align-center body flex-auto w-full text-center no-select overflow-y-auto p-10 g-5">
       <section class="banner max-w-500 c-gold w-full">
        <div class="pos-absolute absolute-full low justify-center column">
            <strong class="desc" style="font-family:'cinzel decorative">Invite and Earn Amazing Rewards</strong>
        </div>
       </section>
       <div class="h-50 bg-dim br-10 overflow-hidden row align-center w-full max-w-500">
        <div style="width:calc(100% - 50px)" class="row align-center p-10 flex-auto ws-nowrap overflow-x-auto h-full ">{{ url()->to('register?ref='.Auth::guard('users')->user()->username.'') }}</div>
       <div onclick="copy('{{ url()->to('register?ref='.Auth::guard('users')->user()->username.'') }}')" class="bg-primary pc-pointer pos-sticky h-full perfect-square column align-center justify-center c-black">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M216,32H88a8,8,0,0,0-8,8V80H40a8,8,0,0,0-8,8V216a8,8,0,0,0,8,8H168a8,8,0,0,0,8-8V176h40a8,8,0,0,0,8-8V40A8,8,0,0,0,216,32Zm-8,128H176V88a8,8,0,0,0-8-8H96V48H208Z"></path></svg>
       </div>
    </div>
    
    <div class="column pc-grid pc-grid-2 space-between g-10 align-center w-full max-w-500">
        <div class="row w-full space-between grid-full g-10">
             <strong class="top-10 right-auto c-gold grid-full" style="font-family:'cinzel decorative'">Commission Levels</strong>
             <span onclick="spa(event,'{{ url('users/referrals') }}')" class="c-gold u top-auto">My Referrals</span>
        </div>
        <div class="dim p-20 w-full bg-dim br-10 column g-10">
            <span class="text-light">1st Level Commission</span>
           <strong class="desc c-primary">{{ $referral_settings->first_level }}%</strong>
           <span class="text-light text-dim">Direct Referrals</span>
        </div>
          <div class="dim p-20 w-full bg-dim br-10 column g-10">
            <span class="text-light">2nd Level Commission</span>
           <strong class="desc c-primary">{{ $referral_settings->second_level }}%</strong>
           <span class="text-light text-dim">Friends of Friends</span>
        </div>
    </div>
    <div class="column w-full max-w-500 g-10 top-10">
        <strong class="top-10 right-auto c-gold" style="font-family:'cinzel decorative'">How It Works</strong>
        <div class="bg-dim max-w-500 w-full p-10 column g-10 br-10">
            <div class="row g-10">
                <div class="h-30 perfect-square circle text-b c-black column justify-center gradient">1</div>
                <div class="column text-start align-start">
                    <b class="c-primary">Share Your Link</b>
                    <span>Copy your unique referral link and share to your friends via social media or messaging apps.</span>
                </div>
            </div>
             <div class="row g-10">
                <div class="h-30 perfect-square circle text-b c-black column justify-center gradient">2</div>
                <div class="column text-start align-start">
                    <b class="c-primary">Friends Sign Up</b>
                    <span>Your friends register using your link and become your level 1 referral.</span>
                </div>
            </div>
             <div class="row g-10">
                <div class="h-30 perfect-square circle text-b c-black column justify-center gradient">3</div>
                <div class="column text-start align-start">
                    <b class="c-primary">Level 1 Commission</b>
                    <span>Your friend purchases an asset,you instantly earn {{ $referral_settings->first_level }}% commission which can be withdrawn anytime.I.e your friend purchases an asset worth &#8358;100,000.00 you earn &#8358;{{ number_format(($referral_settings->first_level*100000)/100,2) }} commission.</span>
                </div>
            </div>
             <div class="row g-10">
                <div class="h-30 gradient perfect-square circle text-b c-black column justify-center">4</div>
                <div class="column text-start align-start">
                    <b class="c-primary">Level 2 Commission</b>
                    <span>Your friend refers another user and he/she purchases an asset,you instantly earn {{ $referral_settings->second_level }}% commission which can be withdrawn anytime.I.e your friends referral purchases an asset worth &#8358;100,000.00 your friend earns &#8358;{{ number_format(($referral_settings->first_level*100000)/100,2) }} and you earn &#8358;{{ number_format(($referral_settings->second_level*100000)/100,2) }}</span>
                </div>
            </div>
        </div>
    </div>
      
       </div>
       
    
    

    </section>
@endsection