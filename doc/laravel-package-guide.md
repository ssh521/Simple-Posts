# Laravel 패키지 만들기

이 문서는 `ssh521/simple-posts` 패키지를 예시로 Laravel 패키지를 처음부터 만드는 방법을 설명합니다.

---

## 1. 디렉토리 구조

```
my-package/
├── composer.json
├── src/
│   ├── MyServiceProvider.php
│   ├── Models/
│   │   └── MyModel.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── MyController.php
│   │   └── Requests/
│   │       └── MyRequest.php
├── routes/
│   └── web.php
├── resources/
│   └── views/
│       └── index.blade.php
├── database/
│   └── migrations/
│       └── 2024_01_01_000000_create_my_table.php
└── config/
    └── my-package.php
```

---

## 2. composer.json

패키지의 핵심 설정 파일입니다.

```json
{
    "name": "vendor/my-package",
    "description": "패키지 설명",
    "type": "library",
    "license": "MIT",
    "autoload": {
        "psr-4": {
            "Vendor\\MyPackage\\": "src/"
        }
    },
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0|^13.0"
    },
    "extra": {
        "laravel": {
            "providers": [
                "Vendor\\MyPackage\\MyServiceProvider"
            ]
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}
```

**핵심 포인트:**
- `extra.laravel.providers` 에 서비스 프로바이더를 등록하면 Laravel이 자동으로 감지(auto-discovery)합니다.
- `autoload.psr-4` 의 네임스페이스와 `src/` 디렉토리를 맞춰야 합니다.

---

## 3. 서비스 프로바이더

서비스 프로바이더는 패키지의 진입점입니다. 라우트, 뷰, 마이그레이션, 설정 등을 Laravel에 등록합니다.

```php
<?php

namespace Vendor\MyPackage;

use Illuminate\Support\ServiceProvider;

class MyServiceProvider extends ServiceProvider
{
    public function register()
    {
        // config 병합 (패키지 기본값 + 사용자 설정)
        $this->mergeConfigFrom(__DIR__.'/../config/my-package.php', 'my-package');
    }

    public function boot()
    {
        // 라우트 등록
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // 뷰 등록 (뷰 네임스페이스: my-package::index)
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'my-package');

        // 마이그레이션 자동 로드
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // artisan 명령어로 실행될 때만 publish 등록
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/my-package'),
            ], 'my-package-views');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'my-package-migrations');

            $this->publishes([
                __DIR__.'/../config/my-package.php' => config_path('my-package.php'),
            ], 'my-package-config');
        }
    }
}
```

---

## 4. 라우트

```php
<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Vendor\MyPackage\Http\Controllers\MyController;

Route::prefix(config('my-package.route.prefix', 'my-route'))
    ->name(config('my-package.route.name', 'my-route.'))
    ->middleware(config('my-package.route.middleware', []))
    ->group(function () {
        Route::get('/', [MyController::class, 'index'])->name('index');
        Route::get('/create', [MyController::class, 'create'])->name('create');
        Route::post('/', [MyController::class, 'store'])->name('store');
        Route::get('/{item}', [MyController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [MyController::class, 'edit'])->name('edit');
        Route::put('/{item}', [MyController::class, 'update'])->name('update');
        Route::delete('/{item}', [MyController::class, 'destroy'])->name('destroy');
    });
```

`config()` 를 통해 prefix, name, middleware를 사용자가 설정 파일로 변경할 수 있게 합니다.

---

## 5. 설정 파일

```php
<?php
// config/my-package.php

return [
    'route' => [
        'prefix'     => 'my-route',
        'name'       => 'my-route.',
        'middleware' => [],
    ],

    'auto_load_migrations' => true,
];
```

---

## 6. 모델

```php
<?php

namespace Vendor\MyPackage\Models;

use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    protected $table = 'my_table';

    protected $fillable = ['title', 'content'];
}
```

---

## 7. 컨트롤러

```php
<?php

namespace Vendor\MyPackage\Http\Controllers;

use Illuminate\Routing\Controller;
use Vendor\MyPackage\Models\MyModel;
use Vendor\MyPackage\Http\Requests\MyRequest;

class MyController extends Controller
{
    public function index()
    {
        $items = MyModel::latest()->paginate(10);
        return view('my-package::index', compact('items'));
    }

    public function create()
    {
        return view('my-package::create');
    }

    public function store(MyRequest $request)
    {
        MyModel::create($request->validated());
        return redirect()->route('my-route.index');
    }

    public function show(MyModel $item)
    {
        return view('my-package::show', compact('item'));
    }

    public function edit(MyModel $item)
    {
        return view('my-package::edit', compact('item'));
    }

    public function update(MyRequest $request, MyModel $item)
    {
        $item->update($request->validated());
        return redirect()->route('my-route.index');
    }

    public function destroy(MyModel $item)
    {
        $item->delete();
        return redirect()->route('my-route.index');
    }
}
```

---

## 8. Form Request (유효성 검사)

```php
<?php

namespace Vendor\MyPackage\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => '제목을 입력해주세요.',
            'content.required' => '내용을 입력해주세요.',
        ];
    }
}
```

---

## 9. 마이그레이션

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('my_table', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('my_table');
    }
};
```

---

## 10. 로컬 테스트 방법

패키지를 Packagist에 배포하기 전에 로컬에서 테스트하는 방법입니다.

### 테스트용 Laravel 프로젝트 생성

```bash
composer create-project laravel/laravel test-app
cd test-app
```

### composer.json 에 로컬 경로 저장소 추가

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../my-package"
        }
    ],
    "require": {
        "vendor/my-package": "dev-main"
    }
}
```

```bash
# lock 파일 삭제 후 설치
rm -f composer.lock
composer update
php artisan migrate

# 라우트 확인
php artisan route:list
```

---

## 11. Packagist 배포

1. GitHub에 저장소 생성 후 push
2. [packagist.org](https://packagist.org) 에 GitHub 저장소 URL 등록
3. GitHub Webhook 설정으로 자동 업데이트 연동

배포 후 설치:
```bash
composer require vendor/my-package
php artisan migrate
```

---

## 참고: publish 명령어

```bash
# 뷰 커스터마이징
php artisan vendor:publish --tag=my-package-views

# 마이그레이션 커스터마이징
php artisan vendor:publish --tag=my-package-migrations

# 설정 파일 커스터마이징
php artisan vendor:publish --tag=my-package-config
```
