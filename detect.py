from ultralytics import YOLO

model = YOLO("best.pt")

results = model("crumpled-cardboard-mail-box.png")
results[0].show()