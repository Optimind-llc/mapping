<?php

Route::get('/', function () {
    return 'Failure mapping system for TAKAOKA';
});

// Press Client
Route::group(['prefix' => 'press', 'namespace' => 'Press'], function () {
    Route::group(['prefix' => 'client', 'namespace' => 'Client'], function () {
        Route::group(['prefix' => 'inspection'], function () {
            Route::get('initial', 'InspectionController@initial');
            Route::get('controlNum', 'InspectionController@getControlNum');
            Route::post('check', 'InspectionController@check');
            Route::post('save/failure', 'InspectionController@saveForFailure');

            Route::get('toBeModificated', 'InspectionController@toBeModificated');
            Route::post('save/modification', 'InspectionController@saveForModification');
            Route::post('history/modification', 'InspectionController@modificationHistory');

            Route::post('update', 'InspectionController@update');
            Route::post('clear/controlNum', 'InspectionController@clearControlNum');


            // Route::get('figure/{pn}', 'InspectionController@getFigure');
            // Route::get('result/{controlNum}', 'InspectionController@result');
            // Route::post('delete', 'InspectionController@delete');
        });

        Route::group(['prefix' => 'memo'], function () {
            Route::get('list-old', 'MemoController@listOld');
            Route::get('list', 'MemoController@list');
            Route::post('initial', 'MemoController@initial');
            Route::post('save', 'MemoController@save');

            Route::post('history', 'MemoController@history');
            Route::post('update', 'MemoController@update');
        });

        Route::group(['prefix' => 'print'], function () {
            Route::post('upload', 'PrintController@saveImg');
        });
    });
});

// Return React App
Route::group(['prefix' => 'press'], function () {
    Route::group(['prefix' => 'manager'], function () {
        Route::get('dashboard-test', 'PagesController@index');
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
    Route::get('initial', 'InitialController@all');
    Route::post('report/check', 'ReportController@check');

    Route::post('mapping', 'MappingController@getData');

    Route::get('reference/failureTypes', 'ReferenceController@allfailureTypes');
    Route::post('reference/search/inspection', 'ReferenceController@serchInspection');
    Route::post('reference/search/memo', 'ReferenceController@serchMemo');

    Route::get('report/export/{line}/{date}/{choku}', 'ReportController@export');
});











