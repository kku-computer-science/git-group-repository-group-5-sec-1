# Changelog

> บันทึกการเปลี่ยนแปลงของโครงการนี้

## **[2025-03-11]**

### การปรับปรุงฟีเจอร์

### (PBL: *As an administrative staff, I want to keep the project funding information up-to-date.*)
## Database
- เพิ่มตาราง funds_type => เก็บข้อมูลประเภททุน
- เพิ่มตาราง funds_category => เก็บข้อมูลลักษณะทุน
- แก้ไขตาราง funds => ลบคอลัมน์ที่ไม่ได้ใช้ออก และเพิ่มการเก็บ foreign key จากตาราง funds_category
- เพิ่มตาราง responsible_dapartment => เก็บข้อมูลหน่วยงานที่รับผิดชอบทั้งภายนอกและภายใน
- เพิ่มตาราง responsible_dapartment_research_projects => เก็บข้อมูลหน่วยงานที่รับผิดชอบของแต่ละโครงการวิจัย
- แก้ไขตาราง research_projects => ลบคอลัมน์ที่ไม่ถูกใช้งานอฃอกและเพิ่มคอลัมน์ใหม่เพื่อรองรับการทำงานของฟังก์ชันและ User Interface ที่ออก

## User Interface and Business Logic
- แก้ไข UI หน้า 'เพิ่มทุนวิจัย' 'แก้ไขทุนวิจัย' และ 'ดูรายละเอียดทุนวิจัย' ให้ตรงกับคอลัมน์ที่เก็บข้อมูลในแต่ละตาราง
- เพิ่ม function เพื่อกรองลักษณะทุนตามประเภททุนที่เลือก
- แก้ไข UI หน้า 'เพิ่มโครงการวิจัย' เพิ่ม drop down เพื่อให้ผู้ใช้สามารถใช้ประเภททุนและลักษณะทุน กรองทุนวิจัยให้เลือกได้ง่ายขึ้น
- แก้ไข UI หน้า 'เพิ่มโครงการวิจัย' เพิ่ม check box การแสดงงบประมาณ ผู้ใช้สามารถเลือกได้ว่าต้องการแสดงงบประมาณที่หน้าแสดงข้อมูลโครงการวิจัยให้ผู้ใช้ทั่วไปเห็นหรือไม่
- แก้ไข UI หน้า 'โครงการบริการวิชาการ/ โครงการวิจัย' ช่องข้อมูล งบประมาณจัดสรร แสดง'งบประมาณ' หรือ แสดง 'ไม่ประสงค์เผยแพร่'
- แก้ไข UI หน้า 'เพิ่มโครงการวิจัย' หน่วยงานที่รับผิดชอบ(ภายใน) ดึงข้อมูลจาก responsible_dapartment ที่เป็นหน่วยงานภายใน 
- แก้ไข UI หน้า 'เพิ่มโครงการวิจัย' หน่วยงานที่รับผิดชอบ(ภายนอก) ดึงข้อมูลจาก responsible_department ที่เป็นหน่วยงานภายนอก สามารถเพิ่ม และเก็บลงตาราง responsible_department ได้
- เพิ่มฟังก์ชัน เก็บหน่วยงานที่รับผิดชอบในโครงการลงตาราง responsible_dapartment_research_projects
- แก้ไขสถานะโครงการ มี 2 สถานะ คือ 'กำลังดำเนินการ' และ 'ยื่นขอ'
- หน้า 'เพิ่มโครงการวิจัย' เพิ่มฟังก์ชัน ไม่สามารถเลือกวันสิ้นสุดโครงการ ก่อนวันเริ่มต้นโครงการได้ 
- หน้า 'เพิ่มโครงการวิจัย' เพิ่มฟังก์ชัน หากวันปัจจุบัน เลยกำหนดวันสิ้นสุดโครงการมาแล้ว สถานะจะเป็น 'ปิดโครงการ' และไม่สามารถแก้ไขได้ หากต้องการแก้ไขสถานะ ต้องแก้ไข 'วันสิ้นสุดโครงการ' ไม่ให้เลยกำหนดก่อน
- หน้า 'เพิ่มทุนวิจัย' และ 'เพิ่มโครงการวิจัย' เพิ่ม '*' สีแดง เพื่อบอกให้ผู้ใช้ทราบว่าต้องระบุข้อมูลในช่องนั้นเสมอ หากไม่ระบุ จะไม่สามารถเพิ่มข้อมูลได้
- แก้ไข pop up แจ้งเตือนผลลัพธ์การดำเนินการให้เป็นภาษาไทย 
- แก้ไข UI หน้า 'โครงการบริการวิชาการ/ โครงการวิจัย' แสดงหน่วยงานที่รับผิดชอบ ภายในและภายนอก
- แก้ไข UI หน้า 'โครงการบริการวิชาการ/ โครงการวิจัย' แสดงผู้รับผิดชอบโครงการ(ร่วม) ภายในและภายนอก 
- แก้ไข UI หน้า 'แก้ไขทุนวิจัย' และ 'ดูรายละเอียดทุนวิจัย' ให้สอดคล้องกับฟังก์ชันและตารางข้อมูลที่แก้ไข
- แก้ไข UI หน้า 'แก้ไขโครงการวิจัย' และ 'ดูรายละเอียดโครงการวิจัย' ให้สอดคล้องกับฟังก์ชันและตารางข้อมูลที่แก้ไข
                                      
 
