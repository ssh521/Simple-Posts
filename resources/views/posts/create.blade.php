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
        const editor = new toastui.Editor({
            el: document.querySelector('#editor'),
            height: '600px',
            initialEditType: 'markdown',
            previewStyle: 'vertical',
            initialValue: document.querySelector('#content').value || '',
            /* start of hooks */
            hooks: {
                addImageBlobHook(blob, callback) {  // 이미지 업로드 로직 커스텀
                    const formData = new FormData();
                    formData.append('image', blob);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    
                    fetch('{{ route("posts.upload-image") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            callback(data.url, data.filename);
                        } else {
                            alert('이미지 업로드 실패: ' + (data.message || '알 수 없는 오류'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('이미지 업로드 중 오류가 발생했습니다.');
                    });
                }
            }
            /* end of hooks */
        });
    
        // 폼 제출 시 에디터 내용을 hidden input에 동기화
        document.querySelector('form').addEventListener('submit', function(e) {
            // 에디터 내용을 hidden input에 저장
            const content = editor.getMarkdown();
            document.querySelector('#content').value = content;
            
            // 내용이 비어있으면 제출 방지
            if (!content.trim()) {
                e.preventDefault();
                alert('내용을 입력해주세요.');
                return false;
            }
            
            console.log('제출할 내용:', content); // 디버깅용
        });
    
        // 에디터 내용이 변경될 때마다 hidden input 업데이트 (실시간 동기화)
        editor.on('change', function() {
            document.querySelector('#content').value = editor.getMarkdown();
        });
    });
    </script>
@endsection