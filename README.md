# Simple Posts - Laravel 게시판 패키지

간단한 게시글 CRUD 기능을 제공하는 Laravel 패키지입니다.

## ✨ 기능

- 📝 게시글 생성, 조회, 수정, 삭제 (CRUD)
- 📅 제목, 내용, 날짜 필드 지원
- 🎨 Bootstrap 기반 반응형 UI
- ⚙️ 설정 가능한 테이블명, 라우트, 페이지네이션
- 🛡️ 포괄적인 예외 처리 및 로깅
- 🌐 한국어 인터페이스

## 📦 설치

```bash
composer require ssh521/simple-posts
```

## 🚀 빠른 시작

### 1. 마이그레이션 실행
```bash
php artisan migrate
```

### 2. 라우트 등록
`routes/web.php` 파일에 다음 라우트를 추가하세요:

```php
use Ssh521\SimplePosts\Http\Controllers\PostController;

Route::prefix('posts')
    ->name('posts.')
    ->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{post}', [PostController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });
```

또는 라우트 파일을 퍼블리싱하여 사용할 수 있습니다:
```bash
php artisan vendor:publish --tag=simple-posts-routes
```
그 후 `routes/simple-posts.php` 파일을 `routes/web.php`에서 포함시키세요.

### 3. 브라우저에서 접속
```
http://your-domain.com/posts
```

## ⚙️ 설정

### 설정 파일 퍼블리싱
```bash
php artisan vendor:publish --tag=simple-posts-config
```

### 뷰 파일 커스터마이징
```bash
php artisan vendor:publish --tag=simple-posts-views
```

### 마이그레이션 파일 커스터마이징
```bash
php artisan vendor:publish --tag=simple-posts-migrations
```

### 라우트 파일 퍼블리싱
```bash
php artisan vendor:publish --tag=simple-posts-routes
```

## 🧪 로컬 테스트

자동 설정 스크립트 실행:
```bash
./setup-test.sh
```

또는 수동 설정은 `test-setup-guide.md` 파일을 참고하세요.

## 📁 패키지 구조

```
Simple-Posts/
├── config/simple-posts.php          # 설정 파일
├── database/migrations/              # 데이터베이스 마이그레이션
├── resources/views/posts/            # Blade 템플릿
├── src/
│   ├── Http/
│   │   ├── Controllers/PostController.php
│   │   ├── Models/Post.php
│   │   ├── Requests/PostRequest.php
│   │   └── routes/web.php
│   └── SimplePostsServiceProvider.php
└── composer.json
```

## 🔧 설정 옵션

`config/simple-posts.php`에서 다음 옵션들을 설정할 수 있습니다:

- 테이블명
- 라우트 prefix 및 middleware
- 페이지네이션 설정
- 날짜 형식
- 유효성 검사 규칙
- 뷰 테마

## 📝 라이센스

MIT License

