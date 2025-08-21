<?php

namespace Ssh521\SimplePosts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => config('simple-posts.validation.title', ['required', 'string', 'max:255']),
            'content' => config('simple-posts.validation.content', ['required', 'string']),
            'date' => config('simple-posts.validation.date', ['required', 'date']),
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '제목을 입력해주세요.',
            'title.max' => '제목은 255자를 초과할 수 없습니다.',
            'content.required' => '내용을 입력해주세요.',
            'date.required' => '날짜를 입력해주세요.',
            'date.date' => '올바른 날짜 형식을 입력해주세요.',
        ];
    }
}