<?php
    $s= array(
            'type' => 'GetAllInfo',
            'UserData' => array(
                'username' => 'Incognitoo',
                'email' => 'sosnowskimarcinszizuw@gmail.com',
                'firstname' => 'Marcin',
                'lastname' => 'Sosnowski',
                'lastlogged' => '03-02-2021',
                'lastloggedip' => '68.23.05.983',
                'actualip' => '78.83.23.273'
            ),

            'UserVideos' => array(
                'VideosCount' => 3,
                'VideosID' => array("78236589723ynvr8y123r8971r", "78236589723ynvr8y123r8971r", "78236589723ynvr8y123r8971r"),
                '78236589723ynvr8y123r8971r' => array(
                    'ss' => '03-12-2021',
                    'min' => 'https://incognitoo.pl/images/1r2y2387.png',
                    'time' => '5468317',
                    'available' => true
                )
            )
        );
        
    header("Content-Type: application/json; charset=UTF-8");
    $data = json_encode($s);
    echo $data;




?>