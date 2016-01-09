<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['prefix' => 'nian','middleware' => ['web']], function () {
    Route::auth();
    Route::get('/admin', 'AdminController@index');
    Route::post('/admin/canche_code', 'AdminController@cancheCode');
    Route::get('/ExpiryId/{fid}', 'AdminController@cancheWithQr')->where('fid','^[0-9]*$');
    Route::post('/admin/canche_fid','AdminController@cancheFid');
    //活动首页路由
    Route::get('/','IndexController@startIndex');
    Route::get('/nianshou','IndexController@index');
    //用户分享链接
    Route::get('/sharelink/{parentid}','ShareController@ShareLink');
    //用户奖品页
    Route::get('/myprize','PrizeController@myPrize');
    //奖品兑换页
    Route::get('/exprize','PrizeController@exPrize');
    Route::post('/exprize/exchange', 'PrizeController@getExchange');
    //攻击和分享ajax
    Route::post('/attack','AttackController@getAttack');
    Route::post('/share','ShareController@main');
});