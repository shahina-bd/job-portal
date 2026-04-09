<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->user_type === 'employer') {
            return view('employer-dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Shared profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::get('/profile/educations', [ProfileController::class, 'educations']);
    Route::get('/profile/experiences', [ProfileController::class, 'experiences']);
    Route::get('/profile/skills', [ProfileController::class, 'skills']);
    Route::get('/profile/trainings', [ProfileController::class, 'trainings']);
    Route::get('/profile/documents', [ProfileController::class, 'documents']);
    Route::get('/profile/address', [ProfileController::class, 'address']);
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress']);

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
        
        // User management
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::get('/admin/users/{user}', [AdminController::class, 'showUser']);
        Route::patch('/admin/users/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);
        
        // Job management
        Route::get('/admin/jobs', [AdminController::class, 'jobs']);
        Route::get('/admin/jobs/{job}', [AdminController::class, 'showJob']);
        Route::patch('/admin/jobs/{job}', [AdminController::class, 'updateJob']);
        Route::delete('/admin/jobs/{job}', [AdminController::class, 'deleteJob']);
        
        // Application management
        Route::get('/admin/applications', [AdminController::class, 'applications']);
        Route::get('/admin/applications/{application}', [AdminController::class, 'showApplication']);
        Route::patch('/admin/applications/{application}', [AdminController::class, 'updateApplication']);
        
        // Category management
        Route::get('/admin/categories', [AdminController::class, 'categories']);
        Route::post('/admin/categories', [AdminController::class, 'storeCategory']);
        Route::patch('/admin/categories/{category}', [AdminController::class, 'updateCategory']);
        Route::delete('/admin/categories/{category}', [AdminController::class, 'deleteCategory']);
        
        // Country management
        Route::get('/admin/countries', [AdminController::class, 'countries']);
        Route::post('/admin/countries', [AdminController::class, 'storeCountry']);
        Route::patch('/admin/countries/{country}', [AdminController::class, 'updateCountry']);
        Route::delete('/admin/countries/{country}', [AdminController::class, 'deleteCountry']);
        
        // Analytics
        Route::get('/admin/analytics', [AdminController::class, 'analytics']);
    });

    // Employer routes
    Route::middleware('role:employer')->group(function () {
        Route::resource('jobs', JobPostController::class);
        Route::post('/company', [CompanyController::class, 'store']);
        Route::get('/company', [CompanyController::class, 'show']);
        Route::patch('/company', [CompanyController::class, 'update']);
        Route::get('/profile/company', [ProfileController::class, 'company']);
        Route::get('/profile/jobs', [ProfileController::class, 'jobs']);
    });

    // Employee routes
    Route::middleware('role:employee')->group(function () {
        Route::post('/education', [EducationController::class, 'store']);
        Route::get('/education/{educations}', [EducationController::class, 'show']);
        Route::patch('/education/{educations}', [EducationController::class, 'update']);
        Route::delete('/education/{educations}', [EducationController::class, 'destroy']);
        
        Route::post('/experience', [ExperienceController::class, 'store']);
        Route::get('/experience/{experiences}', [ExperienceController::class, 'show']);
        Route::patch('/experience/{experiences}', [ExperienceController::class, 'update']);
        Route::delete('/experience/{experiences}', [ExperienceController::class, 'destroy']);
        
        Route::post('/skills', [SkillController::class, 'store']);
        Route::get('/skills/{skills}', [SkillController::class, 'show']);
        Route::patch('/skills/{skills}', [SkillController::class, 'update']);
        Route::delete('/skills/{skills}', [SkillController::class, 'destroy']);
        
        Route::get('/jobs', [JobPostController::class, 'index']);
        Route::get('/jobs/{job}', [JobPostController::class, 'show']);
        Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store']);
        Route::get('/profile/applications', [ProfileController::class, 'applications']);
    });

    // Shared routes
    Route::get('/applications/{application}', [JobApplicationController::class, 'show']);
    Route::delete('/applications/{application}', [JobApplicationController::class, 'destroy']);
});
