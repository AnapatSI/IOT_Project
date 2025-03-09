import numpy as np
import pandas as pd
import pickle
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier

# ตัวอย่างข้อมูล: เปอร์เซ็นต์รอยบุบ (X) กับผลลัพธ์ (Y)
# 0 = Normal, 1 = Damaged
data = {
    "damage_percentage": [2.3, 5.1, 8.7, 12.4, 20.5, 35.7, 50.2, 75.1, 90.3],
    "label": [0, 0, 0, 1, 1, 1, 1, 1, 1]
}

df = pd.DataFrame(data)

# แยก features (X) และ labels (Y)
X = df[["damage_percentage"]]
y = df["label"]

# แบ่งข้อมูลเป็นชุด train และ test
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# สร้างและฝึกโมเดล
model = RandomForestClassifier(n_estimators=10, random_state=42)
model.fit(X_train, y_train)

# บันทึกโมเดลที่ฝึกแล้ว
model_filename = "ml_model.pkl"
with open(model_filename, "wb") as file:
    pickle.dump(model, file)

print("Training complete. Model saved as 'ml_model.pkl'")
