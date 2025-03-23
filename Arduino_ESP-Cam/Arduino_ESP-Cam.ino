#include <Arduino.h>
#include "esp_camera.h"
#include <WiFi.h>
#include <HTTPClient.h>

// WiFi Credentials
const char* ssid = "Axf";
const char* password = "86428642";
const char* serverUrl = "https://angsila.informatics.buu.ac.th/~66160402/IOT_Project/upload.php";  // เปลี่ยนเป็น URL ของเซิร์ฟเวอร์

// กำหนดขา Ultrasonic
#define TRIG_PIN 12
#define ECHO_PIN 14

// ตั้งค่ากล้อง
#define PWDN_GPIO     -1
#define RESET_GPIO    -1
#define XCLK_GPIO     0
#define SIOD_GPIO     26
#define SIOC_GPIO     27
#define Y9_GPIO       35
#define Y8_GPIO       34
#define Y7_GPIO       39
#define Y6_GPIO       36
#define Y5_GPIO       21
#define Y4_GPIO       19
#define Y3_GPIO       18
#define Y2_GPIO       5
#define VSYNC_GPIO    25
#define HREF_GPIO     23
#define PCLK_GPIO     22

void setup() {
    Serial.begin(115200);
    pinMode(TRIG_PIN, OUTPUT);
    pinMode(ECHO_PIN, INPUT);
    
    // เชื่อมต่อ WiFi
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(1000);
        Serial.print(".");
    }
    Serial.println("\n✅ WiFi Connected!");
    
    // ตั้งค่ากล้อง
    camera_config_t config;
    config.ledc_channel = LEDC_CHANNEL_0;
    config.ledc_timer = LEDC_TIMER_0;
    config.pin_d0 = Y2_GPIO;
    config.pin_d1 = Y3_GPIO;
    config.pin_d2 = Y4_GPIO;
    config.pin_d3 = Y5_GPIO;
    config.pin_d4 = Y6_GPIO;
    config.pin_d5 = Y7_GPIO;
    config.pin_d6 = Y8_GPIO;
    config.pin_d7 = Y9_GPIO;
    config.pin_xclk = XCLK_GPIO;
    config.pin_pclk = PCLK_GPIO;
    config.pin_vsync = VSYNC_GPIO;
    config.pin_href = HREF_GPIO;
    config.pin_sscb_sda = SIOD_GPIO;
    config.pin_sscb_scl = SIOC_GPIO;
    config.pin_pwdn = PWDN_GPIO;
    config.pin_reset = RESET_GPIO;
    config.xclk_freq_hz = 20000000;
    config.pixel_format = PIXFORMAT_JPEG;
    config.frame_size = FRAMESIZE_QVGA;
    config.jpeg_quality = 10;
    config.fb_count = 2;
    
    if (esp_camera_init(&config) != ESP_OK) {
        Serial.println("❌ กล้องเริ่มต้นไม่สำเร็จ!");
        return;
    }
    Serial.println("✅ กล้องพร้อมใช้งาน");
}

void captureAndUpload() {
    camera_fb_t *fb = esp_camera_fb_get();
    if (!fb) {
        Serial.println("❌ ถ่ายภาพล้มเหลว!");
        return;
    }
    
    Serial.println("📷 ถ่ายภาพสำเร็จ! กำลังอัปโหลด...");
    
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "image/jpeg");
    
    int httpResponseCode = http.POST(fb->buf, fb->len);
    if (httpResponseCode > 0) {
        Serial.print("✅ อัปโหลดสำเร็จ: ");
        Serial.println(httpResponseCode);
    } else {
        Serial.print("❌ อัปโหลดล้มเหลว: ");
        Serial.println(httpResponseCode);
    }
    
    http.end();
    esp_camera_fb_return(fb);
}

void loop() {
    digitalWrite(TRIG_PIN, LOW);
    delayMicroseconds(2);
    digitalWrite(TRIG_PIN, HIGH);
    delayMicroseconds(10);
    digitalWrite(TRIG_PIN, LOW);
    
    long duration = pulseIn(ECHO_PIN, HIGH);
    int distance = duration * 0.034 / 2;
    
    Serial.print("📏 ระยะ: ");
    Serial.print(distance);
    Serial.println(" cm");
    
    if (distance > 0 && distance < 10) {
        Serial.println("📸 พบกล่อง! กำลังถ่ายภาพ...");
        captureAndUpload();
        delay(5000);
    }
    delay(500);
}
