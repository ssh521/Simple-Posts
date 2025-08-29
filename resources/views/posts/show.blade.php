@extends('simple-posts::posts.layout')

@section('title', $post->title)

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">{{ $post->title }}</h2>
        <small class="text-gray-500">{{ $post->date->format('Y-m-d') }}</small>
    </div>
    <div class="p-6">
        <div class="mb-6" id="viewer"></div>
        
        <div class="flex gap-3">
            <a href="{{ route('posts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">목록으로</a>
            <a href="{{ route('posts.edit', $post) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">수정</a>
            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-200" 
                        onclick="return confirm('정말 삭제하시겠습니까?')">삭제</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewer = new toastui.Editor.factory({
        el: document.querySelector('#viewer'),
        viewer: true,
        initialValue: {!! json_encode($post->content) !!}
    });
});
</script>
@endsection