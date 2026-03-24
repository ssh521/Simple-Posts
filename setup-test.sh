#!/bin/bash

# Simple Posts 패키지 로컬 테스트 설정 스크립트

echo "🚀 Simple Posts 패키지 테스트 환경 설정을 시작합니다..."

# 현재 디렉토리 확인
PACKAGE_DIR=$(pwd)
echo "📁 패키지 경로: $PACKAGE_DIR"

# 테스트 프로젝트 디렉토리 설정
TEST_DIR="../test-simple-posts"

# 이미 테스트 프로젝트가 있는지 확인
if [ -d "$TEST_DIR" ]; then
    echo "⚠️  테스트 프로젝트가 이미 존재합니다. 삭제하고 새로 만들까요? (y/n)"
    read -r answer
    if [ "$answer" = "y" ]; then
        rm -rf "$TEST_DIR"
        echo "🗑️  기존 테스트 프로젝트를 삭제했습니다."
    else
        echo "❌ 설정을 중단합니다."
        exit 1
    fi
fi

# Laravel 프로젝트 생성
echo "📦 새로운 Laravel 프로젝트를 생성합니다..."
cd ..
composer create-project laravel/laravel test-simple-posts

# 테스트 프로젝트로 이동
cd test-simple-posts

# composer.json 백업
cp composer.json composer.json.backup

# composer.json 수정
echo "📝 composer.json을 수정합니다..."
cat > composer_temp.json << 'EOF'
{
    "name": "laravel/laravel",
    "type": "project",
    "description": "Test project for Simple Posts package",
    "keywords": ["framework", "laravel"],
    "license": "MIT",
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0|^13.0",
        "ssh521/simple-posts": "dev-main"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.13",
        "laravel/sail": "^1.26",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.0",
        "phpunit/phpunit": "^11.0.1"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force"
        ],
        "post-root-package-install": [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "post-create-project-cmd": [
            "@php artisan key:generate --ansi",
            "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
            "@php artisan migrate --graceful --ansi"
        ]
    },
    "extra": {
        "laravel": {
            "dont-discover": []
        }
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true,
            "php-http/discovery": true
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true,
    "repositories": [
        {
            "type": "path",
            "url": "../Simple-Posts"
        }
    ]
}
EOF

mv composer_temp.json composer.json
rm -f composer.lock

# SQLite 데이터베이스 파일 생성
echo "🗄️  SQLite 데이터베이스를 설정합니다..."
touch database/database.sqlite

# .env 파일 수정
echo "⚙️  .env 파일을 수정합니다..."
sed -i.bak 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
sed -i.bak 's/DB_HOST=127.0.0.1/#DB_HOST=127.0.0.1/' .env
sed -i.bak 's/DB_PORT=3306/#DB_PORT=3306/' .env
sed -i.bak 's/DB_DATABASE=laravel/DB_DATABASE=' .env
sed -i.bak 's/DB_USERNAME=root/#DB_USERNAME=root/' .env
sed -i.bak 's/DB_PASSWORD=/#DB_PASSWORD=/' .env

# 절대 경로로 데이터베이스 경로 설정
echo "DB_DATABASE=$(pwd)/database/database.sqlite" >> .env

# 패키지 설치
echo "📦 패키지를 설치합니다..."
composer update

# 마이그레이션 실행
echo "🏗️  마이그레이션을 실행합니다..."
php artisan migrate

# 라우트 확인
echo "🛣️  등록된 라우트를 확인합니다..."
php artisan route:list --name=posts

echo ""
echo "✅ 설정이 완료되었습니다!"
echo ""
echo "🚀 테스트를 시작하려면:"
echo "   cd test-simple-posts"
echo "   php artisan serve"
echo ""
echo "🌐 브라우저에서 다음 URL에 접속하세요:"
echo "   http://localhost:8000/posts"
echo ""
echo "📋 추가 명령어:"
echo "   php artisan vendor:publish --tag=simple-posts-config"
echo "   php artisan vendor:publish --tag=simple-posts-views"
echo "   php artisan vendor:publish --tag=simple-posts-migrations"
echo ""
