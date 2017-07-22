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

$app = new Slim\App;

function echoResponse($status_code, $response) {

    global $app;

    //Setting Http response code
    $app->status($status_code);

    //setting response content type to json
    $app->contentType('application/json');

    //displaying the response in json format
    echo json_encode($response);
}

$app->post('/sendLocation', function(ServerRequestInterface $request) {

    $response = [];

    $db = new DBOperation();
    $data =  $request->getQueryParams();
    $result = $db->sendLocation($data);

    if($result == 0) {
        $response['success'] = true;
        $response['message'] = 'Location Send';
        echoResponse(200, $response);
    } elseif ($result == 1 ) {
        $response['success'] = false;
        $response['message'] = 'Oops! Error occured';
        echoResponse(200, $response);
    } else {
        $response['success'] = false;
        $response['message'] = 'Something went wrong !';
        echoResponse(200, $response);
    }
});

$app->get('/getLocation', function(ServerRequestInterface $request) use ($app) {

//    $response = [];

    $db = new DBOperation();
    $data =  $request->getQueryParams();
    $result = $db->getLocation($data);

    if($result == 0) {
        $response['success'] = true;
        $response['message'] = 'Got Location';
        echoResponse(200, $response);
    } elseif ($result == 1) {
        $response['success'] = false;
        $response['message'] = 'Oops ! Error occured';
        echoResponse(200, $response);
    } else {
        $response['success'] = false;
        $response['message'] = 'Something went wrong !';
        echoResponse(200, $response);
    }

});

$app->post('/locationPermit', function(ServerRequestInterface $request) use ($app) {

    $response = [];

    $db = new DBOperation();
    $data =  $request->getQueryParams();
    $result = $db->checkpermit($data);

    if($result == 0) {
        $response['success'] = true;
        $response['message'] = 'Location History';
        echoResponse(200, $response);
    } elseif ($result == 1) {
        $response['success'] = false;
        $response['message'] = 'Oops ! Error occured';
        echoResponse(200, $response);
    } else {
        $response['success'] = false;
        $response['message'] = 'Something went wrong !';
        echoResponse(200, $response);
    }

});

$app->post('/locationHistory', function(ServerRequestInterface $request) use ($app) {

    $response = [];

    $db = new DBOperation();
    $data =  $request->getQueryParams();
    $result = $db->fetchLocationHistory($data);

    if($result == 0) {
        $response['success'] = true;
        $response['message'] = 'Location History';
        echoResponse(200, $response);
    } elseif ($result == 1) {
        $response['success'] = false;
        $response['message'] = 'Oops ! Error occured';
        echoResponse(200, $response);
    } else {
        $response['success'] = false;
        $response['message'] = 'Something went wrong !';
        echoResponse(200, $response);
    }

});


$app->run();