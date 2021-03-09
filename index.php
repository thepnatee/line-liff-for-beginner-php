<!DOCTYPE html>


<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

    <title>Login</title>

    <script src="https://restapi.tu.ac.th/tuapi/resources/assets/js/plugin/webfont/webfont.min.js"></script>
    <link rel="stylesheet" href="https://restapi.tu.ac.th/tuapi/resources/assets/css/azzara.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>


</head>


<script type="text/javascript">

    window.onload = function() {

        // Clear ค่าทุกครั้งทที่เปิดขึ้นมาเลยจ้า
        
        document.getElementById('Username').value = '';
        document.getElementById('Password').value = '';
       

        // เรียนรู้เพิ่มเติมได้ที่ https://github.com/line/line-liff-v2-starter
        // รู้จากกับ LIFF https://medium.com/linedevth/introduction-to-liff-7d708e2f42ec


        // เพิ่มต้นสำคัญมากกก คุณต้อง liff.init() ก่อนเสมอ
        initializeLiff();
        

        // liff init ก็ใช้ liff id ด้วยห้ามลืมเลยนะะะะะะะ
        // คำถามคือ function อะไรเกิดก่อน  liff init ได้บ้าง ตามนี้เลย
        // liff.ready
        // liff.getOS()
        // liff.getLanguage()
        // liff.getVersion()
        // liff.getLineVersion()
        // liff.isInClient()  << ตรวจว่า production เราเป็น liff browser มั้ย ถ้าใช้ จะเป็น true ถ้าเป็น external จะเป็น false
        // liff.closeWindow()  << ใช้บ่อยเชี่ยาละ ปิด liff
    };


    function initializeLiff(myLiffId) {
        $("#form_username").hide();
        $("#load").show();

       
        // xxxxxxxxxxxxxxxxx แก้ เป็น LIFF ID ที่ได้จาก LINE LOGIN -> LIFF ID Copy มาว่างใส่ได้เลย
        liff.init({
                liffId: "xxxxxxxxxxxxxxxxx"
            })
            .then(() => {
                $("#form_username").show();
                $("#load").hide();

                liff.ready.then(async () => {

                    liff.getProfile().then(function (profile) {
                            //  {
                            // "userId":"U4af4980629...",
                            // "displayName":"Brown",
                            // "pictureUrl":"https://profile.line-scdn.net/abcdefghijklmn",
                            // "statusMessage":"Hello, LINE!"
                            // }
                            
                            document.getElementById('userId').value = profile.userId;
                            document.getElementById('displayName').value = profile.displayName;
                            document.getElementById('pictureUrl').value = profile.pictureUrl;
                            document.getElementById('statusMessage').value = profile.statusMessage;
                            


                            // เมื่อทำงานเสร็จถึงขึ้น GET PROFILE ได้แล้ว ก็สามารถใช้ปุ่ม Sign in ได้แล้ว
                            sendMessage();

                        
                        }).catch(function (error) {
                            
                            window.alert('Error getting profile: ' + error);
                        
                    });
                })
            })
            .catch((err) => {

                $("#form_username").show();
                $("#load").hide();

                window.alert('Error sending message: ' + error);

            });

    }

    function sendMessage () {

        document.getElementById('sendMessageButton').addEventListener('click', function() {


        // ขั้นตอนนี้ แค่เอา Value Username กับ Password ส่งไป  Controller เท่านั้น   
        const Username = document.getElementById('Username').value;
        const Password = document.getElementById('Password').value;

        var myData = $('#login_form').serialize();

        if (Username === '' || Password === '') {

            $('#warning').modal('show');
        
        } else {
            document.getElementById("sendMessageButton").disabled = true;

            // ช่วยให้ UX ดีขึ้นหน่อย คือมีตัว Loader ด้วย
            $("#form_username").hide();
            $("#load").show();


            // ส่งค่า user password ไปที่ไฟล์ Controller.php โดยใช้ ajax เป็น method post นะลอง debug ดู
            // console.log(myData);
            // alert(myData);

            // ไป Config ต่อที่ Controller.php
            $.ajax({
                type: "POST",
                url: "./Controller.php/",
                data: myData,
                success: function(response) {


                    var rs = JSON.parse(response);

                    //เมื่อได้ true มา ไม่รอช้า ส่ง pushmessage ไปหน้าห้องแชทของเรา และทำการ ขึ้นว่า ซักเสร็จ และ ปิด liff นั้นๆ จบ

                    if (rs.status === true) {

                        liff.sendMessages([{

                            'type': 'text',
                            'text': "Sign in"

                        }]).then(function() {


                            // มาถึงขั้นนี้แปลว่า  LIFF เราเปลี่ยนแล้วแหละ ลองเชคดู

                            window.alert('Message sent success!');
                            liff.closeWindow();

                        }).catch(function(error) {

                            window.alert('Error sending message: ' + error);
                            liff.closeWindow();

                        });

                        $("#form_username").show();
                        $("#load").hide(); 

                    } else {
                        $("#form_username").show();
                        $("#load").hide();

                        window.alert(rs.message);
                        document.getElementById("sendMessageButton").disabled = false;
                    }
                }

            });

        }
        });
    }
</script>


<body class="login">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <h3 class="text-center">Please sign in</h3>
            <form id="login_form" name="login_form" method="post" onsubmit="return false">
                <div id="load" name="load" style="display: none;">
                    <center>
                        <img src="https://tu.ac.th/templates/site/resx/loader.svg" alt="Loading..." />
                        <div>Loading..</div>
                    </center>
                </div>

                <div id="form_username" name="form_username" class="login-form">
                    <div class="form-group form-floating-label">
                        <input id="Username" name="Username" type="text" autocomplete="text" class="form-control input-border-bottom" required>
                        <label for="Username" class="placeholder">Username</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="Password" name="Password" type="password" autocomplete="password" class="form-control input-border-bottom" required>
                        <label for="Password" class="placeholder">Password</label>
                    </div>
                        
                    <div class="form-action mb-3">
                        <input id="userId" name="userId" type="hidden" >
                        <input id="pictureUrl" name="pictureUrl" type="hidden" >
                        <input id="displayName" name="displayName" type="hidden"  >
                        <input id="statusMessage" name="statusMessage" type="hidden" >
                        <button id="sendMessageButton" class="btn btn-info btn-rounded btn-login"><i class="fas fa-lock"></i> Sign in</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <div class="modal fade" name="warning" id="warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-warning">
                    <h5 class="modal-title" id="exampleModalLabel">คำเตือน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    กรุณากรอกข้อมูลของท่านให้ถูกต้อง
                </div>
            </div>
        </div>
    </div>

    <script src="https://static.line-scdn.net/liff/edge/versions/2.5.0/sdk.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="https://restapi.tu.ac.th/tuapi/resources/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="https://restapi.tu.ac.th/tuapi/resources/assets/js/core/popper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</body>

</html>
