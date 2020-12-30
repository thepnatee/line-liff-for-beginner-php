# LINE LIFF for Beginner - PHP
line-chatbot-webhook-for-beginner-php
### Tools ###
- PHP ^7.XX
- Terminal หรือใคร ใช้ Window ก็ Git ได้เลย
- Heroku (Free Hosting https://devcenter.heroku.com/start). ** ติดตั้ง Heroku PHP ให้เรียบร้อย
- GIT (https://git-scm.com/)
- Brew (https://brew.sh/) สำหรับ Install Heroku

### Step 1 ###
- Register Heroku https://signup.heroku.com/login
- Confirm Email
- Getting Started on Heroku with PHP : $brew install heroku/brew/heroku
- Login : $heroku login
- ระบบจะเปิด Browser เพื่อ Login ก็จะเป็นการเชื่อมต่อ Heroku สำเร็จ

### Step 2 ###
- สร้าง Project บน Heroku เลือก Create New App
- App Name : {ชื่อภาษอังกฤษตัวเอง}-liff-chatbot-lab2 => Ex. thepnatee-liff-chatbot-lab2
- Choose a region : United States
- Click Create App

### Step 3 ###
- Create Project ChatBotLab2
- cd ChatBotLab2
- git clone https://github.com/thepnatee/line-liff-for-beginner-php.git
- cd line-liff-for-beginner-php.git
- Open File : Index.php แก้ 'liffId'

### Step 4 : Copy Script. ###
- cd line-liff-for-beginner-php.git (ตรวจสอบว่ายังอยู่ตำแหน่งไฟล์นี้นะ)
- Copy 5 บรรทัดแล้ววางบน Terminal
    - git init
    - heroku git:remote -a thepnatee-line-chatbot-lab101
    - git add .
    - git commit -am "make it better"
    - git push heroku master

### Step 5 ###
- หน้า Heroku จะมีคำว่า Open App ก็จิ้มแรงๆ จะได้ URL : https://thepnatee-liff-chatbot-lab2.herokuapp.com/
    
    โครงสร้างจะมีดังนี้
    1. Index.php สำหรับหน้า Login
    2. Controller.php ต่อ Database
    3. ของแถม Webhook.php.
    
### Step6 ###
- สร้าง Channel LINE LOGIN
- เชื่อมต่อกับ Messagoing API 
- สร้าง LIFF โดยการ นำ https://thepnatee-liff-chatbot-lab2.herokuapp.com/ ไปสร้างเป็น หน้า Login
- กดปุ่ม Developing ให้เป้น Published
- นำ LIFF URL ที่ได้ Copy แล้วลองไปวางใน LINE Channel Bot ของตัวเอง
    
        // เรียนรู้เพิ่มเติมได้ที่ https://github.com/line/line-liff-v2-starter
        // รู้จากกับ LIFF https://medium.com/linedevth/introduction-to-liff-7d708e2f42ec

