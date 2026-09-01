<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, DashboardController, PatientController, DoctorController, AppointmentController, MedicalRecordController, UserController, AvailabilityController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn() => redirect()->route('dashboard'));
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']); });
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/account', [UserController::class, 'account'])->name('account.edit');
    Route::put('/account', [UserController::class, 'updateAccount'])->name('account.update');
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('patients', PatientController::class);
        Route::resource('doctors', DoctorController::class); });
    Route::middleware('role:admin,doctor,patient')->group(function () {
        Route::resource('appointments', AppointmentController::class);
        Route::post('/appointments/{appointment}/start', [AppointmentController::class, 'start'])->name('appointments.start');
        Route::get('/appointments/{appointment}/medical-record/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
        Route::post('/appointments/{appointment}/medical-record', [MedicalRecordController::class, 'store'])->name('medical-records.store'); });
    Route::middleware('role:doctor')->group(function () {
        Route::get('/doctor/patients', [PatientController::class, 'doctorPatients'])->name('doctors.patients.index');
        Route::get('/doctor/patients/{patient}', [PatientController::class, 'doctorPatient'])->name('doctors.patients.show'); });
    Route::middleware('role:doctor')->group(function () {
        Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
        Route::post('/availability', [AvailabilityController::class, 'store'])->name('availability.store');
        Route::delete('/availability/{availability}', [AvailabilityController::class, 'destroy'])->name('availability.destroy'); });
});