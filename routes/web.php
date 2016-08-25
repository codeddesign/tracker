<?php

Route::group(['prefix' => '/track', 'middleware' => 'pixel'], function() {
    Route::get('/visit', 'TrackerController@visit');

    Route::get('/', 'TrackerController@cache');
});

Route::get('/', function () {
    return view('index');
});
