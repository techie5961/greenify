@extends('layout.users.app')
@section('title')
    Transactions
@endsection
@section('css')
    <style>
        body,main{
            overflow:hidden;
            
        }
    
    </style>
@endsection
@section('main')
    <section class="pos-fixed overflow-y-auto column average bg">
    <div class="p-10 low bg-inherit row pos-stick stick-top space-between w-full align-center">
        <svg class="pc-pointer" onclick="spa(event,'{{ url()->previous() == request()->fullUrl() ? url()->to('users/dashboard') : url()->previous() }}')" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 256 256"><path d="M228,128a12,12,0,0,1-12,12H69l51.52,51.51a12,12,0,0,1-17,17l-72-72a12,12,0,0,1,0-17l72-72a12,12,0,0,1,17,17L69,116H216A12,12,0,0,1,228,128Z"></path></svg>
        <b>Transactions</b>
        <svg onclick="spa(event,'{{ url()->to('users/dashboard') }}')" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 256 256"><path d="M224,120v96a8,8,0,0,1-8,8H40a8,8,0,0,1-8-8V120a15.87,15.87,0,0,1,4.69-11.32l80-80a16,16,0,0,1,22.62,0l80,80A15.87,15.87,0,0,1,224,120Z"></path></svg>

       </div>
     <section class="body w-full align-center overflow-y-auto column flex-auto p-10 pos-stick">
          <section class="w-full infinite-section overflow-y-auto max-500 column g-10 {{ $trx->isEmpty() ? '' : 'bg-dim' }} br-10 p-10">
        @if ($trx->isEmpty())
            @include('components.sections',[
                'null' => 'No Transactions Found'
            ])
        @else
            @foreach ($trx as $data)
                 <div class="row align-center p-y-10 space-between g-5">
            @if ($data->class == 'credit')
                <div class="svg credit">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M200.49,72.48,93,180h75a12,12,0,0,1,0,24H64a12,12,0,0,1-12-12V88a12,12,0,0,1,24,0v75L183.51,55.51a12,12,0,0,1,17,17Z"></path></svg>
            </div>
            @else
                <div class="svg debit">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M204,64V168a12,12,0,0,1-24,0V93L72.49,200.49a12,12,0,0,1-17-17L163,76H88a12,12,0,0,1,0-24H192A12,12,0,0,1,204,64Z"></path></svg>
            </div>
            @endif
            <div class="column g-5 right-auto">
                <b>{{ ucfirst($data->type) }}</b>
                <span class="text-dim text-small">{{ $data->frame }}</span>
            </div>
            <div class="column">
            <strong>&#8358;{{ number_format($data->amount,2) }}</strong>
            <div class="status {{ $data->status == 'pending' ? 'gold' : ($data->status == 'rejected' ? 'red' : 'green') }} left-auto">{{ $data->status }}</div>
            </div>
        </div>
            @endforeach
                  @endif
         
       </section>
     </section>
    </section>
@endsection
