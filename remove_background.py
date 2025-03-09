import cv2
import sys
import numpy as np

# อ่านภาพจาก path ที่ส่งมา
image_path = sys.argv[1]
image = cv2.imread(image_path)

# ใช้ OpenCV เพื่อแยกพื้นหลังออก (ใช้เทคนิคการ threshold หรือ background subtraction)
gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
_, thresh = cv2.threshold(gray, 120, 255, cv2.THRESH_BINARY_INV)

# ลบพื้นหลัง (ใช้ contour หรือเทคนิคต่างๆ)
mask = cv2.bitwise_not(thresh)
result = cv2.bitwise_and(image, image, mask=mask)

# บันทึกภาพที่ลบพื้นหลังแล้ว
output_path = 'output_' + image_path
cv2.imwrite(output_path, result)

# แสดงผลลัพธ์
print(f"Background removed. Saved as {output_path}")
