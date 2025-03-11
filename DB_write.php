<?php
include 'connect.php';

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
$api_key_value = "tPmAT5Ab3j7F9";

// ตรวจสอบว่าได้รับข้อมูล API Key และรูปภาพจาก POST request
if (isset($_POST['api_key']) && isset($_POST['image']) && isset($_POST['box_percentage']) && isset($_POST['box_status'])) {
    $apiKey = $_POST['api_key'];
    $imageData = $_POST['image'];
    $boxPercentage = intval($_POST['box_percentage']);
    $boxStatus = intval($_POST['box_status']);

    // ตรวจสอบ API Key
    $validApiKey = "tPmAT5Ab3j7F9"; // ใส่ API Key ที่ใช้จริง
    if ($apiKey === $validApiKey) {
        
        // แปลง Base64 เป็นรูปภาพ
        $imageData = base64_decode($imageData);

        // บันทึกลงในฐานข้อมูลเป็น MediumBLOB
        $stmt = $conn->prepare("INSERT INTO box_data (box_image, box_percentage, box_status) VALUES (?, ?, ?)");
        $stmt->bind_param("bii", $imageData, $boxPercentage, $boxStatus);
        
        // ดำเนินการบันทึกข้อมูล
        if ($stmt->execute()) {
            echo "Image uploaded successfully!";
        } else {
            echo "Failed to upload image: " . $stmt->error;
        }
        
        // ปิดการเชื่อมต่อฐานข้อมูล
        $stmt->close();
    } else {
        echo "Invalid API Key!";
    }
} else {
    echo "Required fields are missing!";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
