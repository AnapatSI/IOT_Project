#ifndef STRING_ARRAY_H
#define STRING_ARRAY_H

#include <Arduino.h>

class StringArray {
  public:
    StringArray(int size); // ฟังก์ชันสร้างอาร์เรย์
    ~StringArray(); // ฟังก์ชันทำลายอาร์เรย์
    void set(int index, String value); // ตั้งค่าข้อมูลในอาร์เรย์
    String get(int index); // ดึงค่าจากอาร์เรย์
    int length(); // ดึงขนาดของอาร์เรย์

  private:
    String *arr; // อาร์เรย์เก็บข้อมูล
    int size; // ขนาดของอาร์เรย์
};

#endif