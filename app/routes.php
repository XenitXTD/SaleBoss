<?php

Route::group(["namespace" => "Controllers"], function(){

    /**
     * Front page
     */
    Route::get("/", "HomeController@getIndex");

    /**
     * Pages that need not to be accessed when
     * User is logged in the app
     *
     * @see \SaleBoss\Filters\SimpleAccessFilter
     */
    Route::group(['before' => 'guest'],function(){
        Route::get('auth/login', "AuthController@getLogin");
        Route::post("auth/login","AuthController@postLogin");
        Route::get("auth/register","AuthController@getRegister");
        Route::post("auth/register","AuthController@postRegister");
    });

    /**
     * Pages that need to be accessed only
     * when user is logged in the app
     *
     * @see \SaleBoss\Filters\SimpleAccessFilter
     */
    Route::group(['before' => ['auth', 'Notification']],function(){
        Route::get('dash','UserController@getDash');
        Route::get('auth/logout','AuthController@getLogout');
	    Route::resource('menu','MenuController',['except' => ['show','index']]);
	    Route::resource('menu_type','MenuTypeController',['except' => 'show']);
	    Route::get('menu_type/{id}','MenuController@index');
	    Route::resource('groups','GroupController', ['except' => 'show']);
	    Route::resource('users','UserController');
	    Route::resource('permissions','PermissionController');
	    Route::resource('states','StateController');
        Route::put('leads/take/{id}','LeadController@leadPicker');
        Route::put('leads/locker/{id}','LeadController@lockerUpdate');
        Route::get('leads/locker/{id}','LeadController@lockerEdit');
        Route::delete('leads/locker/{id}','LeadController@lockerRelease');
        Route::get('leads/bulk','LeadImporterController@create');
        Route::post('leads/bulk','LeadImporterController@store');
        Route::resource('leads','LeadController', array('except' => array('show')));
        Route::get('leads/all','LeadController@leadsAll');
        Route::post('leads/user/{user_id}/time', 'LeadController@userLeadsWithTime');
        Route::get('leads/user/{user_id}/time', 'LeadController@userLeadsWithTime');
        Route::get('leads/user/{user_id}', 'LeadController@usersLeads');
        Route::get("me/edit","UserController@profileEdit");
        Route::put("me/edit","UserController@profileUpdate");
	    Route::get('me/leads','MyLeadsController@index');
	    Route::post('me/leads','MyLeadsController@store');
	    Route::get('me/leads/unreads', array('as' => 'LeadsUnreads', 'uses' =>'LeadController@NotReads'));
	    Route::delete('me/leads/{lead_id}','MyLeadsController@destroy');
        Route::put('me/leads/{lead_id}','MyLeadsController@update');
        Route::get('stats/whole','StatsController@whole');
        Route::get('stats/user/{user_id}','StatsController@users');
        Route::get('task', array('as' => 'TaskIndex', 'uses' =>'TaskController@index'));
        Route::get('task/create','TaskController@create');
        Route::put('task/create','TaskController@store');
        Route::get('task/{task_id}','TaskController@show');
        Route::put('task/{task_id}','TaskController@messageUpdate');
        Route::put('task/action/{task_id}','TaskController@action');
        Route::get('task/edit/{task_id}','TaskController@edit');
        Route::put('task/edit/{task_id}','TaskController@edit');
        Route::get('folder', array('as' => 'FolderIndex', 'uses' =>'FolderController@index'));
        Route::put('folder/create', array('as' => 'FolderCreate', 'uses' =>'FolderController@store'));
        Route::put('folder/edit/{id}', 'FolderController@update');
        Route::get('folder/edit/{id}', 'FolderController@edit');
        Route::get('folder/delete/{id}', 'FolderController@delete');
        Route::post('folder/item/search', array('as' => 'FolderItemSearchList', 'uses' =>'FolderController@search'));
        Route::get('folder/item/search', array('as' => 'FolderItemSearchList', 'uses' =>'FolderController@search'));
        Route::get('folder/item/create', array('as' => 'FolderItemCreate', 'uses' =>'FolderController@itemCreate'));
        Route::put('folder/item/create', 'FolderController@itemStore');
        Route::get('folder/{id}', array('as' => 'FolderLettersList', 'uses' =>'FolderController@lettersList'));
        Route::get('folder/{id}/items', array('as' => 'FolderItemsList', 'uses' =>'FolderController@itemsList'));
        Route::get('folder/item/{id}', array('as' => 'FolderItem', 'uses' =>'FolderController@itemShow'));
        Route::get('me/letters', array('as' => 'MyLetterList', 'uses' =>'LetterController@index'));
        Route::post('letters/search', array('as' => 'LetterSearchList', 'uses' =>'LetterController@indexSearch'));
        Route::get('letters/search', array('as' => 'LetterSearchList', 'uses' =>'LetterController@indexSearch'));
        Route::get('me/letters/out', array('as' => 'MyLetterSentList', 'uses' =>'LetterController@indexSent'));
        Route::get('letters/create', array('as' => 'LetterCreate', 'uses' =>'LetterController@create'));
        Route::put('letters/create', array('as' => 'LetterCreate', 'uses' =>'LetterController@store'));
        Route::get('letters/{id}/to/{to_id}', 'LetterController@show');
        Route::put('letters/action/{letter_id}','LetterController@action');
    });
});

/**
 * Pages that are Opilo Specific
 *
 * @see SlaeBoss\Controllers\Opilo namespace
 */
Route::group(["namespace" => 'Controllers\Opilo'],function(){
    Route::group(['before' => 'auth'],function() {
        Route::resource('customers','CustomerController');
        Route::get('my/customers','CustomerController@myIndex');
	    Route::get('orders/create/{customer_id}','OrderController@create');
        Route::post('orders/sale/{customer_id}','OrderController@store');
        Route::get('my/orders','OrderController@myIndex');
        Route::get('orders/{id}','OrderController@show');
        Route::get('orders/sale/{id}/edit','OrderController@edit');
        Route::put('orders/sale/{id}','OrderController@update');
        Route::get('orders','OrderController@index');
	    Route::put('orders/accounter_approve/{id}','OrderController@accounterUpdate');
	    Route::put('orders/suspend/{id}','OrderController@suspendUpdate');
	    Route::put('orders/support_approve/{id}','OrderController@supporterUpdate');
    });
});
//
//Event::listen('illuminate.query', function($sql, $bindings, $time){
//        echo $sql.'<br>';          // select * from my_table where id=?
//        print_r($bindings); // Array ( [0] => 4 )
//        echo $time.'<br>';         // 0.58
//
//        // To get the full sql query with bindings inserted
//        $sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
//        $full_sql = vsprintf($sql, $bindings);
//        Log::info($full_sql);
//    });

