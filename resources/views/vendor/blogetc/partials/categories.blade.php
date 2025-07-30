@php
    /** @var \WebDevEtc\BlogEtc\Models\Post $post */
@endphp
<div>
    <p class="font-12 float-left font-weight-bold pt-2">Category :</p>
    @foreach($post->categories as $category)
        <a class="btn btn-default btn-sm m-1" href="{{ $category->url() }}">
            {{ $category->category_name }}
        </a>
    @endforeach
</div>

