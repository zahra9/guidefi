{{--@php--}}
{{--/** @var \WebDevEtc\BlogEtc\Models\Post $post */--}}
{{--@endphp--}}
@extends('blogetc_admin::layouts.admin_layout')
@section('content')
{{--<div class="card">--}}
{{--    <div class="card-body">--}}
{{--        <h5 class="mb-5">Admin - Editing post--}}
{{--            <a target="_blank" href="{{ $post->url() }}" class="float-right btn btn-primary">--}}
{{--                View post--}}
{{--            </a>--}}
{{--        </h5>--}}

{{--        <form method="post" action="{{ route('blogetc.admin.update_post', $post->id) }}" enctype="multipart/form-data">--}}
{{--            @csrf--}}
{{--            @method('patch')--}}
{{--            @include('blogetc_admin::posts.form', ['post' => $post])--}}
{{--            <input type="submit" class="btn btn-primary" value="Save Changes">--}}
{{--        </form>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="container-fluid mt-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    {{--                    <img src="{{asset('assets/images/show_post.jpg')}}" width="100%" alt="">--}}
                    {{--                    @if($post->imageTag('medium', true, '') == '')--}}
                    {{--                        <a href="{{ $post->url() }}"><img src="{{asset('assets/images/blog-dummy.png')}}" width="100%" alt=""></a>--}}
                    {{--                    @else--}}
                    {{--                        <a href="{{ $post->url() }}">{!! $post->imageTag('medium', true, '') !!}</a>--}}
                    {{--                    @endif--}}

                    @if(!is_null($post->image_large) && file_exists(public_path().'/blog_images/'.$post->image_large))
                        <a href="{{ $post->url() }}">
                            <img src="{{ asset('blog_images/'.$post->image_large) }}" width="100%" alt="{{ $post->title }}" class="">
                        </a>
                    @else
                        <a href="{{ $post->url() }}"><img src="{{asset('assets/images/blog-dummy.png')}}" width="100%" alt=""></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-10 ml-auto mr-auto">
                <div>
                    <article>
                        <div class="post_text">
                            <div class="post_meta_top">
                                <span class="post_meta_category">
                                    @if(isset($post->categories) && !is_null($post->categories))
                                        @foreach($post->categories as $category)
                                            <a href="{{ url('blog/category',$category->slug) }}">{{ isset($category->category_name) ? $category->category_name : "" }}</a>
                                        @endforeach
                                    @endif
                                </span>
                                <span class="post_meta_date">{{ isset($post->created_at) ? $post->created_at->format('M d, Y') : ''}}</span>
                            </div>
                            <h2 class="post_title mb-20px">{{ isset($post) ? $post->title : '' }}</h2>
                            <div class="post_content">
                            {{ isset($post->post_body) ? $post->post_body : '' }}
                                {!! isset($post->post_body) ? $post->post_body : '' !!}
                            </div>
                            {{--                            <div class="separator-line mb-25px"></div>--}}
                            {{--                            <div class="navigation post-navigation">--}}
                            {{--                                <div class="row align-items-center nav-links">--}}
                            {{--                                    <div class="col-lg-6 text-lg-left">--}}
                            {{--                                        <div class="nav-previous mb-15px">--}}
                            {{--                                            <div class="nav-subtitle"> Previous Post </div>--}}
                            {{--                                            <div class="nav-title">--}}
                            {{--                                                <a href="#"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Sellentesque tristiue neque</a></div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="col-lg-6 text-lg-right">--}}
                            {{--                                        <div class="nav-next mb-15px">--}}
                            {{--                                            <div class="nav-subtitle">Next Post</div>--}}
                            {{--                                            <div class="nav-title"><a href="#">Aliquam lobortis urna libero--}}
                            {{--                                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="separator-line mb-25px"></div>--}}
                            <div class="blog-post-comments">
                                <div class="comments-area mb-45px">
                                    <ol class="comment-list">
                                        @php
                                            /** @var \WebDevEtc\BlogEtc\Models\Comment[] $comments */
                                        @endphp
                                        @if(isset($comments))
                                        @foreach($comments as $comment)
                                            <li class="comment" id="comment-1">
                                                <article id="div-comment-1" class="comment-body">
                                                    <header class="comment-meta">
                                                                                                            <img class="avatar" src="https://placehold.it/75x75/eee" alt="img" width="75" height="75">
                                                        <cite>
                                                            <span >{{ $comment->author() }} </span>
                                                        </cite>
                                                        <span>{{ $comment->created_at->format('M d, Y')}} at {{ $comment->created_at->format('h:m A')}}  </span>
                                                                                                            <a class="comment-reply-link" href="#" aria-label="Reply to A WordPress Commenter">Reply</a>
                                                    </header>
                                                    <section class="comment-content comment">
                                                        <p>{!! nl2br(e($comment->comment)) !!}</p>
                                                    </section>
                                                </article>
                                                @endforeach
                                                @else
                                                    <div class="alert alert-info">
                                                        No comments yet!
{{--                                                        @can(\WebDevEtc\BlogEtc\Gates\GateTypes::ADD_COMMENT)--}}
{{--                                                            Why don't you be the first?--}}
{{--                                                        @endcan--}}
                                                    </div>
                                                @endif
                                            </li>
                                    </ol>
                                </div>
{{--                                <h5 class="comment-reply-title mb-4">Leave a comment</h5>--}}

{{--                                <form method="post" action="{{ route('blogetc.comments.add_new_comment', $post->slug) }}">--}}
{{--                                    @csrf--}}

{{--                                    <div class="form-group">--}}

{{--                                        <label id="comment_label" for="comment">Your Comment</label>--}}
{{--                                        <textarea--}}
{{--                                                class="form-control"--}}
{{--                                                name="comment"--}}
{{--                                                required--}}
{{--                                                id="comment"--}}
{{--                                                placeholder="Write your comment here"--}}
{{--                                                rows="7">{{ old('comment') }}</textarea>--}}

{{--                                    </div>--}}

{{--                                    <div class="container-fluid">--}}
{{--                                        <div class="row">--}}
{{--                                            @if(config("blogetc.comments.save_user_id_if_logged_in", true) === false || !Auth::check())--}}
{{--                                                <div class="col-md-4 pl-0">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label id="author_name_label" for="author_name">Your Name </label>--}}
{{--                                                        <input--}}
{{--                                                                type="text"--}}
{{--                                                                class="form-control"--}}
{{--                                                                name="author_name"--}}
{{--                                                                id="author_name"--}}
{{--                                                                placeholder="Your name"--}}
{{--                                                                required--}}
{{--                                                                value="{{old("author_name")}}">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                @if(config("blogetc.comments.ask_for_author_email"))--}}
{{--                                                    <div class="col-md-4">--}}
{{--                                                        <div class="form-group">--}}
{{--                                                            <label id="author_email_label" for="author_email">Your Email--}}
{{--                                                            </label>--}}
{{--                                                            <input--}}
{{--                                                                    type="email"--}}
{{--                                                                    class="form-control"--}}
{{--                                                                    name="author_email"--}}
{{--                                                                    id="author_email"--}}
{{--                                                                    placeholder="Your Email"--}}
{{--                                                                    required--}}
{{--                                                                    value="{{old("author_email")}}">--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
{{--                                            @endif--}}

{{--                                            @if(config("blogetc.comments.ask_for_author_website"))--}}
{{--                                                <div class="col-md-4 pr-0">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label id="author_website_label" for="author_website">Your Website--}}
{{--                                                        </label>--}}
{{--                                                        <input--}}
{{--                                                                type="url"--}}
{{--                                                                class="form-control"--}}
{{--                                                                name="author_website"--}}
{{--                                                                id="author_website"--}}
{{--                                                                placeholder="Your Website URL"--}}
{{--                                                                value="{{old("author_website")}}">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    @if(!is_null($captcha))--}}
{{--                                         Captcha is enabled. Load the type class and then include the view as defined in the captcha class. --}}
{{--                                        @include($captcha->view())--}}
{{--                                    @endif--}}

{{--                                    <div class="form-group mb-5 pb-5">--}}
{{--                                        <input type="submit" class="btn-shadow btn" value="Add Comment">--}}
{{--                                    </div>--}}
{{--                                </form>--}}

                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection