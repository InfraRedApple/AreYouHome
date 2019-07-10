<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$jsonData = json_decode(file_get_contents("ips.json"), JSON_PRETTY_PRINT);

$status = [];

foreach ($jsonData['ips'] as $person){
    $person['result'] = false;

//    $person['result'] = "Not Home";
    if(!empty($person['ip'])){
//        exec("ping -c 4 " . $person['ip'], $output, $result);
//        if ($result == 0){
            $person['result'] = true;
//        }
    }


    $status[] = $person;
}
