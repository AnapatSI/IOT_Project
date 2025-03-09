import cv2
import numpy as np
from rembg import remove
from PIL import Image

# โหลดภาพ
image_path = "D:/IOT_Project-main/IOT_Project-main/IMG_1799.jpg"
input_image = Image.open(image_path)

# ลบพื้นหลัง (rembg จัดการโหลดโมเดลและตัดภาพพื้นหลังให้โดยอัตโนมัติ)
print("Removing background...")
output_image = remove(input_image)
print("Background removed successfully!")

# บันทึกผลลัพธ์
output_path = "output_no_background.png"  # บันทึกเป็น PNG เพื่อรักษา alpha channel
output_image.save(output_path)
print(f"Image saved as {output_path}")