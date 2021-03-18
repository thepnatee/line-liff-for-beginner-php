<?php

// AAA = แก้ไขเป็น IP DNS
// BBB = แก้เป็น Username Database
// CCC = แก้เป็น Password Database
// DDD = แก้เป็นชื่อ Database

# Create connection
$conn = new mysqli("AAA", "BBB", "CCC", "DDD");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}   

        # Array ค่า POST รับจากหน้า Controller
        #
        #   [Username] => {Username}
        #   [Password] => {Password}
        #   [userId] => {userId}
        #

        # ลบ /*  บรรทัดที่ 82

        $rs = array();
        $sql = "SELECT * FROM Register WHERE userName ='{$_POST['Username']}' AND passWord ='{$_POST['Password']}'  ;";
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
            getrichMenuId();
            echo json_encode($response);


        }else{
            
            $response['status'] = FALSE;

            echo json_encode($response);
        }

   function getrichMenuId() {
    // แก้ ID Rich Menu
    $richmenuId = '{Rich Menu ID}';
    // Copy Access Token จาก LINE Developer Console Messaging API   
    $token = '{Access Token}';
    
        #$data['userId'] << คือค่าที่ได้จาก Get LIFF PROFILE ส่งจาก หน้า Login
        ##เรียกใช้ richmenu id ด้วย curl
        $urlUID = 'https://api.line.me/v2/bot/user/' . $_POST['userId'] . '/richmenu/richmenu-' . $richmenuId;
        $headersUID = array( 'Authorization: Bearer '. $token );
        
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
