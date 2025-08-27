@extends('simple-posts::posts.layout')

@section('title', $post->title)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">{{ $post->title }}</h2>
        <small class="text-muted">{{ $post->date->format('Y-m-d') }}</small>
    </div>
    <div class="card-body">
        <div class="mb-4" id="viewer"></div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">목록으로</a>
            <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">수정</a>
            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" 
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