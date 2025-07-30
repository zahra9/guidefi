@extends('blogetc_admin::layouts.admin_layout')
@section('content')
    @forelse ($categories as $category)
        <div class="card m-4">
            <div class="card-body">
                <h5 class="card-title font-weight-normal"><a href="{{$category->url()}}">{{$category->category_name}}</a></h5>
                <a href="{{$category->url()}}" class="card-link btn btn-outline-secondary">
                    View Posts in this category
                </a>
                
                <form
                        onsubmit="return confirm('Are you sure you want to delete this blog post category?\n You cannot undo this action!');"
                        method="post"
                        action="{{route("blogetc.admin.categories.destroy_category", $category->id)}}"
                        class="float-right">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-danger" value="Delete">
                </form>
                <a href="{{$category->edit_url()}}" class="card-link btn btn-primary float-right mr-4">Edit Category</a>
            </div>
        </div>
    @empty
        <div class="alert alert-danger">None found, why don't you add one?</div>
    @endforelse

    <div class="text-center">
        {{ $categories->links() }}
    </div>
@endsection
