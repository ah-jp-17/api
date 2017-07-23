<?php
/**
 * Created by PhpStorm.
 * User: Shwetank
 * Date: 22-Jul-17
 * Time: 17:08
 */

require_once '../include/DbOperation.php';
require '../vendor/autoload.php';
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

$app = new Slim\App;

function echoResponse($value, $response) {
    return $response->withJson($value);
}

$app->post('/sendLocation', function(ServerRequestInterface $request, ResponseInterface $response_object) {

    $response = [];

    $db = new DBOperation();
    $data =  $request->getParsedBody();
    $result = $db->sendLocation($data);

    if($result == 0) {
        $response['success'] = true;
        $response['message'] = 'Location sent';
        return echoResponse($response, $response_object);
    } elseif ($result == 1 ) {
        $response['success'] = false;
        $response['message'] = 'Location sending failed';
        return echoResponse($response, $response_object);
    } else {
        $response['success'] = false;
        $response['message'] = 'Something went wrong !';
        return echoResponse($response, $response_object);
    }
});

$app->get('/getLocation', function(ServerRequestInterface $request, ResponseInterface $response_object) use ($app) {

    $response = [];

    $db = new DBOperation();
    $data =  $request->getQueryParams();
    $result = $db->getLocation($data);
    $response['success'] = true;
    $response['message'] = $result;
    return echoResponse($response, $response_object);

});

$app->post('/locationPermit', function(ServerRequestInterface $request, ResponseInterface $response_object) use ($app) {

    $response = [];

    $db = new DBOperation();
    $data =  $request->getParsedBody();
    $result = $db->checkPermit($data);

    if($result == False) {
        $response['success'] = False;
        $response['message'] = 'Failed to set permission';
        return echoResponse($response, $response_object);
    } else {
        $response['success'] = true;
        $response['message'] = 'Saved permission';
        return echoResponse($response, $response_object);
    }

});

$app->post('/fetchLocationHistory', function(ServerRequestInterface $request, ResponseInterface $response_object) use ($app) {

    $response = [];

    $db = new DBOperation();
    $data =  $request->getParsedBody();
    $result = $db->fetchLocationHistory($data);

    if($result == False) {
        $response['success'] = False;
        $response['message'] = $result;
        return echoResponse($response, $response_object);
    } else {
        $response['success'] = true;
        $response['message'] = $result;
        return echoResponse($response, $response_object);
    }

});

$app->post('/registerGcm', function(ServerRequestInterface $request, ResponseInterface $response_object) use ($app) {

    $response = [];

    $db = new DBOperation();
    $data =  $request->getParsedBody();
    $result = $db->registerGcm($data);

    if($result == False) {
        $response['success'] = False;
        $response['message'] = 'Failed to register GCM';
        return echoResponse($response, $response_object);
    } else {
        $response['success'] = true;
        $response['message'] = 'Registered GCM';
        return echoResponse($response, $response_object);
    }

});


$app->run();