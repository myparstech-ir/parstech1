<?php
use App\Http\Controllers\CategoryController;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\SaleAjaxController;
use App\Http\Controllers\Api\CategoryApiController;
use Illuminate\Support\Facades\Route;

Route::get('/categories/list', [CategoryController::class, 'list']);
Route::get('/customers/search', function(Request $request) {
    $q = $request->get('q');
    $results = Person::query()
        ->where('name', 'LIKE', "%$q%")
        ->orWhere('company_name', 'LIKE', "%$q%")
        ->limit(10)
        ->get(['id', 'name']);
    return response()->json($results);
});

Route::get('/sales/latest', [SaleAjaxController::class, 'latest']);
Route::get('/invoices/{id}', [SaleAjaxController::class, 'show']);
Route::post('/invoices', [SaleAjaxController::class, 'store']);
Route::put('/invoices/{id}', [SaleAjaxController::class, 'update']);
Route::get('/categories/list', [CategoryController::class, 'list']);
Route::get('/categories', [CategoryApiController::class, 'index']);
Route::get('/categories/product-list', [CategoryApiController::class, 'productList']);
