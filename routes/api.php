<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\EnsureUserIsTeacher;
use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\api\CourseCommentController;
use App\Http\Controllers\api\CourseContentController;

Route::post('/register', [AuthController::class, 'register']); //pass
Route::post('/login', [AuthController::class, 'login']); //pass
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); //pass


Route::middleware(['auth:sanctum'])->group(function () {


    Route::get('/user', [UserController::class, 'index'])->middleware(EnsureUserIsTeacher::class); //pass
    Route::post('/user', [UserController::class, 'store'])->middleware(EnsureUserIsTeacher::class); //pass
    Route::get('/user/{id}', [UserController::class, 'show'])->middleware(EnsureUserIsTeacher::class); //pass
    Route::put('/user/{id}', [UserController::class, 'update'])->middleware(EnsureUserIsTeacher::class); //pass
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->middleware(EnsureUserIsTeacher::class); //pass


    Route::get('/courses', [CourseController::class, 'index']); //pass
    Route::post('/courses', [CourseController::class, 'store'])->middleware(EnsureUserIsTeacher::class); //pass
    Route::get('/mycourses', [CourseController::class, 'myCourses']); //pass
    Route::put('/courses/{course_id}', [CourseController::class, 'update'])->middleware(EnsureUserIsTeacher::class); //pass
    Route::get('/courses/{course_id}', [CourseController::class, 'show']); //pass
    Route::post('/courses/{course_id}/enroll', [CourseController::class, 'enroll']); //pass, tidak bisa enroll jika pernah terdaftar di course yang sama, dan tidak bisa enroll ketika penuh
    Route::get('/courses/{id}/analytics', [CourseController::class, 'getAnalytics']); //pass

    Route::get('/courses/{course_id}/contents', [CourseContentController::class, 'index']); //pass
    Route::get('/courses/{course_id}/contents/{content_id}', [CourseContentController::class, 'show']); //pass

    Route::get('/contents/{content_id}/comments', [CourseCommentController::class, 'index']); //pass
    Route::post('/contents/{content_id}/comments', [CourseCommentController::class, 'store']); //pass
    Route::delete('/comments/{comment_id}', [CourseCommentController::class, 'destroy']); //pass

    Route::get('/categories', [CategoryController::class, 'index']); //pass
    Route::post('/categories', [CategoryController::class, 'store'])->middleware(EnsureUserIsTeacher::class); //pass
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware(EnsureUserIsTeacher::class);//pass

    Route::post('courses/{courseId}/feedback', [FeedbackController::class, 'store']); //pass
    Route::get('courses/{courseId}/feedback', [FeedbackController::class, 'index']); //pass
    Route::put('feedback/{feedbackId}', [FeedbackController::class, 'update']); //pass, hanya bisa edit untuk user yang membuat saja
    Route::delete('feedback/{feedbackId}', [FeedbackController::class, 'destroy']); //pass, hanya bisa hapus untuk user yang membuat saja

    Route::post('courses/{courseId}/announcement', [AnnouncementController::class, 'store'])->middleware(EnsureUserIsTeacher::class); //pass
    Route::get('courses/{courseId}/announcement', [AnnouncementController::class, 'index']); //pass
    Route::put('announcement/{announcementId}', [AnnouncementController::class, 'update'])->middleware(EnsureUserIsTeacher::class); //pass
    Route::delete('announcement/{announcementId}', [AnnouncementController::class, 'destroy'])->middleware(EnsureUserIsTeacher::class); //pass
});