<?php
    //Starting Configuration

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');

    include('config/database_connect.php');

    $connection = @new mysqli($db_host, $db_user, $db_password, $db_name);

    if($connection -> connect_errno != 0)
    {
        echo error(1);
        return;
    }


    function CheckingGetMethod(){
        if(isset($_GET['m']) == true) {

            $m_get = $_GET['m'];

            switch ($m_get)
            {
                case 1:
                    $UsersControl = new MainPanelFunctions();
                    echo $UsersControl -> GetAllUsers();
                    break;
                case 2:
                    $UsersControl = new MainPanelFunctions();
                    echo $UsersControl -> GetUserInfo();
                    break;
                case 3:
                    $UsersControl = new MainPanelFunctions();
                    echo $UsersControl -> GetUserVideos();
                    break;
                default:
                    echo error(2);
            }
        }
        else{
            echo error(2);
        }
    }




    class MainPanelFunctions{
        function GetAllUsers()
        {
            $json_variable = new StdClass;
            $json_variable -> number_of_users = new StdClass;

            global $connection;
            $result = @$connection -> query("select username, email, isAdmin from users;");

            if($result->num_rows <= 0)
            {
                $json_variable->number_of_users = $result->num_rows;
            }
            else {
                $json_variable->main_data = array();
                $json_variable->number_of_users = $result->num_rows;

                while ($row = $result->fetch_assoc()) {
                    array_push($json_variable->main_data, $row);
                }
            }

            return json_encode($json_variable);
        }

        function GetUserInfo()
        {
            echo "TO DO";
        }

        function GetUserVideos()
        {
            $username = $_GET['username'];

            $json_variable = new StdClass;
            $json_variable -> number_of_files = new StdClass;

            global $connection;
            $result = @$connection -> query("SELECT * FROM `film` WHERE customer_id = (SELECT id from users where username = '".$username."')");

            if($result->num_rows <= 0)
            {
                $json_variable->number_of_files = $result->num_rows;
            }
            else {
                $json_variable->main_data = array();
                $json_variable->number_of_files = $result->num_rows;

                while ($row = $result->fetch_assoc()) {
                    array_push($json_variable->main_data, $row);
                }
            }

            return json_encode($json_variable);
        }

        function errorCodes($code)
        {
            switch ($code)
            {
                case 600:
                    $reply = "Undefined number of users";

            }
        }
    }

    function error($code){
        switch ($code) {
            case 1:
                $reply = "A connection error to the database has occurred. Please contact the site administrator or try again.";
                break;
            case 2:
                $reply = "There was a fatal error getting the variable m from get. Please contact the site administrator or try again.";
                break;
            default:
                $reply = "An unexpected error has occurred! Please contact the site administrator or try again.";
                break;
        }
    }