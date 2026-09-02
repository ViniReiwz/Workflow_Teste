<?php

use App\Http\Controllers\LivrosController;
use App\Http\Controllers\EmprestimoController;
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

 Route::middleware(['web'])->prefix('emprestimos')->name('emprestimos.')->controller(EmprestimoController::class)->group( function() {

    Route::get('/', 'index')->name('index');
    Route::get('/fromUser', 'showUserEmprestimos')->name('fromUser');
    Route::get('/create', 'showCreateForm')->name('showCreateForm');
    Route::post('/create', 'create')->name('create');
    Route::delete('/delete/{emprestimo_id}', 'delete')->name('delete');
    Route::get('/search-book', 'searchBook')->name('search-book');
    Route::get('/busca', 'searchBook')->name('busca');

 });
 
