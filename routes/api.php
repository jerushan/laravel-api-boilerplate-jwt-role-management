<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth', 'middleware' => ['cors', 'localization']], function(Router $api) {
        $api->post('signup', 'App\Api\V1\Controllers\SignUpController@signUp');
        $api->post('login', 'App\Api\V1\Controllers\LoginController@login');

        $api->post('recovery', 'App\Api\V1\Controllers\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\Api\V1\Controllers\ResetPasswordController@resetPassword');

        $api->post('logout', 'App\Api\V1\Controllers\LogoutController@logout');
        $api->post('refresh', 'App\Api\V1\Controllers\RefreshController@refresh');
        $api->get('me', 'App\Api\V1\Controllers\UserController@me');
    });

    $api->group(['middleware' => 'jwt.auth', 'cors', 'localization'], function(Router $api) {
        $api->group(['middleware' => 'role:developer', ], function(Router $api) {
            $api->post('createRole', 'App\Api\V1\Controllers\Developer\RoleController@store');
            $api->post('viewRole', 'App\Api\V1\Controllers\Developer\RoleController@index');
            $api->post('updateRole', 'App\Api\V1\Controllers\Developer\RoleController@update');
            
            $api->post('assignRole', 'App\Api\V1\Controllers\Developer\RoleController@assignRole');
            $api->post('detachRole', 'App\Api\V1\Controllers\Developer\RoleController@detachRole');
        });

        

        $api->get('protected', function() {
            return response()->json([
                'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
});
