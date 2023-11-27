<?php
$servername = "91.201.67.82";
$username = "tatum199";
$password = "4xxH8V4T3455fgFFfv";
$dbname = "cdn-digitalocean-dump";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$data = file_get_contents("http://slave.cdn-loadbalancer.com/portugal_cdn/get_all_cdn_server");
$json = json_decode($data, true);

$remove_alldata_sql = "DELETE FROM cdn_list";
if (mysqli_query($conn, $remove_alldata_sql)) {
    print_r("All Data removed");
}

foreach ($json as $key => $value) {
    $result = mysqli_query($conn, "SELECT * FROM cdn_list WHERE IP = '" . $value . "'");
    if (!mysqli_num_rows($result)) {
        $sql = "INSERT INTO cdn_list (IP, checked) VALUES ('" . $value . "', '0')";
        mysqli_query($conn, $sql);
    }
}

mysqli_close($conn);