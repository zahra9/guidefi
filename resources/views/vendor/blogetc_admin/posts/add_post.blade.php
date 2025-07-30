@extends('blogetc_admin::layouts.admin_layout')
@section('content')
<div class="card mb-5">
    <div class="card-body">
        <h5 class="font-weight-normal mb-5">Admin - Add post</h5>

        <form method="POST" action="{{ route('post-store') }}" enctype="multipart/form-data">
            @csrf
            @include("blogetc_admin::posts.form", ['post' => new \WebDevEtc\BlogEtc\Models\Post()])
            <input type="submit" class="btn btn-primary" value="Add new post">
        </form>
    </div>
</div>

@endsection