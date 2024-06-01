<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BadgeController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CSRDataController;
use App\Http\Controllers\Admin\CsrManagementController;
use App\Http\Controllers\Admin\DistributeDataController;
use App\Http\Controllers\Admin\ImportDataController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\OrientationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCourseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin', 'revalidate'], 'as' => 'admin.'], function(){

    Route::controller(IndexController::class)->group(function(){
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('csr-dashboard/{id?}', 'adminViewCsrDashboard')->name('csr.dashboard');
        Route::get('workshops/{id?}', 'workshops')->name('index.workshops');
    });

    Route::controller(ProfileController::class)->group(function(){
        Route::put('update-password','updatePassword')->name('update.password');
        Route::put('update-profile','updateProfile')->name('update.profile');
        Route::get('profile','profile')->name('profile');
    });

    Route::controller(AdminController::class)->group(function(){
        Route::get('hold-students', 'index')->name('hold.students');
        Route::get('confirm-student/{id?}/{confirmationType?}', 'confirmStudent')->name('confirm.student');
        // Route::get('-dashboard', 'superAdminDashboard')->name('superadmin.dashboard');
    });

    Route::controller(ImportDataController::class)->group(function(){
        Route::get('imported-data', 'index')->name('index.import-data');
        Route::post('import-data', 'importData')->name('store.import-data');
        Route::get('duplicate-data', 'duplicateData')->name('duplicate.import-data');
    });

    Route::controller(DistributeDataController::class)->group(function(){
        Route::get('distribution-records', 'distributionRecord')->name('distribute.record');
        Route::get('distribution', 'index')->name('distribute.index');
        Route::post('distribute-data', 'distribute')->name('distribute');
        Route::post('total-student','getTotalData')->name('count.student');
        Route::get('filter-distribute-data','filterDistributedData')->name('filter.distribute-data');
    });

    Route::controller(CsrManagementController::class)->group(function(){
        Route::get('csrs','index')->name('csr.index');
        Route::post('csr-store','store')->name('csr.store');
        Route::post('csr-edit', 'edit')->name('edit.csr');
        Route::put('csr-update/{id?}', 'update')->name('csr.update');
        Route::get('block-user/{id?}', 'blockUser')->name('user.block');
        Route::get('unblock-user/{id?}', 'UnblockUser')->name('user.unblock');
    });

    Route::controller(CSRDataController::class)->group(function(){
        Route::get('csr-students-data', 'index')->name('csr-data.index');
        Route::get('filter-csr-students-data', 'filterCsrData')->name('csr-data.filter');
        Route::get('csr-student/{id?}', 'showStudent')->name('csr.show.student');
        Route::put('csr-update-student/{id?}', 'updateStudent')->name('csr.update.student');
        Route::get('enroll-form/{id?}', 'enrollForm')->name('csr.enroll-student');
        Route::get('filter-status/{id?}', 'filterActionStatuses')->name('filter.action.status');
        Route::get('filter-status-today/{id?}/{csr?}', 'filterActionStatusesTodayData')->name('filter.action.status.today');
        Route::get('filter-student', 'filterStudent')->name('filter.student');
        Route::get('filter-student-courses/{id?}', 'showStudentCourses')->name('filter.student.courses');
        Route::get('filter-followup/{id?}', 'filterFollowup')->name('filter.followup');
        Route::put('store-remarks/{id?}', 'teamLeadFollowUp')->name('store.remarks');
        Route::get('followupdata-remarks', 'getFollowUpData')->name('data.remarks');
    });

    Route::controller(CourseController::class)->group(function(){
        Route::get('courses', 'index')->name('index.course');
        Route::post('store-course', 'store')->name('store.course');
        Route::post('edit-course', 'edit')->name('edit.course');
        Route::put('update-course/{id?}', 'update')->name('update.course');
        Route::get('course-batches/{id?}', 'courseBatches')->name('course.batch');  
        Route::get('batch-students/{id?}', 'batchesStudent')->name('batch.students');
    });

    Route::controller(BadgeController::class)->group(function(){
        Route::get('batches', 'index')->name('index.batches');
        Route::get('create-batch', 'create')->name('create.batch');
        Route::post('store-batch/{id?}', 'store')->name('store.batch');
        Route::get('edit-batch/{id?}', 'edit')->name('edit.batch');
        Route::put('update-batch/{id?}', 'update')->name('update.batch');
    });

    Route::controller(BankController::class)->group(function(){
        Route::get('banks', 'index')->name('index.bank');
        Route::post('store-bank', 'store')->name('store.bank');
        Route::post('edit-bank', 'edit')->name('edit.bank');
        Route::put('update-bank/{id?}', 'update')->name('update.bank');
    });

    Route::controller(StudentController::class)->group(function(){
        Route::get('students', 'index')->name('index.students');
        Route::get('discontinued-students', 'disContinuedStudent')->name('discontinued.students');
        Route::get('edit-student/{id?}', 'edit')->name('edit.student');
        Route::put('update-student/{id?}', 'update')->name('update.student');
        Route::post("enroll-student/{id?}", 'store')->name('student.store');
        Route::get('show-student/{id?}', 'show')->name('show.student');
        Route::post('make-payment/{id?}', 'makePayment')->name('student.make-payment');
        Route::get('filter-csr-students', 'filterStudent')->name('filter.students');
        Route::post('is-student-enrolled-email', 'isStudentEnrollAlready')->name('check-student');
        Route::post('is-student-enrolled-cnic', 'isStudentEnrollAlreadyCnic')->name('check-student-cnic');
        Route::post('is-student-enrolled-phone', 'isStudentEnrollAlreadyPhone')->name('check-student-phone');
        Route::get('activate-or-deactivate-student/{id?}/{action?}', 'discontinuedCourse')->name('actOrDe.student');
        Route::post('edit-student-payment', 'editPayment')->name('edit.student.payment');
        Route::put('update-student-payment/{id?}', 'updateStudentPayment')->name('update.student.payment');
        Route::post('add-returns', 'addReturns')->name('add.returns');
    });

    Route::controller(StudentCourseController::class)->group(function(){
        Route::post('store-comment', 'addComment')->name('student-course.comment');
        Route::post('get-comments', 'getComments')->name('get.comments');
    });

    Route::controller(OrientationController::class)->group(function(){
        Route::get('orientations', 'index')->name('index.orientations');
        Route::post('store-orientation', 'store')->name('store.orientation');
        Route::post('edit-orientation', 'edit')->name('edit.orientation');
        Route::put('update-orientation/{id?}', 'update')->name('update.orientation');
    });

});


Route::get('accounts', [AccountsController::class, 'index'])->name('index.accounts')->middleware('auth');
Route::get('search-accounts', [AccountsController::class, 'filterPayment'])->name('search.accounts')->middleware('auth');

Route::post('orientation-registration/{id?}', [OrientationController::class, 'register'])->name('register.orientation');

Route::get('artisan/{cmd}',function($cmd){
    Artisan::call("{$cmd}");
    dd(Artisan::output());
});

Auth::routes(['register' => false]);

