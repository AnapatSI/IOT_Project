// #include <WiFi.h>
// #include <WiFiClientSecure.h>  // รองรับ HTTPS
// #include <HTTPClient.h>
// #include "esp_camera.h"
// #include "mbedtls/base64.h" // ใช้สำหรับ Base64 encoding
// #include "camera_pins.h"

// #define CAMERA_MODEL_ESP_EYE

// // ตั้งค่า WiFi
// const char* ssid = "Axf";
// const char* password = "86428642";

// // ตั้งค่าเซิร์ฟเวอร์ PHP
// const char* serverURL = "https://angsila.informatics.buu.ac.th/~66160402/IOT_Project/DB_write.php";

// // API Key เพื่อความปลอดภัย
// const char* apiKey = "tPmAT5Ab3j7F9";

// WiFiClientSecure client; // ใช้ Secure Client

// void setup() {
//     Serial.begin(115200);

//     // เชื่อมต่อ WiFi
//     Serial.println("WiFi Connecting...");
//     WiFi.begin(ssid, password);
//     while (WiFi.status() != WL_CONNECTED) {
//         delay(500);
//         Serial.print(".");
//     }
//     Serial.println("\nWiFi connected.");

//     // ปิดการตรวจสอบ SSL (แก้ปัญหาใบรับรอง)
//     client.setInsecure();

//     // ตั้งค่ากล้อง
//     camera_config_t config;
//     config.ledc_channel = LEDC_CHANNEL_0;
//     config.ledc_timer = LEDC_TIMER_0;
//     config.pin_d0 = GPIO_NUM_15; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_d1 = GPIO_NUM_2;  // ใช้ GPIO ที่ถูกต้อง
//     config.pin_d2 = GPIO_NUM_4;  // ใช้ GPIO ที่ถูกต้อง
//     config.pin_d3 = GPIO_NUM_5;  // ใช้ GPIO ที่ถูกต้อง
//     config.pin_d4 = GPIO_NUM_18; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_d5 = GPIO_NUM_19; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_d6 = GPIO_NUM_21; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_d7 = GPIO_NUM_22; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_xclk = GPIO_NUM_0; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_pclk = GPIO_NUM_4; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_vsync = GPIO_NUM_5; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_href = GPIO_NUM_22; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_sscb_sda = GPIO_NUM_23; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_sscb_scl = GPIO_NUM_18; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_pwdn = GPIO_NUM_32; // ใช้ GPIO ที่ถูกต้อง
//     config.pin_reset = GPIO_NUM_33; // ใช้ GPIO ที่ถูกต้อง
//     config.xclk_freq_hz = 20000000;
//     config.pixel_format = PIXFORMAT_JPEG;

//     if (psramFound()) {
//         config.frame_size = FRAMESIZE_UXGA;
//         config.jpeg_quality = 10;
//         config.fb_count = 2;
//     } else {
//         config.frame_size = FRAMESIZE_SVGA;
//         config.jpeg_quality = 12;
//         config.fb_count = 1;
//     }

//     // เริ่มต้นกล้อง
//     esp_err_t err = esp_camera_init(&config);
//     if (err != ESP_OK) {
//         Serial.printf("Camera init failed with error 0x%x", err);
//         return;
//     }
// }

// void loop() {
//     if (WiFi.status() == WL_CONNECTED) {
//         sendPhoto();  // ถ่ายภาพใหม่ทุกครั้งที่เรียกฟังก์ชันนี้
//     } else {
//         Serial.println("WiFi not connected, attempting to reconnect...");
//         WiFi.reconnect(); // พยายามเชื่อมต่อ WiFi ใหม่
//     }
//     delay(60000); // ส่งทุก 1 นาที
// }

// void sendPhoto() {
//     // ถ่ายภาพใหม่ทุกครั้ง
//     camera_fb_t *fb = esp_camera_fb_get();
//     if (!fb) {
//         Serial.println("Camera capture failed!");
//         return;
//     }

