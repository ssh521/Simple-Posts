@extends('simple-posts::posts.layout')

@section('title', '게시글 수정')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">게시글 수정</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('posts.update', ['post' => $post]) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">제목</label>
                    <input type="text" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $post->title) }}" 
                           required>
                    @error('title')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">내용</label>
                    <input type="hidden" class="@error('content') border-red-500 @enderror" 
                              id="content" 
                              name="content" 
                              required value="{{ old('content', $post->content) }}">
                    <div id="editor" class="border border-gray-300 rounded-md"></div>
                    @error('content')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">날짜</label>
                    <input type="date" 
                           class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror" 
                           id="date" 
                           name="date" 
                           value="{{ old('date', $post->date->format('Y-m-d')) }}" 
                           required>
                    @error('date')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">수정</button>
                    <a href="{{ route('posts.show', ['post' => $post]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editorElement = document.querySelector('#editor');
        const formElement = document.querySelector('form');
        const contentInput = document.querySelector('#content');
        
        const editor = initializeEditor(editorElement, contentInput.value || '');
        setupFormSubmission(editor, formElement, contentInput);
    });
</script>
@endsection