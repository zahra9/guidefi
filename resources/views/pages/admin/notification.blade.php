@extends('layouts.app')


@section('content')
<div class="content-wrapper">
    <div class="row">
     @foreach($notifications as $notification)
        <div class="col-md-12 col-sm-12 col-xl-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex">
                            <div class="preview-thumbnail mt-4">
                                @if(isset($notification->sender->person))
                                    @if(!is_null($notification->sender || $notification->sender->person || $notification->sender->person->image_url))
                                        <img src="{{$notification->sender->person->image_url}}" width="150px" height="150px" style="border-radius: 50%;" alt="">
                                    @else
                                        <img src="{{asset('images/default-avatar.png')}}" width="150px" height="150px" style="border-radius: 50%;" alt="">
                                    @endif
                                    @endif

                            </div>
                            <div class="pl-4">
                                <h6 class="text-black">
                                    {{!is_null($notification->sender)?$notification->sender->name:''}}
                                    @if(isset($notification->sender->roles))
                                    ({{!is_null($notification->sender||$notification->sender->roles)?$notification->sender->roles->first()->name:''}})
                                    send notification to
                                    {{!is_null($notification->receiver)?$notification->receiver->name:''}}
                                    ({{!is_null($notification->receiver)?$notification->receiver->roles->first()->name:''}})
                                    @endif
                                </h6>
                                <h5 class="text-black">{{!is_null($notification->description)?$notification->description:''}}</h5>
                                <p class="text-gray">{{!is_null($notification->created_at)?$notification->created_at->diffForHumans():''}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- content-wrapper ends -->

@endsection