//     // แปลงเป็น Base64
//     size_t output_length;
//     unsigned char base64_output[fb->len * 2]; // ขนาดต้องเผื่อ 2 เท่า
//     mbedtls_base64_encode(base64_output, sizeof(base64_output), &output_length, fb->buf, fb->len);

//     // ปล่อย buffer ของกล้อง
//     esp_camera_fb_return(fb);

//     // สร้าง HTTP POST request
//     HTTPClient http;
//     http.begin(client, serverURL); // ใช้ client ที่รองรับ HTTPS
//     http.addHeader("Content-Type", "application/x-www-form-urlencoded");

//     String postData = "api_key=" + String(apiKey) + "&image=" + String((char*)base64_output);
    
//     int httpResponseCode = http.POST(postData);
//     String response = http.getString();
    
//     Serial.println("Server response: " + response);
    
//     // ตรวจสอบสถานะการส่งข้อมูล
//     if (httpResponseCode == 200) {
//         Serial.println("Image uploaded successfully!");
//     } else {
//         Serial.println("Failed to upload image. HTTP Response Code: " + String(httpResponseCode));
//     }

//     http.end();
// }


#include "WiFi.h"
#include "esp_camera.h"
#include "esp_timer.h"
#include "img_converters.h"
#include "Arduino.h"
#include "soc/soc.h"          
#include "soc/rtc_cntl_reg.h"  
#include "driver/rtc_io.h"
#include <ESPAsyncWebServer.h>
#include "StringArray.h"
#include <SPIFFS.h>
#include <FS.h>


const char* ssid = "Axf";
const char* password = "86428642";


AsyncWebServer server(80);

boolean new_photo = false;

#define photo_path "/image.jpg"

// OV2640 camera module pins (CAMERA_MODEL_AI_THINKER)
#define PWDN_GPIO_NUM     32
#define RESET_GPIO_NUM    -1
#define XCLK_GPIO_NUM      0
#define SIOD_GPIO_NUM     26
#define SIOC_GPIO_NUM     27
#define Y9_GPIO_NUM       35
#define Y8_GPIO_NUM       34
#define Y7_GPIO_NUM       39
#define Y6_GPIO_NUM       36
#define Y5_GPIO_NUM       21
#define Y4_GPIO_NUM       19
#define Y3_GPIO_NUM       18
#define Y2_GPIO_NUM        5
#define VSYNC_GPIO_NUM    25
#define HREF_GPIO_NUM     23
#define PCLK_GPIO_NUM     22

const char index_html[] PROGMEM = R"rawliteral(
<!DOCTYPE HTML><html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { text-align:center; }
    .vert { margin-bottom: 10%; }
    .hori{ margin-bottom: 0%; }
    button {
  background-color: #21b555;
  border: none;
  padding: 7px 10px;
  text-align: center;
  font-size: 10px;
  border-radius: 2px;
  width: 50%;
  color: white;
}
  </style>
</head>
<body>
  <div id="container">
    <h2>ESP32-CAM Photo Web Server</h2>
      <p><button onclick="capturePhoto()">CAPTURE PHOTO</button></p>
      <p><button onclick="rotatePhoto();">ROTATE PHOTO</button></p>
      <p><button onclick="location.reload();">REFRESH PAGE</button></p>
  </div>
  <div><img src="saved-photo" id="photo" width="90%"></div>
</body>
<script>
  var deg = 0;
  function capturePhoto() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', "/capture", true);
    xhr.send();
  }
  function rotatePhoto() {
    var img = document.getElementById("photo");
    deg += 90;
    if(isOdd(deg/90)){ document.getElementById("container").className = "vert"; }
    else{ document.getElementById("container").className = "hori"; }
    img.style.transform = "rotate(" + deg + "deg)";
  }
  function isOdd(n) { return Math.abs(n % 2) == 1; }
</script>
</html>)rawliteral";

