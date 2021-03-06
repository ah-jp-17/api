<?php
/**
 * Created by PhpStorm.
 * User: Shwetank
 * Date: 22-Jul-17
 * Time: 16:57
 */

include_once dirname(__FILE__) . '/fcm.php';

class DBOperation {

    private $con;

    function __construct() {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/DBConnect.php';

        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();

        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }

    public function sendLocation($data) {
        $query = $this->con->prepare("INSERT INTO location_details(user_id, location, time) values(?, ?, ?)");
        $currentTime=time();
        $query->bind_param('ssd', $data['user_id'], $data['location_json'], $currentTime);
        $result = $query->execute();
        $query->close();
        if($result) {
            return 0;
        } else {
            return 1;
        }

    }

    public function getLocation($data) {

        //Send data from location_details table
        $query = $this->con->prepare("SELECT * FROM `location_requests` WHERE id = ?");
        $query->bind_param('d', $data['id']);
        $query->execute();
        //Getting the student result array
        $result = $query->get_result()->fetch_assoc();
        $query->close();
        if($result['permit_status'] == 'rejected') {
            return 'safe';
        } else {
            if($result['permit_status'] == 'granted' || $result['time'] < strtotime('-30 seconds')) {
                $statement = $this->con->prepare("SELECT `location`, `time` FROM `location_details` WHERE (user_id = ? AND time > ?)");
                $time_lower = strtotime('-6 hours');
                $statement->bind_param('sd', $result['user_id'], $time_lower);
                $statement->execute();
                //Getting the student result array
                $information = $statement->get_result();
                $result_object = array();
                $flag = False;
                while ( $row = $information->fetch_assoc()) {
                    $flag = true;
                    array_push($result_object, $row);
                }
                $statement->close();
                
                $searchGCM = $this->con->prepare("SELECT gcm FROM `user_gcm_details` WHERE user_id = ?");
                // echo $result['user_id'];
                $searchGCM->bind_param('s', $result['user_id']);
                $searchGCM->execute();
                $resultGCM = $searchGCM->get_result()->fetch_assoc();
                $searchGCM->close();
                if ($flag) {
                    sendNotification($resultGCM['gcm'], $result['asker_id'], True);
                }
                return $result_object;
            } else {
                return 'wait';
            }
        }
    }

    public function fetchLocationHistory($data) {
        $query = $this->con->prepare("INSERT INTO location_requests(asker_id, user_id, time) values(?, ?, ?)");
        $currentTime=time();
        $query->bind_param('ssd', $data['asker_id'], $data['user_id'], $currentTime);
        $result = $query->execute();
        $query->close();

        $insert_id = $this->con->insert_id;

        $searchGCM = $this->con->prepare("SELECT gcm FROM `user_gcm_details` WHERE user_id = ?");
        $searchGCM->bind_param('s', $data['user_id']);
        $searchGCM->execute();
        $resultGCM = $searchGCM->get_result()->fetch_assoc();
        $searchGCM->close();
        $response = sendNotification($resultGCM['gcm'], [
                                           'name'=> $data['asker_id'],
                                           'id'=> $insert_id,
                                           'time'=> time() + 30
                                           ], False);
        $response = json_decode($response, true);
        if($result && $response['success']) {
            return $insert_id;
        } else {
            return False;
        }
    }

    public function registerGcm($data) {
        $query = $this->con->prepare("INSERT INTO user_gcm_details(user_id, gcm) values(?, ?) ON DUPLICATE KEY UPDATE gcm = ?;");
        $query->bind_param('sss', $data['user_id'], $data['gcm'], $data['gcm']);
        $result = $query->execute();
        $query->close();
        if($result) {
            return True;
        } else {
            return False;
        }
    }

    public function checkPermit($data) {
        $query = $this->con->prepare("UPDATE location_requests SET permit_status = ? where id = ?");
        $query->bind_param('ss', $data['permit'], $data['id']);
        $result = $query->execute();
        $query->close();
        if($result) {
            return True;
        } else {
            return False;
        }
    }


}