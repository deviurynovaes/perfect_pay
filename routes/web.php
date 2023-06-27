<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

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

Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
Route::post('/finalizar-pagamento', [InvoiceController::class, 'createInvoice'])->name('invoice.create');
Route::get('/buscar-cliente', [InvoiceController::class, 'findClientByEmail'])->name('invoice.find');
Route::get('/obrigado', [InvoiceController::class, 'success'])->name('invoice.success');
