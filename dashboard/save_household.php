<?php session_start();
require  "../config.inc.php";
require PATH . '/class/connect.inc.php'; // เชื่อมต่อฐานข้อมูล
require 'session.inc.php';

if ($_POST) {

    // รับข้อมูลจากฟอร์ม
    $house_no = $mysqli->real_escape_string($_POST['house_no']);
    $latitude = $mysqli->real_escape_string($_POST['latitude']);
    $longitude = $mysqli->real_escape_string($_POST['longitude']);
    $onwer_name = $mysqli->real_escape_string($_POST['onwer_name']);
    $address = $mysqli->real_escape_string($_POST['address']);


    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = " INSERT INTO `households` (`house_no`, `onwer_name`, `address`, `latitude`, `longitude`, `do_adddate`, `update_at`) 
VALUES ('{$house_no}', '{$onwer_name}', '{$address}', '{$latitude}', '{$longitude}', NOW(), NOW())";
    echo $sql;
   
    $result = $mysqli->query($sql);
    if ($result) {
        echo "บันทึกข้อมูลเรียบร้อยแล้ว!";
    } else {
        echo "เกิดข้อผิดพลาด: " . $mysqli->error;
    }
}
$mysqli->close();
?>