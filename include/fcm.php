<?php

function sendNotification($registrationId, $body, $isNotification) {
    $API_ACCESS_KEY = 'AAAAesPc-d8:APA91bEGVnH5E9V0ssjt3Te85F8p2cul3VJO0kL9i1qQq7wkfr5MUmDbj5A1e2IUuJBfvXCXuAjm5co9vOV9Hd8um-DTIr25Fox6fdI3bS0SpwgJT8mjLZEK080OwQEC_h-biasTk5gB';
    $fields = array();
    if ($isNotification) {
        $msg = array
            (
                'body'  => $body.' viewed your profile',
                'title' => 'Location history accessed',
                'sound' => 'default'
            );
        $fields = array
            (
                'to'            => $registrationId,
                'notification'  => $msg,
                'priority'      => 'high',
                'time_to_live'  => 30
            );
    } else {
        $fields = array
            (
                'to'            => $registrationId,
                'data'          => $body,
                'priority'      => 'high',
                'time_to_live'  => 30
            );
    }


    $headers = array
        (
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
        );
    #Send Reponse To FireBase Server  
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    return $result;
}

?>