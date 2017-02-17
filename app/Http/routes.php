<?php

Route::get('/', function () {
    return 'Failure mapping system for TAKAOKA';
});

// Press Client
Route::group(['prefix' => 'press', 'namespace' => 'Press'], function () {
    Route::group(['prefix' => 'client', 'namespace' => 'Client'], function () {
        Route::group(['prefix' => 'inspection'], function () {
            Route::get('initial', 'InspectionController@initial');
            Route::get('figure/{pn}', 'InspectionController@getFigure');
            Route::get('controlNum', 'InspectionController@getControlNum');
            Route::post('save/failure', 'InspectionController@saveFailure');
            Route::post('save/modification', 'InspectionController@saveModification');
            Route::get('result/{controlNum}', 'InspectionController@result');



            Route::post('update', 'InspectionController@update');
            Route::post('delete', 'InspectionController@delete');
        });
    });
});

// Return React App
Route::group(['prefix' => 'press'], function () {
    Route::group(['prefix' => 'manager'], function () {
        Route::get('dashboard', 'PagesController@index');
        Route::get('mapping', 'PagesController@index');
        Route::get('reference', 'PagesController@index');
        Route::get('report', 'PagesController@index');
        Route::get('contact', 'PagesController@index');
    });

    Route::group(['prefix' => 'maintenance'], function () {
        Route::get('worker', 'PagesController@index');
        Route::get('failure', 'PagesController@index');
        Route::get('part', 'PagesController@index');
    });
});



Route::group(['prefix' => 'press/manager', 'namespace' => 'Press\Manager'], function () {
    // Maintenance
    Route::group(['prefix' => 'maintenance'], function () {
        Route::get('failure', 'ShowController@index');
        Route::get('modification', 'ShowController@index');
        Route::get('holeModification', 'ShowController@index');
        Route::get('hole', 'ShowController@index');
        Route::get('inline', 'ShowController@index');
    });

    Route::get('report/check/{date}', 'ReportController@check');
    Route::get('report/export/{process}/{inspection}/{line}/{part}/{date}/{choku}', 'ReportController@export');
});
