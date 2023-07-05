<?php

use Illuminate\Support\Facades\Route;

// 首页
Route::get('/', [App\Http\Controllers\Index::class, 'index']);

Route::group(['prefix' => 'analysis'], function (){
    // 视频解析控制器
    Route::post('/', [App\Http\Controllers\Analysis::class, 'index']);
    // 视频解析结果
    Route::get('/result/{id}', [App\Http\Controllers\Analysis::class, 'result']);
    // 下载视频
    Route::get('/download/{id}', [App\Http\Controllers\Analysis::class, 'download']);
});
