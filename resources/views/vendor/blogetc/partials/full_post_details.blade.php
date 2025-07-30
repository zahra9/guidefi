@php
/** @var \WebDevEtc\BlogEtc\Models\Post $post */
@endphp


{!! $post->imageTag('medium', false, 'd-block mx-auto blogImagewidth') !!}
@can(\WebDevEtc\BlogEtc\Gates\GateTypes::MANAGE_BLOG_ADMIN)
<a href="{{$post->editUrl()}}" class="btn btn-blogEditPost  btn-sm pull-right float-right">
    Edit Post
</a>
@endcan
<h2 class="blog_title font-weight-bold mt-4">{{$post->title}}</h2>
<!-- <h5 class="blog_subtitle font-weight-normal">{{$post->subtitle}}</h5> -->


<div>

    @if($post->posted_at)
    <p class="text-gray-actual font-12"><strong>@includeWhen($post->author, 'blogetc::partials.author', ['post'=>$post])</strong>
        @endif
       | {{ $post->posted_at->format('d/m/Y') }}
    </p>
    <!-- @includeWhen($post->categories, 'blogetc::partials.categories', ['post'=>$post]) -->


</div>
<hr />

<p class="blog_body_content">
    {!! $post->renderBody() !!}

    {{--@if(config("blogetc.use_custom_view_files") && $post->use_view_file)--}}
    {{-- // use a custom blade file for the output of those blog post--}}
    {{-- @include("blogetc::partials.use_view_file")--}}
    {{--@else--}}
    {{-- {!! $post->post_body !!}        // unsafe, echoing the plain html/js--}}
    {{-- {{ $post->post_body }} // for safe escaping --}}
    {{--@endif--}}
</p>

<hr />