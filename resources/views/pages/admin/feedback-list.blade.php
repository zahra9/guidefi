@extends('layouts.app')

@section('linked_css')
<link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
    <style>
        .fa-star{
            color: #e48107;
        }
        .fa-star-half{
            color: #e48107;
        }
    </style>
@endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div>
                    <h4 class="card-title font-weight-bold">Feedback List</h4>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Client</th>
                                    <th>Advisor</th>
                                    <th>Expertise Level</th>
                                    <th>Availability</th>
                                    <th>Communication</th>
                                    <th>Feedback</th>
                                    <th>Feedback By</th>
                                </tr>
                                </thead>
                                <tbody>

                                    @if($feedbacks->count())
                                    @foreach($feedbacks as $feedback)
                                    <tr class="text-black">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ isset($feedback->client) ? $feedback->client->name : '' }}</td>
                                        <td>{{ isset($feedback->advisor) ? $feedback->advisor->name : '' }}</td>
                                        <td>
                                            @while($feedback->expertise_level>0)
                                                @if($feedback->expertise_level >0.5)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="fas fa-star-half"></i>
                                                @endif
                                                @php $feedback->expertise_level--; @endphp
                                            @endwhile
                                        </td>
                                        <td>
                                            @while($feedback->availability>0)
                                                @if($feedback->availability >0.5)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="fas fa-star-half"></i>
                                                @endif
                                                @php $feedback->availability--; @endphp
                                            @endwhile
                                        </td>
                                        <td>
                                            @while($feedback->communication>0)
                                                @if($feedback->communication >0.5)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="fas fa-star-half"></i>
                                                @endif
                                                @php $feedback->communication--; @endphp
                                            @endwhile
                                        </td>
                                        <td>{{ $feedback->feedback }}</td>
                                        <td>{{ $feedback->feedback_by }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                              
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>


 @endsection
@section('scripts')

@endsection
