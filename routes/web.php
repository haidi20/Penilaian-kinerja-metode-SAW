<?php
Route::get('/',function(){
  // return redirect()->route('dashboard');
  return redirect()->route('login');
});
Route::group(['middleware' => 'auth'],function(){
  Route::get('dashboard',function(){
    session()->put('aktif','dashboard');
    session()->put('aktiff','');
    return view('dashboard.index');
  })->name('dashboard');
  // route untuk keperluan input data dan ajax //
  Route::get('data/warga','DataController@dataWarga')->name('data.warga');
  Route::get('data/input','DataController@index')->name('input.index');

  // route untuk keperluan fitur" topsis //
  Route::group(['prefix' => 'topsis','namespace' => 'Topsis'],function(){
    Route::get('pembagi','PembagiController@index')->name('topsis.pembagi.index');
    Route::get('terbobot','TerbobotController@index')->name('topsis.terbobot.index');
    Route::get('peringkat','PeringkatController@index')->name('topsis.peringkat.index');
    Route::get('pebantu/delta','PembantuController@delta')->name('topsis.pembantu.delta');
    Route::get('pembantu/alpha','PembantuController@alpha')->name('topsis.pembantu.alpha');
    Route::get('normalisasi','NormalisasiController@index')->name('topsis.normalisasi.index');
  });
  // route untuk keperluan fitur" di saw //
  Route::resource('warga','WargaController');
  Route::resource('kreteria','KreteriaController');
  Route::resource('alternatif','AlternatifController');
  Route::get('analisa','AnalisaController@index')->name('analisa.index');
  Route::get('kinerja','KinerjaController@index')->name('kinerja.index');
  Route::get('normalisasi','NormalisasiController@index')->name('normalisasi.index');
});

//auth laravel
Auth::routes();
