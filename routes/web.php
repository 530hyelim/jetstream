<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/items', function () {
        return view('items');
    })->name('items');
});

/*
    [sanctum]
    - spa, 모바일 애플리케이션과 간단한 토큰 기반의 api를 위한 경량 인증 시스템 제공
    - 사용자가 자신의 계정에 대해 여러개의 api 토큰을 생성할 수 있음
    - 토큰이 수행할 수 있는 권한/범위가 부여될 수 있음

    제트스트림 없이 sanctum만 활용해서 인증 시스템 구현 가능!!

    [jetstream 설치 과정]
    1. composer create-project laravel/laravel <프로젝트명>
    2. create database <데이터베이스명>
    3. env 파일에서 db 정보수정
    4. composer require laravel/jetstream
    5. php artisan jetstream:install livewire
    6. php artisan migrate & php artisan serve
*/