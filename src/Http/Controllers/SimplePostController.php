<?php

namespace Ssh521\SimplePosts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

use Ssh521\SimplePosts\Models\SimplePost;
use Ssh521\SimplePosts\Http\Requests\PostRequest;

class SimplePostController extends Controller
{
    public function index()
    {
        try {
            $perPage = config('simple-posts.pagination.per_page', 10);
            $posts = SimplePost::orderBy('date', 'desc')->paginate($perPage);
            return view('simple-posts::posts.index', compact('posts'))->withErrors([]);
        } catch (\Exception $e) {
            Log::error('게시글 목록 조회 중 오류 발생: ' . $e->getMessage());
            return view('simple-posts::posts.index', ['posts' => collect()])
                ->with('error', '게시글 목록을 불러오는 중 오류가 발생했습니다.')
                ->withErrors([]);
        }
    }

    public function show(SimplePost $post)
    {
        try {
            return view('simple-posts::posts.show', compact('post'))->withErrors([]);
        } catch (ModelNotFoundException $e) {
            Log::warning('존재하지 않는 게시글 접근 시도: ID ' . request()->route('post'));
            return redirect()->route('posts.index')
                ->with('error', '요청하신 게시글을 찾을 수 없습니다.');
        } catch (\Exception $e) {
            Log::error('게시글 조회 중 오류 발생: ' . $e->getMessage());
            return redirect()->route('posts.index')
                ->with('error', '게시글을 불러오는 중 오류가 발생했습니다.');
        }
    }

    public function create()
    {
        try {
            return view('simple-posts::posts.create')->withErrors([]);
        } catch (\Exception $e) {
            Log::error('게시글 작성 페이지 로드 중 오류 발생: ' . $e->getMessage());
            return redirect()->route('posts.index')
                ->with('error', '게시글 작성 페이지를 불러오는 중 오류가 발생했습니다.');
        }
    }

    public function store(PostRequest $request)
    {
        try {
            $post = SimplePost::create($request->validated());
            Log::info('새 게시글 생성됨: ID ' . $post->id);
            return redirect()->route('posts.index')
                ->with('success', '게시글이 성공적으로 생성되었습니다.');
        } catch (\Exception $e) {
            Log::error('게시글 생성 중 오류 발생: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', '게시글 생성 중 오류가 발생했습니다. 다시 시도해주세요.');
        }
    }

    public function edit(SimplePost $post)
    {
        try {
            return view('simple-posts::posts.edit', compact('post'))->withErrors([]);
        } catch (ModelNotFoundException $e) {
            Log::warning('존재하지 않는 게시글 수정 시도: ID ' . request()->route('post'));
            return redirect()->route('posts.index')
                ->with('error', '수정하려는 게시글을 찾을 수 없습니다.');
        } catch (\Exception $e) {
            Log::error('게시글 수정 페이지 로드 중 오류 발생: ' . $e->getMessage());
            return redirect()->route('posts.index')
                ->with('error', '게시글 수정 페이지를 불러오는 중 오류가 발생했습니다.');
        }
    }

    public function update(PostRequest $request, SimplePost $post)
    {
        try {
            $post->update($request->validated());
            Log::info('게시글 수정됨: ID ' . $post->id);
            return redirect()->route('posts.show', $post)
                ->with('success', '게시글이 성공적으로 수정되었습니다.');
        } catch (ModelNotFoundException $e) {
            Log::warning('존재하지 않는 게시글 수정 시도: ID ' . $post->id);
            return redirect()->route('posts.index')
                ->with('error', '수정하려는 게시글을 찾을 수 없습니다.');
        } catch (\Exception $e) {
            Log::error('게시글 수정 중 오류 발생: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', '게시글 수정 중 오류가 발생했습니다. 다시 시도해주세요.');
        }
    }

    public function destroy(SimplePost $post)
    {
        try {
            $postId = $post->id;
            $post->delete();
            Log::info('게시글 삭제됨: ID ' . $postId);
            return redirect()->route('posts.index')
                ->with('success', '게시글이 성공적으로 삭제되었습니다.');
        } catch (ModelNotFoundException $e) {
            Log::warning('존재하지 않는 게시글 삭제 시도: ID ' . request()->route('post'));
            return redirect()->route('posts.index')
                ->with('error', '삭제하려는 게시글을 찾을 수 없습니다.');
        } catch (\Exception $e) {
            Log::error('게시글 삭제 중 오류 발생: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '게시글 삭제 중 오류가 발생했습니다. 다시 시도해주세요.');
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);

        try {
            $image = $request->file('image');
            $originalName = $image->getClientOriginalName();
            // 파일명의 모든 공백을 언더바로 변경
            $nameWithoutSpaces = preg_replace('/\s+/', '_', $originalName);
            // 특수문자 제거 (언더바 제외)
            $safeFilename = preg_replace('/[^a-zA-Z0-9가-힣._-]/', '', $nameWithoutSpaces);
            $filename = time() . '_' . $safeFilename;
            $path = $image->storeAs('posts/images', $filename, 'public');
            
            $url = asset('storage/' . $path);
            
            return response()->json([
                'success' => true,
                'url' => $url,
                'filename' => $filename
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => '이미지 업로드 중 오류가 발생했습니다.'
            ], 500);
        }
    }

}
