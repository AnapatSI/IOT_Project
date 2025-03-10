<?php
include 'connect.php';

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
$api_key_value = "tPmAT5Ab3j7F9";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if ($api_key == $api_key_value) {

        // เช็คว่ามีการรับไฟล์ภาพหรือไม่
        if (isset($_FILES['image']['tmp_name'])) {
            $image = file_get_contents($_FILES['image']['tmp_name']);  // อ่านไฟล์ภาพจาก POST request.

            // สร้างการเชื่อมต่อฐานข้อมูล
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL เพื่อบันทึกข้อมูลภาพลงใน Table
            $sql = "INSERT INTO box_images (image) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $image);  // "s" สำหรับข้อมูลประเภท string
            if ($stmt->execute()) {
                echo "Image uploaded successfully!";
            } else {
                echo "Error uploading image: " . $stmt->error;
            }

            // ปิดการเชื่อมต่อฐานข้อมูล
            $stmt->close();
            $conn->close();
        } else {
            echo "No image received.";
        }

    } else {
        echo "Wrong API Key provided.";
    }
} else {
    echo "No data posted with HTTP POST.";
}

// ฟังก์ชั่นเพื่อล้างข้อมูลที่รับมาจาก POST
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
