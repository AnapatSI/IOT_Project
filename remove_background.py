import cv2
import numpy as np
from rembg import remove
from PIL import Image
import os

<<<<<<< HEAD
# โหลดภาพ
image = cv2.imread("AnapatSI/IOT_Project/IMG_1799.jpg")
=======
# สร้างโฟลเดอร์สำหรับเก็บผลลัพธ์
output_folder = "output_images"
if not os.path.exists(output_folder):
    os.makedirs(output_folder)
    print(f"Created folder: {output_folder}")
>>>>>>> 76b8689845dc2440a98e0cc55aafb46f0342f366

<<<<<<< HEAD
# แปลงเป็น HSV
hsv = cv2.cvtColor(image, cv2.COLOR_BGR2HSV)
=======
# กำหนดโฟลเดอร์ที่มีรูปภาพ
input_folder = "images_before"
>>>>>>> 76b8689845dc2440a98e0cc55aafb46f0342f366

<<<<<<< HEAD
# กำหนดช่วงสีที่ต้องการเก็บ (สีของกล่อง)
lower = np.array([10, 50, 50])  # ปรับตามสีของกล่อง
upper = np.array([30, 255, 255])

# Mask เพื่อเก็บเฉพาะกล่อง
mask = cv2.inRange(hsv, lower, upper)

# ทำให้ขอบเรียบขึ้น
kernel = np.ones((5, 5), np.uint8)
mask = cv2.morphologyEx(mask, cv2.MORPH_CLOSE, kernel)

# ตัดพื้นหลังออก
result = cv2.bitwise_and(image, image, mask=mask)

# แสดงภาพ
cv2.imshow("Original", image)
cv2.imshow("Mask", mask)
cv2.imshow("Result", result)
cv2.waitKey(0)
cv2.destroyAllWindows()
=======
# ตรวจสอบว่าโฟลเดอร์มีอยู่จริง
if not os.path.exists(input_folder):
    print(f"Error: Folder '{input_folder}' does not exist")
else:
    # หาไฟล์ภาพทั้งหมดในโฟลเดอร์
    valid_extensions = ['.jpg', '.jpeg', '.png', '.bmp']
    image_count = 0
    
    try:
        for filename in os.listdir(input_folder):
            # ตรวจสอบนามสกุลไฟล์
            file_extension = os.path.splitext(filename)[1].lower()
            if file_extension in valid_extensions:
                # สร้างพาธเต็มของไฟล์
                image_path = os.path.join(input_folder, filename)
                
                try:
                    # โหลดภาพ
                    input_image = Image.open(image_path)
                    
                    # ดึงชื่อไฟล์โดยไม่มีนามสกุล
                    image_name = os.path.splitext(filename)[0]
                    
                    # ลบพื้นหลัง
                    print(f"Removing background from {filename}...")
                    output_image = remove(input_image)
                    print("Background removed successfully!")
                    
                    # บันทึกผลลัพธ์
                    output_path = os.path.join(output_folder, f"{image_name}_no_background.png")
                    output_image.save(output_path)
                    print(f"Image saved as {output_path}")
                    print("-" * 30)
                    image_count += 1
                    
                except Exception as e:
                    print(f"Error processing {filename}: {str(e)}")
        
        print(f"All images processed! Total: {image_count} images")
    except Exception as e:
        print(f"Error accessing folder: {str(e)}")
>>>>>>> 76b8689845dc2440a98e0cc55aafb46f0342f366