<?php

Route::get('/index',function(){
	   return view('pages.index');
});

Route::post('auth/register','Users_Registration@postRegister');

Route::post('auth/login','Users_Login@postLogin' );

Route::get('logout','Users_Logout@index' );

Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/home',function(){
        return view('pages.home');
    });
    
    Route::get('/expenses',function(){
	   return view('pages.expenses');
    });
    
    Route::get('/about',function(){
        return view('pages.about');    
    });
    
    Route::get('/expenses',function(){
        return view('pages.expenses');
    });

    Route::get('/liquidation',function(){
        return view('pages.liquidation');
    });
    Route::get('/receipts',function(){
        return view('pages.receipts');
    });
    
    Route::get('/calendar',function(){
        return view('pages.calendar');
    });

    Route::get('/psite',function(){
        return view('pages.receipts.psite');
    });
    Route::get('/cicct_days',function(){
        return view('pages.receipts.cicct_days');
    });
    
    Route::get('/graduation',function(){
        return view('pages.receipts.graduation');
    });
    
    
    
});



Route::get('/test','FineReaderController@sendImage');
