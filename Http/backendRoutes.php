<?php

use Illuminate\Routing\Router;
/** @var Router $router */

use Spatie\Analytics\Period;

$router->group(['prefix' =>'/ganalytic'], function (Router $router) {
// append

    $router->get('ganalytics', [
        'as' => 'admin.ganalytic.index',
        'uses' => 'GanalyticController@index',
        'middleware' => 'can:ganalytic.ganalytics.index'
    ]);

    $router->get('ganalytics/{status}', [
        'as' => 'admin.ganalytic.show',
        'uses' => 'GanalyticController@show',
        'middleware' => 'can:ganalytic.ganalytics.show'
    ]);

    $router->post('ganalytics', [
        'as' => 'admin.ganalytic.store',
        'uses' => 'GanalyticController@store',
        'middleware' => 'can:ganalytic.ganalytics.store'
    ]);

    $router->get('ganalytics/{id}',function(){



    });

	$router->get('',function(){

		$startDate = new \Carbon\Carbon('first day of this month');
        $endDate = new \Carbon\Carbon('last day of this month');

        $period = Period::create($startDate,$endDate);
        
        $response = Analytics::performQuery(
            $period,
            'ga:users,ga:newUsers,ga:pageviews,ga:bounceRate'
        );



        return collect($response['rows'] ?? [])->map(function (array $items) {
            return [
                'visitors' => (int) $items[0],
                'pageViews' => (int) $items[1],
            ];
        });
	});
});
