<?php

Route::group(['prefix' => '/track', 'middleware' => 'cors'], function() {
    Route::get('/visit', 'TrackerController@visit')
        ->middleware('track-pixel');

    Route::get('/unique', 'TrackerController@unique');
});

Route::get('/', function () {
    return view('index');
});
