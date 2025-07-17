@extends('layout.users.app')
@section('title')
    My Products
@endsection
@section('main')
    <section class="section1 column pc-grid pc-grid-2 w-full g-10 p-10">
        @if ($purchased->isEmpty())
            @include('components.sections',[
                'null' => 'You have no active product'
            ])
        @else
             <strong class="desc grid-full c-primary">My Products</strong>
       @foreach ($purchased as $data)
            <div class="column bg-dim p-10 g-10 br-10">
            <div style="height:100px;background-image:url('{{ asset('products/'.$data->json->photo.'') }}')" class="bg-image w-full br-10"></div>
           <div class="row space-between w-full g-10">
                <div class="column">
                    <strong class="font-1 c-primary right-auto">{{ $data->json->name }}</strong>
                    <span class="text-light text-dim right-auto">Name</span>
                </div>
                <div class="column">
                    <strong class="font-1 c-primary left-auto">&#8358;{{ number_format($data->json->price,2) }}</strong>
                <span class="text-light left-auto text-dim">Purchased For</span>
                </div>
            </div> 
            <div class="row space-between w-full g-10">
                <div class="column">
                    <strong class="font-1 c-primary right-auto">&#8358;{{ number_format($data->json->return,2) }}</strong>
                    <span class="text-light text-dim right-auto">Daily Earnings</span>
                </div>
                <div class="column">
                    <strong class="font-1 c-primary left-auto">&#8358;{{ number_format($data->json->return*$data->json->validity,2) }}</strong>
                <span class="text-light left-auto text-dim">Total Return</span>
                </div>
            </div>
            <div class="row space-between w-full g-10">
                <div class="column">
                    <strong class="font-1 c-primary right-auto">{{ $data->expires }}</strong>
                    <span class="text-light text-dim right-auto">Expires</span>
                </div>
                <div class="column">
                    <strong class="font-1 c-primary text-end left-auto">{{ $data->next_reward }}</strong>
                <span class="text-light left-auto text-dim">Next Income</span>
                </div>
            </div>
        </div>
       @endforeach
        @endif
       
    </section>
@endsection