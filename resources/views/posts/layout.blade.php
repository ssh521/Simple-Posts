<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '간단한 게시판')</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- toastui-editor -->
    <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css" />

    <!-- 커스텀 스타일 -->
    <style>
        /* Toast UI Editor 미리보기 이미지 스타일 */
        .toastui-editor-md-preview img {
            max-width: 100% !important;
            height: auto !important;
            display: block !important;
            margin: 10px 0 !important;
        }
        
        /* 이미지 로딩 문제 해결 */
        .toastui-editor-md-preview img[src] {
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* 에디터 영역 스타일 */
        .toastui-editor-defaultUI {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }
    </style>

    <script>
        function initializeEditor(editorElement, initialValue = '') {
            return new toastui.Editor({
                el: editorElement,
                height: '600px',
                initialEditType: 'markdown',
                previewStyle: 'vertical',
                initialValue: initialValue,
                toolbarItems: [
                    ['heading', 'bold', 'italic', 'strike'],
                    ['hr', 'quote'],
                    ['ul', 'ol', 'task', 'indent', 'outdent'],
                    ['table', 'image', 'link'],
                    ['code', 'codeblock']
                ],
                usageStatistics: false,
                viewer: true,
                previewHighlight: false,
                customHTMLRenderer: {
                    image(node, context) {
                        const { destination, title } = node;
                        return {
                            type: 'openTag',
                            tagName: 'img',
                            attributes: {
                                src: destination,
                                alt: node.firstChild ? node.firstChild.literal : '',
                                title: title || '',
                                style: 'max-width: 100%; height: auto;'
                            }
                        };
                    }
                },
                hooks: {
                    addImageBlobHook(blob, callback) {
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
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success && data.url) {
                                callback(data.url, data.filename || 'uploaded-image');
                            } else {
                                alert('이미지 업로드 실패: ' + (data.message || '알 수 없는 오류'));
                            }
                        })
                        .catch(error => {
                            alert('이미지 업로드 중 오류가 발생했습니다: ' + error.message);
                        });
                    }
                }
            });
        }

        function setupFormSubmission(editor, formElement, contentInput) {
            formElement.addEventListener('submit', function(e) {
                const content = editor.getMarkdown();
                contentInput.value = content;
                
                if (!content.trim()) {
                    e.preventDefault();
                    alert('내용을 입력해주세요.');
                    return false;
                }
            });

            editor.on('change', function() {
                contentInput.value = editor.getMarkdown();
            });
        }
    </script>

</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">간단한 게시판</h1>
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>