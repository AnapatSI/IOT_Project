import cv2
import numpy as np
import gitmap
import pickle

# โหลดโมเดลที่ฝึกไว้
model_filename = "ml_model.pkl"
with open(model_filename, "rb") as file:
    model = pickle.load(file)

def calculate_damage_percentage(image1_path, image2_path):
    """ ใช้ GitMap เปรียบเทียบภาพและคำนวณเปอร์เซ็นต์ความเสียหาย """
    img1 = cv2.imread(image1_path, cv2.IMREAD_GRAYSCALE)
    img2 = cv2.imread(image2_path, cv2.IMREAD_GRAYSCALE)

    if img1 is None or img2 is None:
        raise ValueError("ไม่สามารถโหลดภาพได้")

    # ใช้ GitMap วิเคราะห์ความแตกต่าง
    diff_map = gitmap.compute(img1, img2)

    # แปลงค่า GitMap เป็นเปอร์เซ็นต์พื้นที่ที่เปลี่ยนแปลง
    threshold = 50  # ค่าที่ใช้แยกรอยบุบ (อาจต้องปรับแต่ง)
    damage_area = np.sum(diff_map > threshold)
    total_area = diff_map.size
    damage_percentage = (damage_area / total_area) * 100

    return damage_percentage

def predict_damage(damage_percentage):
    """ ใช้โมเดล Machine Learning ทำนายว่ากล่องบุบหรือไม่ """
    prediction = model.predict([[damage_percentage]])
    return "Damaged" if prediction == 1 else "Normal"

if __name__ == "__main__":
    # เปรียบเทียบภาพต้นฉบับ (กล่องปกติ) กับภาพที่ได้รับจาก ESP32-CAM
    normal_image = "dataset/box_normal.jpg"
    damaged_image = "uploads/box_damaged.jpg"

    damage_percentage = calculate_damage_percentage(normal_image, damaged_image)
    result = predict_damage(damage_percentage)

    print(f"Damage Percentage: {damage_percentage:.2f}%")
    print(f"Result: {result}")
