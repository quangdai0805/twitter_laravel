<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use \App\Http\Controllers\TestController;
use \App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QdtestController;
use \App\Http\Controllers\TwitterController;
Route::get('/', function () {
    return view('main');
});
Route::get('/test', function () {
    return view('layout');
});

// Route::get('test', [\App\Http\Controllers\TestController::class, 'index']);

//Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::get('login', [TestController::class, 'index'])->name('login');
// Route::post('login', [TestController::class, 'create'])->name('login');
//Route::resource('index',TestController::class);
// Route::get('/login', 'LoginController@showLoginForm')->name('login');
// Route::post('/login', 'LoginController@login');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Route::get('accounts', [AccountController::class, 'index'])->name('accounts');

Route::resource('accounts', AccountController::class);

Route::get('someMethod', [AccountController::class, 'someMethod'])->name('someMethod');

Route::delete('/delete-selected', [AccountController::class, 'deleteSelected'])->name('deleteSelected');

Route::delete('deleteSelected', [AccountController::class, 'deleteSelected'])->name('deleteSelected');
// Route::get('test', [AccountController::class, 'test'])->name('test');
// Route::get('deleteSelected', [AccountController::class, 'deleteSelected'])->name('deleteSelected');
// Route::middleware('auth')->group(function () {
//     Route::resource('accounts', AccountController::class);
// });

//Route::post('test', [LoginController::class, 'showMainView'])->name('test');;
// Route::post('process-number', [LoginController::class, 'showMainView']);
// Route::get('process-number', [LoginController::class, 'processNumber']);
Route::post('login-selected-accounts', [TwitterController::class, 'loginSelectedAccounts'])->name('login.selected.accounts');
Route::get('LoginAccount', [TwitterController::class, 'LoginAccount'])->name('LoginAccount');

Route::get('CheckProxy', [TwitterController::class, 'CheckProxy'])->name('CheckProxy');
Route::post('LikePost', [TwitterController::class, 'LikePost'])->name('LikePost');
Route::get('CreateRetweet', [TwitterController::class, 'CreateRetweet'])->name('CreateRetweet');

Route::get('main', [LoginController::class, 'showMainView'])->name('main');
Route::post('testt', [LoginController::class, 'testt'])->name('testt');
Route::get('x_guest_token', [LoginController::class, 'x_guest_token'])->name('x_guest_token');
Route::get('testProxy', [LoginController::class, 'testProxy'])->name('testProxy');

Route::get('qd', [QdtestController::class, 'qd'])->name('qd');