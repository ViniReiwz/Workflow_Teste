<?php

use App\Http\Controllers\LivrosController;
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
    return view('welcome');
});

// Permite usar Gate::check('user')na view 404
Route::fallback(function(){
    return view('errors.404');
 });

 Route::middleware(['web'])->prefix('livros')->name('livros.')->controller(LivrosController::class)->group( function() {

    Route::get('/', 'index')->name('index');
    Route::get('/edit/{isbn}', 'edit')->name('edit');
    Route::post('/create', 'store')->name('create');
    Route::post('/update', 'store')->name('update');
    Route::delete('/delete/{isbn}', 'delete')->name('delete');

 });
 
