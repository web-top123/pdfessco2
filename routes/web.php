<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GlobalTimeoutController;
use App\Http\Controllers\TimeoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoriesMenuController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\MoveToController;
use App\Http\Controllers\CopyToController;
use App\Http\Controllers\AdminSearchController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\ExplodeController;
use App\Http\Controllers\CategoriesListController;


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

Route::get('admin/categories/list', function () {
    return \App\Models\Category::withCount('files')
    ->withDepth()
    ->orderBy('name', 'asc')
    ->get()
    ->toFlatTree();
})->middleware('auth');
Route::get('admin/categories/cards', function () {
    return \App\Models\Category::where('parent_id', null)
    ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name")
    ->withCount('files')
    ->withCount('children')
    ->withDepth()
    ->get()
    ->toTree();
})->middleware('auth');

Route::get('admin/categories/{id}', function ($id) {
    $category = \App\Models\Category::withDepth()
    ->with('files')
    ->with(['children' => function ($q){
        $q->withCount('files')
        ->withCount('children')
        ->withDepth()
        ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name");
    }])
    ->withCount('files')
    ->withCount('children')
    ->withDepth()
    ->findOrFail($id);
    $category->files = $category->files->map( function ($file) {

        // $counter = new \App\Services\PdfCounter;
        // $file['count'] = $counter->count($file);
        return $file;
    });

    return $category;
})->middleware('auth');

Route::get('page-count/{id}', [DocumentController::class,'index']);



Route::get('/', function () {
    return view('welcome');
})->middleware('guest');
Route::view('about', 'presentation.about');
Route::view('contact-us', 'presentation.contact');
Route::post('contact-us', [ContactController::class,'store']);
Route::post('timeout/global', [GlobalTimeoutController::class,'update']);
Route::post('timeout/user/update', [TimeoutController::class,'update']);
Route::post('timeout/user/delete', [TimeoutController::class,'delete']);
Route::get('timeout/data/admins', [TimeoutController::class,'dataAdmins']);
Route::get('timeout/data/users', [TimeoutController::class,'dataUsers']);

Auth::routes();
Route::post('admin/file', [FilesController::class,'store']);
Route::middleware('auth')->group(function () {

    Route::get('profile', [UserController::class,'index']);
    Route::patch('update', [UserController::class,'update']);
    Route::patch('update/password', [UserController::class,'reset']);
    Route::get('catperm', [ManageController::class,'categoryPermissions']);
    Route::post('editperms', [ManageController::class,'changeCategoryPermissions']);
    Route::prefix('dashboard')->group(function () {

        Route::get('/', [DashboardController::class,'index']);
        Route::get('files', [FilesController::class,'index']);
        Route::get('page-count/{id}', [DocumentController::class,'index']);
        Route::post('create', [DocumentController::class,'store']);
        Route::get('categories', [CategoriesMenuController::class,'index']);
        Route::post('email', function () {
            \Mail::to(request()->to)->send(new \App\Mail\TestMail(request()->url, request()->subject));
            return json_encode([ 'success' => true ]);
        });

    });

    Route::prefix('admin')->middleware('can:manage')->group(function() {

        Route::get('/', [ManageController::class,'index']);

        Route::prefix('categories')->group(function() {

            //Route::get('{id}', [CategoriesController::class,'index']);
            // Route::get('cards', [CategoriesCardsController::class,'index']);
            // Route::get('list', [CategoriesListController::class,'index']);
            Route::post('delete', [CategoriesController::class,'delete']);
            Route::patch('{id}', [CategoriesController::class,'update']);
            Route::post('/', [CategoriesController::class,'store']);
            Route::patch('move', [CategoriesController::class,'move']);
            Route::patch('moveCat', [CategoriesController::class,'moveCat']);
            Route::patch('moveUp', [CategoriesController::class,'moveUp']);

        });

        Route::prefix('move-to')->group(function() {
            Route::patch('/', [MoveToController::class,'update']);
            Route::post('list', [MoveToController::class,'index']);
        });
        Route::prefix('copy-to')->group(function() {
            Route::patch('/', [CopyToController::class,'update']);
            Route::post('list', [CopyToController::class,'index']);
        });

        Route::patch('file/{id}', [FilesController::class,'update']);

        Route::get('search', [AdminSearchController::class,'index']);

        Route::get('manage-users', [UsersController::class,'index']);
        Route::get('manage-users/data', [UsersController::class,'data']);

        Route::post('user', [UserController::class,'create']);
        Route::patch('user/{id}', [UserController::class,'edit']);
        Route::delete('user', [UserController::class,'delete']);

        Route::get('manage-admins', [AdminsController::class,'index']);
        Route::get('manage-admins/data', [AdminsController::class,'data']);
        Route::post('user/{admin}', [UserController::class,'create']);
       

        Route::post('explode', [ExplodeController::class,'index']);
    });
});
