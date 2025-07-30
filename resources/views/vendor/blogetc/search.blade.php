@extends("home.layouts.master")
@section("content")

    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-8 mt-5">
                <div class="row">
                @forelse($search_results as $key => $result)
                        @include('blogetc::partials.index_loop', ['post' => $result->indexable])
                    @empty
                        <div class="col-md-12 blog-post">
                            <div class="alert alert-danger">Sorry, but there were no results!</div>
                        </div>
                    @endforelse
                </div>
            </div>
            @include('vendor.blogetc.partials.side_bar')
        </div>
    </div>

@endsection

