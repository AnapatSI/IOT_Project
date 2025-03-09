<?php
// ตั้งค่า API Key
$api_key_value = "tPmAT5Ab3j7F9";
$api_key = $_POST['api_key'];

if ($api_key == $api_key_value) {
    // รับภาพที่แปลงเป็น Base64
    $image_data = $_POST['image'];

    // แปลงข้อมูล Base64 กลับเป็นไฟล์ภาพ
    $image = base64_decode($image_data);
    $file = 'uploaded_image_' . time() . '.jpg';

    // บันทึกไฟล์ภาพ
    file_put_contents($file, $image);

    echo "Image uploaded successfully!";
} else {
    echo "Invalid API Key!";
}
?>
