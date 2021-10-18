<?php

    // Starting configuration

    // Everything here needs to be changed xDDD


    session_start();

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');

    require_once('config/credentials.php');

    $connection = @new mysqli($db_host, $db_user, $db_password, $db_name);

    if($connection -> connect_errno != 0)
    { 
        $code = 1;
        error($code);
        return;
    }

    // Checking GET method

    if(isset($_GET['m']) == true){
        $method = $_GET['m'];
    }
    else{
        error(2);
        return;
    }

    switch ($method){
        case 1: 
            if((isset($_POST['username'])) && ($_POST['password'] == true))
            {
                $username = $_POST['username'];
                $password = $_POST['password'];
            }
    
            else{
                error(3);
                break;
            }
            login($connection, $username, $password);
            break;
        case 2:
            logout();
            break;
        default:
            error(2);
            break;
    }

    // Main Functions

    function login($connection, $username, $password){
        $username = htmlentities($username, ENT_QUOTES, "UTF-8");
		$password = htmlentities($password, ENT_QUOTES, "UTF-8");

        if($result = @$connection->query(
            sprintf("SELECT * FROM users
            WHERE users.username='%s'
            AND users.password='%s';",
            mysqli_real_escape_string($connection,$username),
            mysqli_real_escape_string($connection,$password))))
        {
            $num_of_users = $result -> num_rows;
			if($num_of_users>0)
			{
                $data = $result -> fetch_assoc();

                $json_variables = new StdClass;

                if($data['isAdmin'] == 1)
                {
                    $json_variables -> logged = 1;
                    $json_variables -> code = 200;
                    $json_variables -> userid = $data['id'];
                    $json_variables -> username = $data['username'];
                    $json_variables -> email = $data['email'];
    
                    $json_response = json_encode($json_variables);
                    echo $json_response;
                    
                    $_SESSION['loged'] = true;
                    $_SESSION['userid'] = $data['id'];
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['email'] = $data['email'];
                }

                else
                {
                    $json_variables = new StdClass;
                    $json_variables -> loged = 0;
                    $json_variables -> code = 404;
    
                    $json_response = json_encode($json_variables);
                    echo $json_response;
                }
			} 
            else
            {
                $json_variables = new StdClass;
                $json_variables -> logged = true;
                $json_variables -> code = 404;

                $json_response = json_encode($json_variables);
                echo $json_response;
			}
        }

        $connection->close();
    }

    function logout(){
        session_unset();
        header("Location: ../index.php");
    }

    function error($code){
        $json_variables = new StdClass;
        $json_variables -> loged = -1;
        $json_variables -> code = $code;

        switch ($code){
            case 1:
                $reply = "A connection error to the database has occurred. Please contact the site administrator or try again.";
                break;
            case 2:
                $reply = "There was an error loading the GET method. Check the correctness of the variable 'm' in the link.";
                break;
            case 3:
                $reply = "There was an error loading the POST method. Please contact the site administrator or try again.";
                break;
            case 4:
                $reply = "The specified user is not an administrator.";
                break;
            default:
                $reply = "An unexpected error has occurred! Please contact the site administrator or try again.";
                break;
        }
        
        $json_variables -> reply = $reply;
        $json_variables -> logged = true;
        
        $json_response = json_encode($json_variables);

        header("Content-Type: application/json; charset=UTF-8");
        
        echo $json_response;
    }
