@extends('simple-posts::posts.layout')

@section('title', '게시글 목록')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">게시글 목록</h2>
    <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">새 게시글 작성</a>
</div>

@if($posts->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">번호</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">제목</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">날짜</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">작업</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($posts as $post)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $post->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $post->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $post->date->format(config('simple-posts.date_format', 'Y-m-d')) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800 border border-blue-600 hover:bg-blue-50 px-3 py-1 rounded text-xs transition duration-200 mr-2">수정</a>
                                <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 border border-red-600 hover:bg-red-50 px-3 py-1 rounded text-xs transition duration-200" 
                                            onclick="return confirm('정말 삭제하시겠습니까?')">삭제</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
@else
    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-md">
        게시글이 없습니다. <a href="{{ route('posts.create') }}" class="text-blue-600 hover:text-blue-800 underline">첫 번째 게시글을 작성해보세요!</a>
    </div>
@endif
@endsection