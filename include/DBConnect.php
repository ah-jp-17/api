<?php
/**
 * Created by PhpStorm.
 * User: Shwetank
 * Date: 22-Jul-17
 * Time: 16:55
 */

class DBConnect {

    private $con;

    function connect() {

        include_once dirname(__FILE__) . '/constants.php';

        //Database Connection
        $this->con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        //Check for errors
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        //Returning Connection Link
        return $this->con;
    }
}