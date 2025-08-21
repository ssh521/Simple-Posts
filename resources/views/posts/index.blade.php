@extends('simple-posts::posts.layout')

@section('title', '게시글 목록')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>게시글 목록</h2>
    <a href="{{ route('posts.create') }}" class="btn btn-primary">새 게시글 작성</a>
</div>

@if($posts->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>제목</th>
                    <th>날짜</th>
                    <th>작업</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                {{ $post->title }}
                            </a>
                        </td>
                        <td>{{ $post->date->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">수정</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('정말 삭제하시겠습니까?')">삭제</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $posts->links() }}
@else
    <div class="alert alert-info">
        게시글이 없습니다. <a href="{{ route('posts.create') }}">첫 번째 게시글을 작성해보세요!</a>
    </div>
@endif
@endsection