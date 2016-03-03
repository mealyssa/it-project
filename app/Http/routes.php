<?php

Route::get('/',function(){
	   return view('pages.index');
});

Route::post('auth/register','Account_Controller@postRegister');

Route::post('auth/signIn','Account_Controller@postSignIn' );

Route::get('logout','Account_Controller@logout' );
Route::post('home/uploadReceipts','ReceiptsController@upload');

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
    Route::get('/organization',function(){
        return view('pages.organization');
    });
    
    
    
});



Route::get('/test','FineReaderController@sendImage');