void setup() {
  Serial.begin(115200);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  if (!SPIFFS.begin(true)) {
    Serial.println("An Error has occurred while mounting SPIFFS");
    ESP.restart();
  }
  else {
    delay(500);
    Serial.println("SPIFFS mounted successfully");
  }

  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());

  // Turn-off the 'brownout detector'
  WRITE_PERI_REG(RTC_CNTL_BROWN_OUT_REG, 0);

  // OV2640 camera module
  camera_config_t config;
  config.ledc_channel = LEDC_CHANNEL_0;
  config.ledc_timer = LEDC_TIMER_0;
  config.pin_d0 = Y2_GPIO_NUM;
  config.pin_d1 = Y3_GPIO_NUM;
  config.pin_d2 = Y4_GPIO_NUM;
  config.pin_d3 = Y5_GPIO_NUM;
  config.pin_d4 = Y6_GPIO_NUM;
  config.pin_d5 = Y7_GPIO_NUM;
  config.pin_d6 = Y8_GPIO_NUM;
  config.pin_d7 = Y9_GPIO_NUM;
  config.pin_xclk = XCLK_GPIO_NUM;
  config.pin_pclk = PCLK_GPIO_NUM;
  config.pin_vsync = VSYNC_GPIO_NUM;
  config.pin_href = HREF_GPIO_NUM;
  config.pin_sscb_sda = SIOD_GPIO_NUM;
  config.pin_sscb_scl = SIOC_GPIO_NUM;
  config.pin_pwdn = PWDN_GPIO_NUM;
  config.pin_reset = RESET_GPIO_NUM;
  config.xclk_freq_hz = 20000000;
  config.pixel_format = PIXFORMAT_JPEG;

  if (psramFound()) {
    config.frame_size = FRAMESIZE_UXGA;
    config.jpeg_quality = 10;
    config.fb_count = 2;
  } else {
    config.frame_size = FRAMESIZE_SVGA;
    config.jpeg_quality = 12;
    config.fb_count = 1;
  }
  esp_err_t err = esp_camera_init(&config);
  if (err != ESP_OK) {
    Serial.printf("Camera init failed with error 0x%x", err);
    ESP.restart();
  }

  server.on("/", HTTP_GET, [](AsyncWebServerRequest * request) {
    request->send_P(200, "text/html", index_html);
  });

  server.on("/capture", HTTP_GET, [](AsyncWebServerRequest * request) {
    new_photo = true;
    request->send_P(200, "text/plain", "Capturing Photo using ESP32-CAM");
  });

  server.on("/saved-photo", HTTP_GET, [](AsyncWebServerRequest * request) {
    request->send(SPIFFS, photo_path, "image/jpg", false);
  });


  server.begin();

}

void loop() {
  if (new_photo) {
    captureSave_photo();
    new_photo = false;
  }
  delay(1);
}

// Check if photo capture was successful
bool check_photo( fs::FS &fs ) {
  File f_pic = fs.open( photo_path );
  unsigned int pic_sz = f_pic.size();
  return ( pic_sz > 100 );
}

// Capture Photo and Save it to SPIFFS
void captureSave_photo( void ) {
  camera_fb_t * fb = NULL; 
  bool ok = 0;

  do {
    Serial.println("ESP32-CAMP capturing photo...");

    fb = esp_camera_fb_get();
    if (!fb) {
      Serial.println("Failed");
      return;
    }

    Serial.printf("Picture file name: %s\n", photo_path);
    File file = SPIFFS.open(photo_path, FILE_WRITE);
    if (!file) {
      Serial.println("Failed to open file in writing mode");
    }
    else {
      file.write(fb->buf, fb->len); 
      Serial.print("The picture has been saved in ");
      Serial.print(photo_path);
      Serial.print(" - Size: ");
      Serial.print(file.size());
      Serial.println(" bytes");
    }
    file.close();
    esp_camera_fb_return(fb);

    ok = check_photo(SPIFFS);
  } while ( !ok );
}