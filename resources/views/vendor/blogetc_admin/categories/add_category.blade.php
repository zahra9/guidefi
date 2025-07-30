@extends('blogetc_admin::layouts.admin_layout')
@section('title', 'BlogEtc - Add Category')
@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="mb-5 font-weight-normal">Admin - Add Category</h5>

        <form method="post" action="{{ route('blogetc.admin.categories.create_category') }}" enctype="multipart/form-data">
            @csrf
            @include('blogetc_admin::categories.form', ['category' => new \WebDevEtc\BlogEtc\Models\Category()])

            <input type="submit" class="btn btn-primary" value="Add New Category">
        </form>
    </div>
</div>

@endsection