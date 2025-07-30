@php
    /** @var \WebDevEtc\BlogEtc\Models\Post $post */
@endphp
{{--Used on the index page (so shows a small summary--}}
{{--See the guide on webdevetc.com for how to copy these files to your /resources/views/ directory--}}

<div class="col-md-4 blog-post">
    <article >
        <div>
            @if(!is_null($post->image_medium) && file_exists(public_path().'/blog_images/'.$post->image_medium))
                <a href="{{ $post->url() }}">
                    <img src="{{ asset('blog_images/'.$post->image_medium) }}" width="100%" alt="{{ $post->title }}" class="">
                </a>
            @else
                <a href="{{ $post->url() }}"><img src="{{asset('assets/images/blog-dummy.png')}}" width="100%" alt=""></a>
            @endif
        </div>
        <div class="entry-content-wrapper "style="height: 210px; overflow:hidden;">
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

