from ultralytics import YOLO

# Load a model
model = YOLO("yolo11n.pt")

# Train the model
train_results = model.train(
    data=r"C:\Users\User\Documents\GitHub\IOT_Project\dataset\data.yaml",  # path to dataset YAML
    epochs=100,  # number of training epochs
    imgsz=640,  # training image size
    device="0",  # device to run on, i.e. device=0 or device=0,1,2,3 or device=cpu
    workers=0

)

# Evaluate model performance on the validation set
metrics = model.val()

