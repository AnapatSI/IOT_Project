import cv2
import numpy as np
from rembg import remove
from PIL import Image
import os

# สร้างโฟลเดอร์สำหรับเก็บผลลัพธ์
output_folder = "output_images"
if not os.path.exists(output_folder):
    os.makedirs(output_folder)
    print(f"Created folder: {output_folder}")

# กำหนดโฟลเดอร์ที่มีรูปภาพ
input_folder = "images_before"

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