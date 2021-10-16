<?php
    session_start();

    require_once('config/database_connect.php');

    // Database Connection

    $connection = @new mysqli($db_host, $db_user, $db_password, $db_name);

    if($connection -> connect_errno != 0)
    { 
        error(1);
        return;
    }

    // Check

    check($connection);

    function check($connection)
    {
        if(isset($_GET['m']) == true){
            $method = $_GET['m'];

            if((isset($_SESSION['loged']) && ($_SESSION['loged']) == true)){
                $userid = $_SESSION['userid'];
                
                switch($method)
                {
                    case 1:
                        GetAllInfo($connection, $userid);
                        break;
                    case 2:
                        GetFileInfo();
                        break;
                    default:
                        error(-9999);
                        break;
                }
            }
            else{
                error(4);
                return;
            }
        }

        else{
            error(2);
            return;
        }
    }

    // GetAllInfo

    function GetAllInfo($connection, $userid){

        if($result = $connection->query(
            "SELECT * FROM users JOIN film 
            ON users.id = film.customer_id 
            WHERE users.id=".$userid."
            AND film.customer_id =".$userid))
        {
            $videos_num = $result -> num_rows;

            echo GetAllInfoIntoJSON($result, $videos_num);
        }
        else{
            error(-99999);
        }

        $connection->close();
    }
    
    function GetAllInfoIntoJSON($result, $videos_num)
    {
        if($videos_num>0)
        {
            //DECLARE
            $json_variables = new StdClass;
            $json_variables -> Type = new StdClass;
            $json_variables -> UserData = new StdClass;
            $json_variables -> UserVideos = new StdClass; 
            $json_variables -> UserVideos -> VideosCount = new StdClass; 
            $json_variables -> UserVideos -> VideosID = array();

            //START
            $json_variables -> Type = "GetAllInfo";

            //VideoDATA
            $json_variables -> UserVideos -> VideosCount = $videos_num;

            for($i = 1; $i <= $videos_num; $i++){
                $data = $result -> fetch_assoc();
                array_push($json_variables -> UserVideos -> VideosID, $data['videoKey']);
            }

            // UserDATA
            $json_variables -> UserData -> username = $data['username'];
            $json_variables -> UserData -> email = $data['email'];
            $json_variables -> UserData -> actualip = getUserIpAddr();

            //END
            $json_response = json_encode($json_variables);

            return $json_response;
            
        } 
        else
        {
            $json_variables = new StdClass;
            $json_variables -> video = null;
            $json_variables -> customer_id = null;
            $json_variables -> name = null;

            $json_response = json_encode($json_variables);
            // echo $json_response;
        }
    }

    // GetVideoInfo

    function GetFileInfo()
    {
        if(isset($_GET['videoKey']) == true)
        {
            global $drive_key;
            $video_key = $_GET['videoKey'];
            $url = 'https://www.googleapis.com/drive/v2/files/';
           ;
            $request_url = $url . $video_key . $drive_key;
    
            $curl = curl_init($request_url);
        
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
    
            $response = curl_exec($curl);
            curl_close($curl);
            $json = json_decode($response);
            echo $response . PHP_EOL;
        }
        else
        {
            error(5);
            return;
        }
    }


    // Add-on functions (sorry for polish comments, I'll change it)
    

    function error($code){
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
                $reply = "Wystapil blad wczytywania danych uzytkownika. Skontaktuj sie z administratorem strony lub sprobuj ponownie pozniej.";
                break;
            case 5:
                $reply = "Wystapil blad wczytywania zmiennej 'videoKey' z url. Sprawdz poprawnosc zmiennej 'videoKey w linku'";
                break;
            default:
                $reply = "Wystapil nieoczekiwany blad! Skontaktuj sie z administratorem strony lub sprobuj ponownie pozniej.";
                break;
        }

        $json_variables = new StdClass;
        $json_variables -> code = $code;
        $json_variables -> reply = $reply;
        
        $json_response = json_encode($json_variables);

        header("Content-Type: application/json; charset=UTF-8");
        
        echo $json_response;
    }

    
    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }



?>