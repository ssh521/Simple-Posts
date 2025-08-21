# Simple Posts 패키지 로컬 테스트 가이드

## 방법 1: 심볼릭 링크 사용 (권장)

### 1단계: 테스트용 Laravel 프로젝트 생성
```bash
composer create-project laravel/laravel test-simple-posts
cd test-simple-posts
```

### 2단계: composer.json 수정
테스트 프로젝트의 `composer.json`에 다음을 추가:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../Simple-Posts"
        }
    ],
    "require": {
        "ssh521/simple-posts": "dev-main"
    }
}
```

### 3단계: 패키지 설치
```bash
composer require ssh521/simple-posts:dev-main
```

### 4단계: 데이터베이스 설정
`.env` 파일에서 데이터베이스 설정:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

또는 MySQL 사용:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_simple_posts
DB_USERNAME=root
DB_PASSWORD=
```

### 5단계: 마이그레이션 실행
```bash
php artisan migrate
```

### 6단계: 라우트 확인
```bash
php artisan route:list | grep posts
```

### 7단계: 서버 실행 및 테스트
```bash
php artisan serve
```

브라우저에서 `http://localhost:8000/posts` 접속

## 방법 2: 직접 복사 방법

### vendor 디렉토리에 직접 복사
```bash
# 테스트 프로젝트에서
mkdir -p vendor/ssh521/simple-posts
cp -r /path/to/Simple-Posts/* vendor/ssh521/simple-posts/
composer dump-autoload
```

## 방법 3: Packagist 없이 Git 저장소 사용

### composer.json에 Git 저장소 추가
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/your-username/simple-posts"
        }
    ]
}
```

## 테스트 시나리오

### 기본 기능 테스트
1. 게시글 목록 조회 (`/posts`)
2. 새 게시글 작성 (`/posts/create`)
3. 게시글 상세보기 (`/posts/{id}`)
4. 게시글 수정 (`/posts/{id}/edit`)
5. 게시글 삭제

### 설정 파일 테스트
```bash
# 설정 파일 퍼블리싱
php artisan vendor:publish --tag=simple-posts-config

# 뷰 파일 퍼블리싱
php artisan vendor:publish --tag=simple-posts-views

# 마이그레이션 파일 퍼블리싱
php artisan vendor:publish --tag=simple-posts-migrations
```

### 오류 처리 테스트
1. 존재하지 않는 게시글 접근
2. 잘못된 데이터 입력
3. 데이터베이스 연결 오류 시뮬레이션

## 디버깅 팁

### 로그 확인
```bash
tail -f storage/logs/laravel.log
```

### 라우트 캐시 클리어
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

### Composer 오토로드 갱신
```bash
composer dump-autoload
```
