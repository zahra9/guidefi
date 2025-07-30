@if (config('blogetc.search.search_enabled') )
    <div class="col-md-4 search-form-outer mt-5 ml-auto mr-auto">
        <form method="get" action="{{ route('blogetc.search') }}" class="text-center">
            <div class="form-group float-left">
            <input type="text" name="s" placeholder="Search Blogs" class="form-control blogSearchBox" value="{{ Request::get('s') }}">
            </div>
            <div class="form-group">
            <input type="submit" value="Search" class="btn btnBlogSearch">
            </div>
        </form>
    </div>
@endif
