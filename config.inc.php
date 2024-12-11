<?php 
header("Content-Type:text/html;charset=utf-8");

define("PATH",$_SERVER["DOCUMENT_ROOT"]."/chaiyaphruek.com");
define("TITLE","หมู่บ้านชัยพกษ์ หมู่ที่ 7 ตำบลหนองหอย อำเภอเมืองเชียงใหม่ จ.เชียงใหม่ 50000");

$KEYWORD = "หมู่บ้านชัยพกษ์ หมู่ที่ 7 ตำบลหนองหอย อำเภอเมืองเชียงใหม่ จ.เชียงใหม่ 50000";
$DESCRIPTION = "หมู่บ้านชัยพกษ์ หมู่ที่ 7 ตำบลหนองหอย อำเภอเมืองเชียงใหม่ จ.เชียงใหม่ 50000";	
date_default_timezone_set('Asia/Bangkok');  

function calculateAge($dob) {
    $dobDate = new DateTime($dob); // แปลงวันที่เกิดเป็น DateTime
    $currentDate = new DateTime(); // วันที่ปัจจุบัน
    $age = $dobDate->diff($currentDate)->y; // คำนวณอายุเป็นปี
    return $age;
}