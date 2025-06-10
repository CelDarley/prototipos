<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Models\Menu;

Route::get('/', function () {
    $parentMenus = Menu::with('children')->whereNull('parent_id')->get();
    return view('home', compact('parentMenus'));
})->name('home');

Route::prefix('menus')->group(function () {
    Route::get('/manage', [MenuController::class, 'manage'])->name('menus.manage');
    Route::get('/image/{id}', [MenuController::class, 'showImage'])->name('menus.image');
    Route::post('/', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    Route::get('/manage-links', [MenuController::class, 'manageLinks'])->name('menus.manage-links');
    Route::post('/reorder', [MenuController::class, 'reorder'])->name('menus.reorder');
});
