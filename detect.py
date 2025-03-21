from ultralytics import YOLO

model = YOLO("yolo11n.pt")

results = model("b1okue.png")
results[0].show()