<?php session_start();
require  "../config.inc.php";
$_SESSION["staffs"]="AASSSS";
require  'session.inc.php';
error_reporting(E_ERROR | E_PARSE);
require  '../class/connect.inc.php'; // เชื่อมต่อฐานข้อมูล
if($_GET["do"]=="edit") {

        $q = $mysqli->query("SELECT
households.id,
households.house_no,
households.onwer_name,
households.address,
households.latitude,
households.longitude,
households.do_adddate,
households.update_at
FROM
households
where households.id =   '{$_GET["id"]}'  ") ;
$row = $q->fetch_assoc();

}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="../assets/js/color-modes.js"></script>
    <?php  include "inc_headtag.php";?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
    #map {
        height: 300px;
        /* กำหนดความสูงของแผนที่ */
    }
    </style>

</head>

<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#"> Webmaster : หมู่บ้านชัยพกษ์ </a>

    <ul class="navbar-nav flex-row d-md-none">
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false"
                aria-label="Toggle search">
                <svg class="bi">
                    <use xlink:href="#search" />
                </svg>
            </button>
        </li>
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <svg class="bi">
                    <use xlink:href="#list" />
                </svg>
            </button>
        </li>
    </ul>

    <div id="navbarSearch" class="navbar-search w-100 collapse">
        <input class="form-control w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <?php include "menu.php";?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="container mt-5">
                <h2>
                    <?php if($_GET["do"]=="edit") echo 'แก้ไขข้อมูลทะเบียนบ้าน';else echo 'เพิ่มข้อมูลทะเบียนบ้าน';?>
                </h2>
                <div class="col-12 mb-3">
                    <form action="save_household.php" method="POST">
                        <input type="hidden" name="editId" value="<?=$_GET["id"]?>">
                        <div class="mb-3">
                            <label for="house_no" class="form-label">หมายเลขบ้าน</label>
                            <input type="text" class="form-control" id="house_no" name="house_no" required
                                value="<?= $row["house_no"]?>">
                        </div>
                        <div class="mb-3">
                            <label for="onwer_name" class="form-label">ชื่อเจ้าบ้าน</label>
                            <input type="text" class="form-control" id="onwer_name" name="onwer_name"
                                value="<?= $row["onwer_name"]?>">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">ที่อยู่</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="<?= $row["address"]?>">
                        </div>

                        <div class="mb-3">
                            <br />
                            <hr />
                            <h4>เลือกตำแหน่งบนแผนที่</h4>
                            <label for="latitude">Latitude:</label>
                            <input type="text" id="latitude" name="latitude" readonly value="<?= $row["latitude"]?>">
                            <label for="longitude">Longitude:</label>
                            <input type="text" id="longitude" name="longitude" readonly value="<?= $row["longitude"]?>">
                        </div>
                        <div class="mb-3">
                            <div id="map"></div>
                        </div><br />
                        <center>
                            <button type="submit" class="btn btn-primary">บันทึก</button> &nbsp; &nbsp;
                            <a href="listall.php" class="btn btn-secondary" role="button">ยกเลิก</a>
                        </center>
                        <br />
                        <br />
                        <br />
                    </form>
                </div>
            </div>
        </main>
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
const marker = L.marker([initialLat, initialLng], {
    draggable: true
}).addTo(map);

// อัปเดตค่าพิกัดเมื่อหมุดถูกลาก
marker.on('dragend', function(event) {
    const position = marker.getLatLng();
    document.getElementById('latitude').value = position.lat;
    document.getElementById('longitude').value = position.lng;
});

// อัปเดตค่าพิกัดเมื่อผู้ใช้คลิกที่แผนที่
map.on('click', function(event) {
    const position = event.latlng;
    marker.setLatLng(position);
    document.getElementById('latitude').value = position.lat;
    document.getElementById('longitude').value = position.lng;
});
</script>


<?php include "inc.footer.php";?>
</body>

</html>