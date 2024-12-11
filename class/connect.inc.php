<?php
if($_SERVER["HTTP_HOST"] == "127.0.0.1"){
    $db_config=array(
        "host"=>"localhost",  // กำหนด host
        "user"=>"root", // กำหนดชื่อ user
        "pass"=>"",   // กำหนดรหัสผ่าน
        "dbname"=>"chaiyaphruek",  // กำหนดชื่อฐานข้อมูล ,db_techserv				 
        "charset"=>"utf8"  // กำหนด charset
    );
}else{
//Database name：sql_project_eng_
//User：sql_project_eng_
//Password：Xdatk58xpn7fhZhm
    $db_config=array(
        "host"=>"localhost",  
        "user"=>"", 
        "pass"=>"",   
        "dbname"=>"", 
        "charset"=>"utf8"  
    );
}

$mysqli = @new mysqli($db_config["host"], $db_config["user"], $db_config["pass"], $db_config["dbname"]);
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}
