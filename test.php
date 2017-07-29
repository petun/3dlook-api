<?php

require "vendor/autoload.php";

$apiKey = 'a95a01c0d8cffe0274040397e3bdc97b92d7d582';
$client = new \ShareCloth\Look\Api\Client($apiKey);

//$response = $client->personCustomBody([
//    'gender' => 'male',
//    'chest' => '70',
//    'waist' => '70',
//    'hips' => '70',
//]);
//
//var_dump($response);


//$file1 = __DIR__ . '/tests/images/1.png';
//$file2 = __DIR__ . '/tests/images/2.png';
//$height = 167;
//$gender = 'female';
//$angle = 0;


$file1 = __DIR__ . '/tests/images/3.jpg';
$file2 = __DIR__ . '/tests/images/4.png';
$height = 180;
$gender = 'male';
$angle = 0;

$response = $client->uploads($file1);
if ($response['status'] == false) {
    throw  new \Exception('Failed to upload image1');
}
var_dump($response);
$name1 = $response['name']; //2017_07_29_21_11_00_a6f44ad033c981f0dcb8f8c458df56aa_2017_07_29_21_11_00.png
//$name1 = '2017_07_29_21_11_00_a6f44ad033c981f0dcb8f8c458df56aa_2017_07_29_21_11_00.png';

$response = $client->uploads($file2);
var_dump($response);
$name2 = $response['name']; //2017_07_29_21_11_38_4edfd50b0326aea4fdf66e265ed36af7_2017_07_29_21_11_38.png
//$name2 = '2017_07_29_21_11_38_4edfd50b0326aea4fdf66e265ed36af7_2017_07_29_21_11_38.png';


echo "Name1: " . $name1 . ', name2: ' . $name2 . '<br /><br />';


$response = $client->step([
    'step' => 1,
    'angle' => $angle,
    'image' => $name1,
    'gender' => $gender,
    'height' => $height
]);


//var_dump($response);

$status = $response['status']; //true

if ($status == true) {
    $key = $response['key']; //683
} else {
    throw new \Exception('Failed to ');
}



// 502 GATEWAY
$response = $client->step([
    'step' => 2,
    'angle' => $angle,
    'image' => $name2,
    'gender' => $gender,
    'height' => $height,
    'key' => $key,
]);
//
var_dump($response);

$response = $client->complete([
    'image_1' => $name1,
    'image_2' => $name2,
    'height' => $height,
    'gender' => $gender,
    'angle' => $angle,
    'key' => $key
]);


var_dump($response);






