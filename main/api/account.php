<?php

    // Konfiguracja wstepna

    session_start();

    require_once('config/credentials.php');

$connection = @new mysqli($db_host, $db_user, $db_password, $db_name);

    if($connection -> connect_errno != 0)
    { 
        $code = 1;
        error($code);
        return;
    }


    // Sprawdzanie metody "Co chcesz kurwa uczynic?"

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
                error(4); 
                break;
            }
            login($connection, $username, $password);
            break;
        case 2:
            logout();
            break;
        default:
            error(3); 
            break;
    }


    // Funkcje

    function login($connection, $username, $password){
        $username = htmlentities($username, ENT_QUOTES, "UTF-8");
		$password = htmlentities($password, ENT_QUOTES, "UTF-8");

        if($result = @$connection->query(
            sprintf("SELECT * FROM users JOIN film 
            ON users.id = film.customer_id 
            WHERE users.username='%s'
            AND users.password='%s' 
            AND film.customer_id = (SELECT users.id FROM users WHERE users.username = '%s' AND users.password ='%s')",
            mysqli_real_escape_string($connection,$username),
            mysqli_real_escape_string($connection,$password),
            mysqli_real_escape_string($connection,$username),
            mysqli_real_escape_string($connection,$password))))
        {
            $num_of_users = $result -> num_rows;
			if($num_of_users>0)
			{
                $data = $result -> fetch_assoc();

                $json_variables = new StdClass;
                $json_variables -> loged = 1;
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
                $json_variables -> userID= null;
                $json_variables -> username = null;
                $json_variables -> email = null;

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
                $reply = "Wystapil blad polaczenia z baza danych! Skontaktuj sie z administatorem strony lub sprobuj ponownie.";
                break;
            case 2:
                $reply = "Wystapil blad wczytywania 'Co chcesz kurwa uczynic?'. Sprawdz poprawnosc zmiennej 'm' w linku.";
                break;
            case 3:
                $reply = "Wystapil blad wczytywania 'Co chcesz kurwa uczynic?'. Sprawdz poprawnosc zmiennej 'm' w linku.";
                break;
            case 4:
                $reply = "Wystapil blad wczytywania zmiennych post do logowania. Sprawdz poprawnosc zmiennej post przy polaczeniu do API.";
                break;
            default:
                $reply = "Wystapil nieoczekiwany blad! Skontaktuj sie z administratorem strony lub sprobuj ponownie pozniej.";
                break;
        }
        
        $json_variables -> reply = $reply;
        $json_variables -> userID= null;
        $json_variables -> username = null;
        $json_variables -> email = null;
        
        $json_response = json_encode($json_variables);

        header("Content-Type: application/json; charset=UTF-8");
        
        echo $json_response;
    }
?>