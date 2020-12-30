<?php

/*

    Config

    #Step 1

    $richmenuId = 'bbbbbbbbbbbb';  <<<< แก้ไข bbbbbbbbbbbb เป็น Rich Menu ID

    การสร้าง richmenu ใช้ Tool : LINE Bot Designer
    การได้มาซึ่ง Rich Menu ID ใช้ Tool : POSTMAN https://www.postman.com/downloads/
    
    ลองอ่าน Blog นี้ https://medium.com/incubate-co-th/%E0%B8%88%E0%B8%B1%E0%B8%9A-postman-%E0%B8%A1%E0%B8%B2%E0%B8%AA%E0%B8%A3%E0%B9%89%E0%B8%B2%E0%B8%87-line-rich-menu-api-%E0%B8%87%E0%B9%88%E0%B8%B2%E0%B8%A2-%E0%B9%86-%E0%B8%94%E0%B9%89%E0%B8%A7%E0%B8%A2%E0%B8%95%E0%B8%B1%E0%B8%A7%E0%B9%80%E0%B8%AD%E0%B8%87-4598a24ea746
    อยากละเอียดมากขึ้น https://medium.com/linedevth/%E0%B9%80%E0%B8%81%E0%B9%88%E0%B8%87-rich-menu-%E0%B9%83%E0%B8%99-line-messaging-api-%E0%B9%83%E0%B8%AB%E0%B9%89%E0%B8%84%E0%B8%A3%E0%B8%9A%E0%B8%AA%E0%B8%B9%E0%B8%95%E0%B8%A3-6cf12b394f38


    หมายเหตุ: แนะนำยังไม่ต้องลงเขียนโค้ดก็ได้ลองยิง API ผ่าน postman
    https://developers.line.biz/en/docs/messaging-api/using-rich-menus/?fbclid=IwAR1eszqDRf2Bj7iZORUUlF_QTFryCxmDTm4q4Snqi5f7OjFkXpm_6I_JJto


    $token = 'aaaaaaaaaaaa'; <<<< แก้ไข aaaaaaaaaaaa Token ที่ได้จาก Messaging API



    #Step 2

    Cloud VPS แนะนำเยอะแยะมากมาย  แนะนำหลานเจ้า : digital ocean , cloudflare , vultr
    ผมใช้ digital ocean plan ธรรมดา

    หรือถ้าท่านมี ของท่านก็ใช้ได้เลย 

    $servername = "000.000.000.00";  แก้เป็น IP Server OR Server name Database ของท่าน 
    $username = "XXXX";  // username database
    $password = "PPPP"; // password database
    $dbname = "DATABASE_NAME"; // Database Name


    #Step 2.2

    เปิด Comment Check Connection
    ประมาณ บรรทัดที่ 63-66
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
    }  


*/

#Step 1
$richmenuId = 'bbbbbbbbbbbb';
$token = 'aaaaaaaaaaaa';

#Step 2.1
$servername = "000.000.000.00";  // IP Server OR Server name
$username = "XXXX";  // username database
$password = "PPPP"; // password database
$dbname = "DATABASE_NAME"; // Database Name

#Step 2.2
# Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }   





        # Array ค่า POST รับจากหน้า Controller
        #
        #   [Username] => {Username}
        #   [Password] => {Password}
        #   [userId] => {userId}
        #
        
        

        # ลบ /*  บรรทัดที่ 82
        /* 
        $rs = array();
        $sql = "SELECT * FROM Member WHERE Username ='{$_POST['Username']}' AND Password ='{$_POST['Password']}'  ;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {

                $check = TRUE;
            
                $rs = $row;
            
            }
        } else {

             $check = FALSE;

        }

        $response = array();

        if($check === TRUE ){


            $response['status'] = TRUE;
            
            # ส่งค่า userId ไป Function Set Rich Menu 
            getrichMenuId($_POST['userId']);
            
            echo json_encode($response);


        }else{
            
            $response['status'] = FALSE;

            echo json_encode($response);
        }
    */
    # ลบ /* บรรทัดที่ 121 


    
    
    # ตัวอย่างเรียก function
    # Comment 4 บรรนทัด ล่าง เพื่อปิดทดสอบ Function
    # ง่ายๆคือ ปิดบรรทัด 132-135

    // /*
    getrichMenuId($_POST);
    $response = array();
    $response['status'] = TRUE;
    echo json_encode($response);
    // */


        
    # get rich menu
    # สามารถนำไปใช้ จุดอื่นได้เช่น Webhook ไว้เปลี่ยนไปเปลี่ยนมาอีกที    
   function getrichMenuId($data = array()) {


        #$data['userId'] << คือค่าที่ได้จาก Get LIFF PROFILE ส่งจาก หน้า Login
        ##เรียกใช้ richmenu id ด้วย curl

        $urlUID = 'https://api.line.me/v2/bot/user/' . $data['userId'] . '/richmenu/richmenu-' . $richmenuId;
        $headersUID = array('Authorization: Bearer '. $token );
        
        $posts = ''; // Set ให้ป็นค่าว่าง

        $chUID = curl_init($urlUID);
        curl_setopt($chUID, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($chUID, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chUID, CURLOPT_POSTFIELDS, $posts);
        curl_setopt($chUID, CURLOPT_HTTPHEADER, $headersUID);
        curl_setopt($chUID, CURLOPT_FOLLOWLOCATION, 1);
        $resultUID = curl_exec($chUID);
        curl_close($chUID);
    }
