<?php session_start();
require  "../config.inc.php";
require PATH . '/class/connect.inc.php'; // เชื่อมต่อฐานข้อมูล
require 'session.inc.php';
//echo print_r($_POST);
if ($_POST) {

    // รับข้อมูลจากฟอร์ม
$household_id = $mysqli->real_escape_string($_POST['household_id']);
$citizen_id = $mysqli->real_escape_string($_POST['citizen_id']);
$name = $mysqli->real_escape_string($_POST['name']);
$surname = $mysqli->real_escape_string($_POST['surname']);
$birth_date = $mysqli->real_escape_string($_POST['birth_date']);
$gender = $mysqli->real_escape_string($_POST['gender']);



// เพิ่มข้อมูลสมาชิกลงในฐานข้อมูล
if(!empty($_POST["editId"])) {
    $sql = " UPDATE `citizens` 
    SET `citizen_id`='{$citizen_id}', `name`='{$name}', `surname`='{$surname}', `birth_date`='{$birth_date}', 
    `gender`='{$gender}' WHERE (`id`='{$_POST["editId"]}')";
}else {
    // ตรวจสอบหมายเลขบัตรประชาชนซ้ำ
$sql_check = "SELECT id FROM citizens WHERE citizen_id = '{$citizen_id}' ";
$check = $mysqli->query($sql_check);
if ($check->num_rows > 0) {
    echo "หมายเลขบัตรประชาชนนี้มีอยู่ในระบบแล้ว!";
    exit;
}
    $sql = "  INSERT INTO `citizens` (`citizen_id`, `name`, `surname`, `birth_date`, `gender`, `household_id`,update_at) 
    VALUES ('{$citizen_id}', '{$name}', '{$surname}', '{$birth_date}', '{$gender}', '{$household_id}' ,NOW())";
}


  $result = $mysqli->query($sql);
    if ($result) {
       echo "บันทึกข้อมูลเรียบร้อยแล้ว!";
       header('location:member.php?id='.$household_id.'&step=complete');
exit;
} else {
echo "เกิดข้อผิดพลาด: " . $mysqli->error;
}




}