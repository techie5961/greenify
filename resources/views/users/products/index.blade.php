@extends('layout.users.app')
@section('title')
    Products
@endsection
@section('main')
    <section class="column pc-grid grid-2 w-full g-10 p-10">
       @if ($products->isEmpty())
           @include('components.sections',[
            'null' => 'No Products Available'
           ])
       @else
            <strong class="desc grid-full c-primary">Available Products</strong>
       @foreach ($products as $data)
            <div class="w-full bg-dim p-10 column g-10 br-10">
           <div style="height:100px;background-image:url('{{ asset('products/'.$data->photo.'') }}')" class="bg-image br-10 w-full"></div>
        <hr class="gradient">
      <div class="row space-between w-full g-10">
        <div class="column g-5">
              <strong class="font-1 c-primary">{{ $data->name }}</strong>
              <span class="text-light text-dim">Product Name</span>
        </div>
         <div class="column g-5">
              <strong class="font-1 c-primary left-auto">&#8358;{{ number_format($data->return,2) }}</strong>
              <span class="text-light text-dim left-auto">Daily Earnings</span>
        </div>
      </div>
      <div class="row space-between w-full g-10">
        <div class="column g-5">
              <strong class="font-1 c-primary">&#8358;{{ number_format($data->return*$data->validity,2) }}</strong>
              <span class="text-light text-dim">Total Earnings</span>
        </div>
         <div class="column g-5">
              <strong class="font-1 text-gradient c-primary left-auto">{{ number_format($data->validity) }} Days</strong>
              <span class="text-light text-dim left-auto">Lifespan</span>
        </div>
      </div>
      <hr>
      <div class="row space-between">
        <strong class="desc c-primary">&#8358;{{ number_format($data->price,2) }}</strong>
        <button onclick="GetRequest(event,'{{ url('users/get/products/purchase?id='.$data->id.'') }}',MyFunc.CallBack)" style="width:fit-content;height:fit-content;" class="btn p-10 btn-gradient"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm-88,96A48.05,48.05,0,0,1,80,88a8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0A48.05,48.05,0,0,1,128,136Z"></path></svg>Purchase</button>
      </div>
      
        </div>
       @endforeach
       @endif
    </section>
    
@endsection
@section('js')
    <script class="js">
    
        MyFunc={
            CallBack : function(response){
             
                SlideUp(response);
            },
            Confirmed : function(response){
              let data=JSON.parse(response);
              CreateNotify(data.status,data.message);
              if(data.status == 'success'){
                HideSlideUp();
                spa(event,data.url);
              }
            },
          

        }
  
    </script>
@endsection