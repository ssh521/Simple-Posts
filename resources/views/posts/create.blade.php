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
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="5" 
                                  required>{{ old('content') }}</textarea>
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
@endsection