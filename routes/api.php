<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\Addcontroller;
use App\Http\Controllers\API\Sellscontroller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    //route vers le user
    Route::post('user/create',[UserController::class,'create']);
    Route::post('user/update',[UserController::class,'update']);
    Route::get('user/details',[UserController::class,'details']);
    Route::get('user/search',[UserController::class,'search']);

    //routepour le produit
    Route::post('product/create',[ProductController::class,'create']);
    Route::get('product/details',[ProductController::class,'details']);
    Route::get('product/search',[ProductController::class,'search']);
    Route::post('product/update',[ProductController::class,'update']);

    //route vers Addproduct
    Route::post('ajout/create',[Addcontroller::class,'create']);

    //route vers la sortie 
    Route::post('sells/create',[Sellscontroller::class,'create']);

});
// route pour les controllers user
    Route::post('user/login',[UserController::class,'login']);
    
   
  



   


  
