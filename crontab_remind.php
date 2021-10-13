<?php 
$url = "https://pocketplan2020.herokuapp.com/remind.php";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$info = curl_getinfo($curl);
print_r($response);exit;
?>