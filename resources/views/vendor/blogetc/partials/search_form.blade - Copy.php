@php
$recentPosts =  \WebDevEtc\BlogEtc\Models\Post::where('is_published',1)->orderBy('id','Desc')->take(5)->get(['id','title','slug']);
$recentCategories =  \WebDevEtc\BlogEtc\Models\Category::orderBy('id','Desc')->take(5)->get();
@endphp
<div class="col-md-4 pl-5 pr-5 mt-5">
    <div class="widget">
        <form method="get" action="{{ route('blogetc.search') }}" class="text-center">
        <div class="form-group blog-search-box">
                <input type="input" class="form-control" id="" aria-describedby="" placeholder="Search..." name="s" value="{{ Request::get('s') }}">
            <button type="submit" value="Search" class="btn btnBlogSearch"><i class="fa fa-search" aria-hidden="true"></i></button>
            
        </div>
        </form>
    </div>
    <div class="recent-posts">
        <h5 class="widget-title">
            Recent Posts
        </h5>
        <ul>
            @if(isset($recentPosts) && !is_null($recentPosts))
            @foreach($recentPosts as $recentPost)
            <li>
                <a href="{{ url('blog',$recentPost->slug) }}">{{ $recentPost->title }}</a>
            </li>
            @endforeach
                @endif
        </ul>
    </div>
    <div class="category-list mt-5">
        <h5 class="widget-title">Categories</h5>
        <ul>
            @if(isset($recentCategories) && !is_null($recentCategories))
            @foreach($recentCategories as $recentCategory)
            <li>
                <a href="{{ url('blog/category',$recentCategory->slug) }}">{{ $recentCategory->category_name }}</a>
            </li>
            @endforeach
                @endif
        </ul>
    </div>
</div>
