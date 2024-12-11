<?php session_start();
require  "../config.inc.php";
$_SESSION["staffs"]="AASSSS";
require  'session.inc.php';
if($_GET["do"]=="edit") {

        $q = $mysqli->query("SELECT
households.id,
households.house_no,
households.onwer_name,
households.address,
households.latitude,
households.longitude,
households.do_adddate,
households.update_at,
Count(citizens.id) AS amt_member
FROM
households
where households.id =   '{$_GET["id"]}'  ") ;
    $row = $q->fetch_assoc();

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Household Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 300px;
            /* กำหนดความสูงของแผนที่ */
        }
    </style>

</head>

<body>
    <div class="container mt-5">
        <h2>เพิ่มข้อมูลทะเบียนบ้าน</h2>
        <div class="col-12 mb-3">
        <form action="save_household.php" method="POST">
            <div class="mb-3">
                <label for="house_no" class="form-label">หมายเลขบ้าน</label>
                <input type="text" class="form-control" id="house_no" name="house_no" required value<?= $row[""]?>>
            </div>
            <div class="mb-3">
                <label for="onwer_name" class="form-label">ชื่อเจ้าบ้าน</label>
                <input type="text" class="form-control" id="onwer_name" name="onwer_name" value="" >
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">ที่อยู่</label>
                <input type="text" class="form-control" id="address" name="address" value="">
            </div>         
            
            <div class="mb-3">
                <hr />
                <h2>เลือกตำแหน่งบนแผนที่</h2>
                <label for="latitude">Latitude:</label>
                <input type="text" id="latitude" name="latitude" readonly>
                <label for="longitude">Longitude:</label>
                <input type="text" id="longitude" name="longitude" readonly>
            </div>
            <div class="mb-3">
                <div id="map"></div>
            </div>
            <center>
                 <button type="submit" class="btn btn-primary">บันทึก</button>
            </center>
            <br />

            <br />
        </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ตั้งค่าตำแหน่งเริ่มต้น (ประเทศไทย)

        const initialLat = 18.75518627363531;
        const initialLng = 99.01667475700378;
        const map = L.map('map').setView([initialLat, initialLng], 17);

        // เพิ่มแผนที่จาก OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // เพิ่มหมุดเริ่มต้น
        const marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

        // อัปเดตค่าพิกัดเมื่อหมุดถูกลาก
        marker.on('dragend', function (event) {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });

        // อัปเดตค่าพิกัดเมื่อผู้ใช้คลิกที่แผนที่
        map.on('click', function (event) {
            const position = event.latlng;
            marker.setLatLng(position);
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });
    </script>


</body>

</html>