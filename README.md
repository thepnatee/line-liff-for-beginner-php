# LINE Chat Bot for Beginner - PHP
line-chatbot-webhook-for-beginner-php
### Tools ###
- PHP ^7.XX
- Terminal หรือใคร ใช้ Window ก็ Git ได้เลย
- Heroku (Free Hosting https://devcenter.heroku.com/start)
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
- App Name : {ชื่อภาษอังกฤษตัวเอง}-line-chatbot-lab101 => Ex. thepnatee-line-chatbot-lab101
- Choose a region : United States
- Click Create App

### Step 3 ###
- Create Project ChatBotLab101
- cd ChatBotLab101
- git clone https://github.com/thepnatee/line-chatbot-webhook-for-beginner-php.git
- cd line-chatbot-webhook-for-beginner-php
- Open File : Index.php แก้ 'Channel access token'

### Step 4 : Copy Script. ###
- cd line-chatbot-webhook-for-beginner-php (ตรวจสอบว่ายังอยู่ตำแหน่งไฟล์นี้นะ)
- Copy 5 บรรทัดแล้ววางบน Terminal
    - git init
    - heroku git:remote -a thepnatee-line-chatbot-lab101
    - git add .
    - git commit -am "make it better"
    - git push heroku master

### Step 5 ###
- หน้า Heroku จะมีคำว่า Open App ก็จิ้มแรงๆ จะได้ URL : https://thepnatee-line-chatbot-lab101.herokuapp.com/
- Copy URL แก้ใน Messaging API : ตรง Webhook
- กด Verify รัวๆ กระโดนตบ 20 ครั้ง จะได้ Message ขึ้นว่า ซักเสร็จ ตากต่อได้เลย. เอ้ยย Success  
- กดเปิด Use Webhook 
- เริ่มใช้งานBot ได้เลย


### Option ###
Config LINE Notify บรรทัดที่ 12
Config Dialogflow บรรทัดที่ 157
