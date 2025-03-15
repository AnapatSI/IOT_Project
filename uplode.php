<?php
// ตั้งค่า API Key
$api_key_value = "tPmAT5Ab3j7F9";

// ตรวจสอบ API Key
if (!isset($_POST['api_key']) || $_POST['api_key'] !== $api_key_value) {
    die("Invalid API Key!");
}

// ตรวจสอบว่ามีข้อมูลรูปภาพถูกส่งมาหรือไม่
if (!isset($_POST['image'])) {
    die("No image data received!");
}

// รับภาพที่แปลงเป็น Base64
$image_data = $_POST['image'];

// แปลง Base64 เป็นไฟล์ภาพ
$image = base64_decode($image_data);

// ตรวจสอบว่าไฟล์ถูกถอดรหัสได้จริง
if ($image === false) {
    die("Failed to decode image!");
}

// ตั้งค่าชื่อไฟล์ให้ไม่ซ้ำกัน
$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
    // สร้างไดเรกทอรีถ้ายังไม่มี
    if (!mkdir($upload_dir, 0777, true)) {
        die("Failed to create upload directory!");
    }
}

// สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
$file_name = $upload_dir . 'image_' . uniqid() . '.jpg';

// บันทึกไฟล์ภาพ
if (file_put_contents($file_name, $image)) {
    echo "Image uploaded successfully: " . $file_name;
} else {
    echo "Failed to save image!";
}
?>
