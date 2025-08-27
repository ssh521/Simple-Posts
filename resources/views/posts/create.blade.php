@extends('simple-posts::posts.layout')

@section('title', '새 게시글 작성')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">새 게시글 작성</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('posts.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">제목</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">내용</label>
                        <input type="hidden" class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  required value="{{ old('content') }}">
                        <div id="editor"></div>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">날짜</label>
                        <input type="date" 
                               class="form-control @error('date') is-invalid @enderror" 
                               id="date" 
                               name="date" 
                               value="{{ old('date', date('Y-m-d')) }}" 
                               required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">저장</button>
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">취소</a>
                    </div>
                </form>
            </div>
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
        initialValue: document.querySelector('#content').value || ''
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