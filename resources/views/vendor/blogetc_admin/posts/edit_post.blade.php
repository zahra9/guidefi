@php
/** @var \WebDevEtc\BlogEtc\Models\Post $post */
@endphp
@extends('blogetc_admin::layouts.admin_layout')
@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="mb-5">Admin - Editing post
            <a target="_blank" href="{{ route('view-post',$post->slug)}}" class="float-right btn btn-primary">
                View post
            </a>
        </h5>

        <form method="post" action="{{ route('update-post', $post->id) }}" enctype="multipart/form-data">
            @csrf
            @method('patch')
            @include('blogetc_admin::posts.form', ['post' => $post])
            <input type="submit" class="btn btn-primary" value="Save Changes">
        </form>
    </div>
</div>

@endsection