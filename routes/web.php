<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomersReports;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Invoices_Report;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/login', function () {
    return view('auth.login');
});


Route::middleware('auth')->group(function () {

    // main page

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // invoices routes 

    Route::resource('/invoices', InvoiceController::class)->middleware('permission:قائمة الفواتير');
    Route::get('/delete_invoice/{invoice}', [InvoiceController::class, 'destroy'])->middleware('permission:حذف الفاتورة');
    Route::get('/archive_invoice/{invoice}', [InvoiceController::class, 'archive'])->middleware('permission:ارشفة الفاتورة');
    Route::get('/restore_invoice/{invoice}', [InvoiceController::class, 'restore_invoice'])->middleware('permission:الفواتير');
    Route::get('/Print_invoice/{invoice}', [InvoiceController::class, 'print'])->middleware('permission:طباعةالفاتورة');
    Route::get('/export_invoices', [InvoiceController::class, 'export'])->middleware('permission:تصدير EXCEL');

    Route::post('satus_update/{id}', [InvoiceController::class, 'status_update'])->name('Status_Update')->middleware('permission:تغير حالة الدفع');

    Route::get('/paid_invoices', [InvoiceController::class, 'paid'])->middleware('permission:الفواتير المدفوعة');
    Route::get('/unpaid_invoices', [InvoiceController::class, 'unpaid'])->middleware('permission:الفواتير الغير مدفوعة');
    Route::get('/partialPaid_invoices', [InvoiceController::class, 'partial_paid'])->middleware('permission:الفواتير المدفوعة جزئيا');
    Route::get('/archived_invoices', [InvoiceController::class, 'archived_invoices'])->middleware('permission:ارشيف الفواتير');

    // invoice details

    Route::get('/InvoicesDetails/{id}', [InvoicesDetailsController::class, 'show'])->middleware('permission:الفواتير');
    Route::post('/AddMoreAttachments', [InvoiceAttachmentsController::class, 'store'])->middleware('permission:اضافة مرفق');
    Route::get('delete_file/{id_attachments}', [InvoicesDetailsController::class, 'destroy'])->middleware('permission:حذف المرفق');

    Route::get('/get_products_by_section', [InvoiceController::class, 'getproducts'])->middleware('permission:المنتجات');

    // settings

    Route::resource('/sections', SectionController::class)->middleware('permission:الاقسام');

    Route::resource('/products', ProductController::class)->middleware('permission:المنتجات');

    Route::resource('roles', RoleController::class)->middleware('permission:صلاحيات المستخدمين');
    Route::resource('users', UserController::class)->middleware('permission:قائمة المستخدمين');
    Route::get('delete_user/{id}', [UserController::class, 'destroy'])->middleware('permission:حذف مستخدم');

    // Reports

    Route::get('invoices_report', [Invoices_Report::class, 'index'])->middleware('permission:تقرير الفواتير');
    Route::post('Search_invoices', [Invoices_Report::class, 'Search_invoices'])->middleware('permission:تقرير الفواتير');

    Route::get('customers_report', [CustomersReports::class, 'index'])->middleware('permission:تقرير العملاء');
    Route::post('Search_customers', [CustomersReports::class, 'Search_customers'])->middleware('permission:تقرير العملاء');

    // notifications

    Route::post('markAsRead', [InvoiceController::class, 'markNotificationsAsRead']);
});

Route::get('/{page}', [AdminController::class, 'index']);

require __DIR__ . '/auth.php';
