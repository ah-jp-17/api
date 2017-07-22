<?php
/**
 * Created by PhpStorm.
 * User: Shwetank
 * Date: 22-Jul-17
 * Time: 16:57
 */

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

        //Save data in the users table come from pinging
        $query = $this->con->prepare("INSERT INTO users(username, location, created_at) values(?, ?, ?)");
        $query->bind_param($data['username'], json_encode($data['location']));
        $result = $query->execute();
        $query->close();

        if($result) {

            return 0;

        } else {

            return 1;

        }

    }

    public function getLocation($data) {

        //Send data from users table
        $query = $this->con->prepare("SELECT * FROM `location_details` WHERE id = ?");
        $query->bind_param('s', $data['id']);
        $query->execute();
        //Getting the student result array
        $result = $query->get_result()->fetch_assoc();
        $query->close();

        if($result['permit_status'] == 'granted') {
            if(strtotime($result['created_at']) > strtotime('-5 minutes')) {
                $statement = $this->con->prepare("SELECT * FROM `users` WHERE (id = ? AND created_at > DATE_ADD(NOW(), INTERVAL -6 HOUR))");
                $statement->bind_param('s', $result['user_id']);
                $statement->execute();
                //Getting the student result array
                $information = $statement->get_result()->fetch_assoc();
                $statement->close();
                print_r($information);die;
            }
//            return $result;
        } else {
            print_r('Fuck off!!');die;
//            return $result;
        }
        //returning the student
//        return $result;

    }

    public function checkPermit($data) {
        //TODO: Implementation of backend
        print_r($data);die;
    }

    public function fetchLocationHistory($data) {
        //TODO: Implementation of backend
        print_r($data);die;
    }
}