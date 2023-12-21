<?php

    use BizSolution\BizPlasGate\Core\Route as Route;

    Route::post('send-otp', 'SMSController@send_otp');
    Route::post('verify-otp', 'SMSController@verify_otp');
    Route::get('test-api', 'SMSController@test_api');