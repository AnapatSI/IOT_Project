#include "StringArray.h"

StringArray::StringArray(int size) {
  this->size = size;
  arr = new String[size]; // สร้างอาร์เรย์ของ String
}

StringArray::~StringArray() {
  delete[] arr; // ลบอาร์เรย์ออกจากหน่วยความจำ
}

void StringArray::set(int index, String value) {
  if (index >= 0 && index < size) {
    arr[index] = value;
  }
}

String StringArray::get(int index) {
  if (index >= 0 && index < size) {
    return arr[index];
  }
  return "";
}

int StringArray::length() {
  return size;
}