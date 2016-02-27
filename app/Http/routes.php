<?php


Route::post('auth/login',['uses' => 'Users_Login@postLogin' ]);

Route::get('/home1',['middleware'=>'auth',function(){
   return view('pages.home');
}]);
   
Route::post('auth/register','Users_Registration@postRegister');

Route::get('/index',function(){
	return view('pages.index');
});
/*Route::get('/home1',function(){
	return view('pages.home');
});*/


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
// Route::get('/login',function(){
// 	return view('pages.login');
// });

Route::get('/test','FineReaderController@sendImage');
