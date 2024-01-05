<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\home\ProductController as HomeProductController;
use App\Http\Controllers\home\CategoryController as HomeCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('/brands' , BrandController::class);
Route::apiResource('/categories' , CategoryController::class);
Route::apiResource('/attributes', AttributeController::class);
Route::apiResource('/products',ProductController::class);
Route::apiResource('/banners',BannerController::class);
route::apiResource('/home/faqs',FaqController::class);
// route::put('/home/faqs/{faq}/edit',[FaqController::class ,'update']);



Route::get('ctegory-attributes/{category}', [CategoryController::class , 'getCategoryAttribute']);

Route::get('categories-products/{category:slug}',[HomeCategoryController::class , 'show']);
Route::get('home/products',[HomeProductController::class , 'index']);
Route::get('home/products/{product}',[HomeProductController::class , 'show']);



Route::post('/register' ,[AuthController::class , 'register']);
Route::post('/login' ,[AuthController::class , 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [UserController::class, 'me'])->middleware('auth:sanctum');
