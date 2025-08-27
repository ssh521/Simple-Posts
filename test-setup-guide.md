# Simple Posts 패키지 로컬 테스트 가이드

## 방법 1: 심볼릭 링크 사용 (권장)

### 1단계: simple-posts 다운로드
```bash
git clone https://github.com/ssh521/Simple-Posts.git
```

### 2단계: 테스트용 Laravel 프로젝트 생성
```bash
composer create-project laravel/laravel simple-posts-app
cd simple-posts-app
```

### 3단계: composer.json 수정
테스트 프로젝트 simple-posts-app 에서 `composer.json`에 다음을 추가:

```json
{

    "require": {
        "ssh521/simple-posts": "dev-main"
    },

    "minimum-stability": "dev",
    "prefer-stable": true,
    "repositories": [
        {
            "type": "path",
            "url": "../Simple-Posts",
            "options": {
                "symlink": true
            }
        }
    ]
}
```

### 3단계: 패키지 update
```
composer update
```

### 4단계: 자동 업데이트
```
composer dump-autoload
```

### 5단계: 설정 파일 테스트

#### 설정 파일 퍼블리싱
```bash
php artisan vendor:publish --tag=simple-posts-config
```

#### 뷰 파일 커스터마이징
```bash
php artisan vendor:publish --tag=simple-posts-views
```

#### 마이그레이션 파일 커스터마이징
```bash
php artisan vendor:publish --tag=simple-posts-migrations
```

#### 라우트 파일 퍼블리싱
```bash
php artisan vendor:publish --tag=simple-posts-routes
```

### 6. 기본 기능 테스트
1. 게시글 목록 조회 (`/posts`)
2. 새 게시글 작성 (`/posts/create`)
3. 게시글 상세보기 (`/posts/{id}`)
4. 게시글 수정 (`/posts/{id}/edit`)
5. 게시글 삭제

### 7. 로그 확인
```bash
tail -f storage/logs/laravel.log
```

### 라우트 캐시 클리어
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
```