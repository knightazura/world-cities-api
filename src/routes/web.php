<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return "What makes you come here?";
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('/', function () {
        return "What makes you come here?";
    });

    $router->get('/cities', function () {
        /**
         * Query String
         * limit: number
         * offset: number
         * namePrefix: string
         */
        $limit  = request()->get('limit');
        $offset = request()->get('offset');
        $namePrefix = request()->get('namePrefix');

        $subCountQuery = "SELECT count(city) AS 'total' FROM cities WHERE city_ascii LIKE '$namePrefix%' OR city LIKE '$namePrefix%'";
        $query = "SELECT city, country FROM cities WHERE city_ascii LIKE '$namePrefix%' OR city LIKE '$namePrefix%'";

        if (isset($offset) && !is_null($offset) && $offset > 1) {
            $query = $query . " LIMIT " . $offset * $limit . ", $limit";
        }

        $sub_query_result = app('db')->select($subCountQuery);
        $query_result = app('db')->select($query);

        return [
            'data' => $query_result,
            'metadata' => [
                'currentOffset' => (int)$offset,
                'totalCount' => $sub_query_result[0]->total
            ]
        ];
    });
});
