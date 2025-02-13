<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use \App\Http\Controllers\TestController;
use \App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QdtestController;
use \App\Http\Controllers\TwitterController;
use App\Http\Controllers\UnlockController;
use App\Http\Controllers\ProxyController;

Route::get('/home', function () {
    return view('main');
});

Route::get('/check-proxy', [ProxyController::class, 'checkProxy']);


Route::get('/run-python-script', [TestController::class, 'runPythonScript']);
Route::get('/unlock-account', [UnlockController::class, 'unlockAccount']);


Route::get('', [LoginController::class, 'showMainView'])->name('main');

Route::get('user', [LoginController::class, 'user'])->name('user');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);


Route::get('register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('register', [LoginController::class, 'register']);


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
Route::post('login-selected-accounts', [TwitterController::class, 'LoginAccount'])->name('login.selected.accounts');
Route::post('LoginAccount', [TwitterController::class, 'LoginAccount'])->name('LoginAccount');

Route::get('CheckProxy', [TwitterController::class, 'CheckProxy'])->name('CheckProxy');

Route::post('testttt', [TwitterController::class, 'test']);

Route::post('LikePost', [TwitterController::class, 'LikePost'])->name('LikePost');
Route::post('comment-post', [TwitterController::class, 'CommentPost'])->name('comment-post');
Route::get('CreateRetweet', [TwitterController::class, 'CreateRetweet']);


Route::middleware('throttle:200,1')->group(function () {
    Route::get('check_x', [TwitterController::class, 'check_x'])->name('check_x');
});
