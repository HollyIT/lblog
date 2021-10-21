<div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">

    <a href="{{ route('post.show', ['post' => $post]) }}">
    @if($post->image)

    <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="{{ $post->image->formatUrl('front') }}" alt="blog">
        @else
        <div class="lg:h-48 md:h-36 w-full bg-black"></div>
    @endif
    <div class="p-6">
        <h2 class="title-font text-lg font-medium text-gray-900 mb-3">{{ $post->title  }}</h2>
        <p class="leading-relaxed mb-3">{{ $post->description }}</p>
    </div>
</a>
</div>
