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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

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

        /* 전체화면 관련 스타일 */
        .toastui-editor-full-screen {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100vh !important;
            z-index: 9999;
            background: white;
        }

        .fullscreen {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: 22px !important;
            height: 22px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: none !important;
            border: none !important;
            cursor: pointer;
            vertical-align: middle;
            position: relative;
            top: 1px;
        }

        .fullscreen i {
            font-size: 14px;
            color: #333;
            width: auto;
            height: auto;
            line-height: 1;
            position: relative;
            top: -1px;
        }

        .fullscreen:hover i {
            color: #4b4b4b;
        }
    </style>

    <script>
        const createFullscreenButton = (toggleCallback) => {
            const button = document.createElement('button');
            Object.assign(button, {
                className: 'toastui-editor-toolbar-icons fullscreen',
                title: '전체화면',
                innerHTML: '<i class="fas fa-expand"></i>',
                style: { backgroundImage: 'none', margin: '0' },
                onclick: (e) => {
                    e.preventDefault();
                    toggleCallback();
                }
            });
            return button;
        };

        const handleFullscreen = (editor, editorElement) => {
            const editorEl = editorElement.parentElement;
            if (!editorEl) return;

            const isFullscreen = editorEl.classList.contains('toastui-editor-full-screen');
            const newHeight = isFullscreen ? '600px' : '100vh';
            editor.setHeight(newHeight);
            editorEl.classList.toggle('toastui-editor-full-screen');
            document.body.style.overflow = isFullscreen ? '' : 'hidden';

            const button = editorElement.querySelector('.fullscreen i');
            if (button) {
                button.className = isFullscreen ? 'fas fa-expand' : 'fas fa-compress';
            }
        };

        function initializeEditor(editorElement, initialValue = '') {
            const editor = new toastui.Editor({
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
                    ['code', 'codeblock'],
                    [{
                        el: createFullscreenButton(() => handleFullscreen(editor, editorElement)),
                        tooltip: '전체화면',
                        name: 'fullscreen'
                    }]
                ],
                usageStatistics: false,
                viewer: true,
                previewHighlight: false,
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