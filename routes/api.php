<?php

Route::post('auth', 'Auth\LoginController');

Route::group(['middleware' => 'jwt'], function () {
    Route::get('currency/convert', 'CurrencyConvertController');
    Route::get('currency/convert/history', 'CurrencyConvertHistoryController');
});
