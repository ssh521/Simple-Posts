@extends('simple-posts::posts.layout')

@section('title', '새 게시글 작성')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">새 게시글 작성</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('posts.store') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">제목</label>
                    <input type="text" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}" 
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
                              required value="{{ old('content') }}">
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
                           value="{{ old('date', date('Y-m-d')) }}" 
                           required>
                    @error('date')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">저장</button>
                    <a href="{{ route('posts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">취소</a>
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

        if (!window.toastui || !window.toastui.Editor) {
            console.error('Toast UI Editor가 로드되지 않았습니다.');
            return;
        }
        
        if (!editorElement) {
            console.error('#editor 엘리먼트를 찾을 수 없습니다.');
            return;
        }

        if (!formElement) {
            console.error('form 엘리먼트를 찾을 수 없습니다.');
            return;
        }

        if (!contentInput) {
            console.error('#content input 엘리먼트를 찾을 수 없습니다.');
            return;
        }
        
        const editor = initializeEditor(editorElement, contentInput.value || '');
        
        if (editor) {
            // 에디터 내용이 변경될 때마다 hidden input 업데이트
            editor.on('change', () => {
                contentInput.value = editor.getMarkdown();
            });
            
            // 폼 제출 시 최종 내용 저장
            setupFormSubmission(editor, formElement, contentInput);
        } else {
            console.error('에디터를 초기화하는데 실패했습니다.');
            alert('에디터를 초기화하는데 실패했습니다. 페이지를 새로고침 해주세요.');
        }
    });
</script>
@endsection