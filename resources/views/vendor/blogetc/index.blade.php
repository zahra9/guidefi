@extends("home.layouts.master")
@section("content")

<!-- <div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="text-center col-sm-4 mx-auto">
        {{$posts->appends( [] )->links()}}
      </div>
      @include("blogetc::sitewide.search_form")
    </div>
  </div>
  <div class="col-sm-12 blogetc_container">
    @can(\WebDevEtc\BlogEtc\Gates\GateTypes::MANAGE_BLOG_ADMIN)
    <div class="text-center">
      <p class="mb-1">
        You are logged in as a blog admin user.
        <br>
        <a href="{{route("blogetc.admin.index")}}" class="btn border btn-outline-primary btn-sm">
          <i class="fa fa-cogs" aria-hidden="true"></i>
          Go To Blog Admin Panel
        </a>
      </p>
    </div>
    @endcan
    <div class="row mt-5 mb-5">
      @if(isset($blogetc_category) && $blogetc_category)
      <div class="col-md-12  mb-5">
        <h2 class="text-center font-weight-normal">
          Viewing Category: {{$blogetc_category->category_name}}
        </h2>
      </div>
      @if($blogetc_category->category_description)
      <p class="text-center">{{$blogetc_category->category_description}}</p>
      @endif
      @endif

      @forelse($posts as $post)
      <div class="col-lg-3 col-md-4">
        @include("blogetc::partials.index_loop")
      </div>
      @empty
      <div class="alert alert-danger">No posts</div>
      @endforelse

    </div>
  </div>

</div> -->

<div class="container">
  <div class="row ">
    <div class="col-md-8 mt-5">
      <div class="row">
        @if(isset($posts) && !is_null($posts))
        @foreach($posts as $post)
        @if($post->is_published == 1)
        <div class="col-md-4 blog-post">
          <article>
            <div>
              @if(!is_null($post->image_medium) && file_exists(public_path().'/blog_images/'.$post->image_medium))
              <a href="{{ $post->url() }}">
                <img src="{{ asset('blog_images/'.$post->image_medium) }}" width="100%" alt="{{ $post->title }}" class="">
              </a>
              @else
              <a href="{{ $post->url() }}"><img src="{{asset('assets/images/blog-dummy.png')}}" width="100%" alt=""></a>
              @endif
            </div>
            <div class="entry-content-wrapper " style="height: 210px; overflow:hidden;">
              <div class="entry-meta-top">
                <span class="entry-meta-data">{{ isset($post->created_at) ? $post->created_at->format('M d, Y') : ''}}</span>
                <span class="entry-meta-category">
                  @if(isset($post->categories->first()->slug) && !is_null($post->categories->first()->slug))
                  <a href="{{ url('blog/category',$post->categories->first()->slug) }}">{{ isset($post->categories->first()->category_name) ? $post->categories->first()->category_name : "" }}</a>
                  @endif
                </span>
              </div>
              <h4 class="entry-title">
                <a href="{{ $post->url() }}">{{ isset($post->title) ? str_limit($post->title , 40) : '' }}</a>
              </h4>
              <div class="entry-excerpt">
                {!! isset($post->post_body) ? $post->post_body : '' !!}
              </div>
            </div>
          </article>
        </div>
        @endif
        @endforeach
        @endif
      </div>
    </div>
    @include('vendor.blogetc.partials.side_bar')
  </div>
</div>



@endsection