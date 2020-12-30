<?php
echo 'Welcome Webhook';
date_default_timezone_set("Asia/Bangkok"); //config date


##################################################################################
#                แก้ไขตรงนี้                                                        #
$access_token = 'Channel access token'; // Access Token จาก Channel access token #
#                                                                                #
##################################################################################

$line_notify = 'Token Notify'; // Access Token จาก LINE Bot Notify

// Get POST body content
$content = file_get_contents('php://input');


// Parse JSON
$events = json_decode($content, true);

if (!is_null($events['events'])) {
    $message = array();
    $message['lawmessage'] = $content;
    $message['userLineToken'] = $access_token; // Access Token จาก Channel LINE DEV  --> แก้บรรทัดที่ 4
    $message['line_notify'] = $line_notify;  // Access Token จาก LINE Bot Notify  --> แก้บรรทัดที่ 5
    foreach ($events['events'] as $event) {

        $message['replyToken'] = $event['replyToken']; // Reply Token มีอายุ 30 วินาที ใช้ได้เพียงหนึ่งครั้ง
        $message['userId'] = $event['source']['userId']; // userId ID ของ User ต่อ 1 Provider เดียวกัน
        $message['eventType'] = $event['type']; // ประเภทของข้อความ
        $message['messageType'] = $event['message']['type'];
        $message['message'] = $event['message']['text']; // ข้อความประเภท text
        $message['messageId'] = $event['message']['id']; // ID ของข้อความ 
        $message['messageTitle'] = $event['message']['title']; //  ค่า Title จาก location 
        $message['messageAddress'] = $event['message']['address']; // ค่า Address จาก location  
        $message['messageLatitude'] = $event['message']['latitude']; // ค่า Latitude จาก location  
        $message['messagelongitude'] = $event['message']['longitude']; // ค่า longitude จาก location 
        $message['messagestickerId'] = $event['message']['stickerId']; // ค่า stickerId จาก Sticker 
        $message['messagepackageId'] = $event['message']['packageId']; // ค่า packageId จาก Sticker 
        $message['postbackData'] = $event['postback']['data']; // postback value ที่ แนบมาจาก Post Back Data 
    }


    /*
        แยกประเภท  Message ต่างๆ 
        
        case text  กรณีรับข้อความเป็น text 
        case 'sticker': กรณีรับข้อความเป็น sticker 
        case 'image': กรณีรับข้อความเป็น image
        case 'location': กรณีรับข้อความเป็น location
    
        {
        "destination": "xxxxxxxxxx",
        "events": [
            {
            "replyToken": "nHuyWiB7yP5Zw52FIkcQobQuGDXCTA",
            "type": "message",
            "mode": "active",
            "timestamp": 1462629479859,
            "source": {
                "type": "user",
                "userId": "U4af4980629..."
            },
            "message": {
                "id": "325708",
                "type": "text",
                "text": "Hello, world! (love)",
                "emojis": [
                {
                    "index": 14,
                    "length": 6,
                    "productId": "5ac1bfd5040ab15980c9b435",
                    "emojiId": "001"
                }
                ]
            }
            }
        ]
        }
    */

    if ($message['eventType'] === 'message') {
        switch ($message['messageType']) {
            case 'text':
                sentLineMessage_text($message);
                break;
            case 'sticker':
                sentLineMessage_sticker($message);
                break;
            case 'image':
                sentLineMessage_image($message);
                break;
            case 'location':
                sentLineMessage_location($message);
                break;
            default:
                sentLineMessage_noncase($message);
                break;
        }
    } elseif ($message['eventType'] === 'follow') {
        #สัญญาณประเภทนี้เกิดจากที่ผู้ใช้เพิ่ม Bot เราเป็นเพื่อน
        followEvent($message);
    } elseif ($message['eventType'] === 'unfollow') {
        #สัญญาณประเภทนี้เกิดจากที่ผู้ใช้ Block LINE เรา
        UnfollowEvent($message);
    } elseif ($message['eventType'] === 'postback') {
        postbackData($message);
    }
}

# Message Evenet  : Text

function sentLineMessage_text($message = array())
{

    if ($message) {


        if ($message['message'] == 'สวัสดี') {

            $messages = [
                'type' => 'text',
                'text' => 'สวัสดีครับ'
            ];
            $data = [
                'replyToken' => $message['replyToken'],
                'messages' => [$messages],
            ];


            $message['post'] = json_encode($data);
            apiPost($message);
            

            // Config LINE Notify 
            // ส่ง Message  ไป Function แจ้งเตือน
            // sendNotify($message);

        }elseif($message['message'] == '#raw'){

        } elseif($message['message'] == '#Out'){

          getrichMenuId($message);

            // Event Objects RAW
            $messages = [
                'type' => 'text',
                'text' => 'ท่านได้ยกเลิกการเชื่อมต่อเรียบร้อย You have successfully disconnected.'
            ];
            $data = [
                'replyToken' => $message['replyToken'],
                'messages' => [$messages],
            ];


            $message['post'] = json_encode($data);
            apiPost($message);
        } elseif($message['message'] == '#flex'){

            # https://developers.line.biz/flex-simulator/
            # ใช้ในการวาด Flex
            $messages = '
                                {
                                "replyToken":"' . $message['replyToken'] . '",
                                "messages":[


                                {
                                    "type": "flex",
                                    "altText": "Demo Flex",
                                    "contents": 
                                    
                                    {
                                      "type": "bubble",
                                      "hero": {
                                        "type": "image",
                                        "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/01_1_cafe.png",
                                        "size": "full",
                                        "aspectRatio": "20:13",
                                        "aspectMode": "cover",
                                        "action": {
                                          "type": "uri",
                                          "label": "Line",
                                          "uri": "https://linecorp.com/"
                                        }
                                      },
                                      "body": {
                                        "type": "box",
                                        "layout": "vertical",
                                        "contents": [
                                          {
                                            "type": "text",
                                            "text": "Brown Cafe",
                                            "weight": "bold",
                                            "size": "xl",
                                            "contents": []
                                          },
                                          {
                                            "type": "box",
                                            "layout": "baseline",
                                            "margin": "md",
                                            "contents": [
                                              {
                                                "type": "icon",
                                                "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png",
                                                "size": "sm"
                                              },
                                              {
                                                "type": "icon",
                                                "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png",
                                                "size": "sm"
                                              },
                                              {
                                                "type": "icon",
                                                "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png",
                                                "size": "sm"
                                              },
                                              {
                                                "type": "icon",
                                                "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png",
                                                "size": "sm"
                                              },
                                              {
                                                "type": "icon",
                                                "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gray_star_28.png",
                                                "size": "sm"
                                              },
                                              {
                                                "type": "text",
                                                "text": "4.0",
                                                "size": "sm",
                                                "color": "#999999",
                                                "flex": 0,
                                                "margin": "md",
                                                "contents": []
                                              }
                                            ]
                                          },
                                          {
                                            "type": "box",
                                            "layout": "vertical",
                                            "spacing": "sm",
                                            "margin": "lg",
                                            "contents": [
                                              {
                                                "type": "box",
                                                "layout": "baseline",
                                                "spacing": "sm",
                                                "contents": [
                                                  {
                                                    "type": "text",
                                                    "text": "Place",
                                                    "size": "sm",
                                                    "color": "#AAAAAA",
                                                    "flex": 1,
                                                    "contents": []
                                                  },
                                                  {
                                                    "type": "text",
                                                    "text": "Miraina Tower, 4-1-6 Shinjuku, Tokyo",
                                                    "size": "sm",
                                                    "color": "#666666",
                                                    "flex": 5,
                                                    "wrap": true,
                                                    "contents": []
                                                  }
                                                ]
                                              },
                                              {
                                                "type": "box",
                                                "layout": "baseline",
                                                "spacing": "sm",
                                                "contents": [
                                                  {
                                                    "type": "text",
                                                    "text": "Time",
                                                    "size": "sm",
                                                    "color": "#AAAAAA",
                                                    "flex": 1,
                                                    "contents": []
                                                  },
                                                  {
                                                    "type": "text",
                                                    "text": "10:00 - 23:00",
                                                    "size": "sm",
                                                    "color": "#666666",
                                                    "flex": 5,
                                                    "wrap": true,
                                                    "contents": []
                                                  }
                                                ]
                                              }
                                            ]
                                          }
                                        ]
                                      },
                                      "footer": {
                                        "type": "box",
                                        "layout": "vertical",
                                        "flex": 0,
                                        "spacing": "sm",
                                        "contents": [
                                          {
                                            "type": "button",
                                            "action": {
                                              "type": "uri",
                                              "label": "CALL",
                                              "uri": "https://linecorp.com"
                                            },
                                            "height": "sm",
                                            "style": "link"
                                          },
                                          {
                                            "type": "button",
                                            "action": {
                                              "type": "uri",
                                              "label": "WEBSITE",
                                              "uri": "https://linecorp.com"
                                            },
                                            "height": "sm",
                                            "style": "link"
                                          },
                                          {
                                            "type": "spacer",
                                            "size": "sm"
                                          }
                                        ]
                                      }
                                  
                                    }

                                  }

                                 ]
                                }
                      ';


            $message['post'] = $messages;
            apiPost($message);

        } else {

            /*
            Forward Dialogflow Webhook นำ ID
            แก้ไข {Dialog Flow ID} ที่ได้จาก Dialogflow
            */

         
            $url = "https://bots.dialogflow.com/line/{Dialog Flow ID}/webhook";

            $headers = getallheaders();
            $headers['Host'] = "bots.dialogflow.com";
            $headers['Content-Type'] = "application/json;charset=UTF-8";
            $json_headers = array();
            foreach ($headers as $k => $v) {
                $json_headers[] = $k . ":" . $v;
            }
            $inputJSON = file_get_contents('php://input');
            // ส่วนของการส่งการแจ้งเตือนผ่านฟังก์ชั่น cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $inputJSON);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $json_headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 0 | 2 ถ้าเว็บเรามี ssl สามารถเปลี่ยนเป้น 2
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // 0 | 1 ถ้าเว็บเรามี ssl สามารถเปลี่ยนเป้น 1
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
}

    # get rich menu
    function getrichMenuId($message = array()) {

      ##เรียกใช้ richmenu id ด้วย curl

      $richmenuId = 'bbbbbbbbbbbb'; // rich menu 2  
      $token = 'aaaaaaaaaaaa';
      $urlUID = 'https://api.line.me/v2/bot/user/' . $message['userId'] . '/richmenu/richmenu-' . $richmenuId;
      $headersUID = array('Authorization: Bearer '. $message['userLineToken'] );
      
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



 # Message Evenet  : Sticker

function sentLineMessage_sticker($message = array()) {
    //ตอบกลับด้วย sticker id และ package id
    $messages = [
        'type' => 'sticker',
        'packageId' => 11537,
        'stickerId' => 52002736
    ];
    $data = [
        'replyToken' => $message['replyToken'],
        'messages' => [$messages],
    ];

    $message['post'] = json_encode($data);
    apiPost($message);
}

# Message Evenet  : Image 
function sentLineMessage_image($message = array()) {
    $messages = [
        'type' => 'text',
        'text' => json_encode($message)
    ];
    $data = [
        'replyToken' => $message['replyToken'],
        'messages' => $messages,
    ];

    $message['post'] = json_encode($data);
    apiPost($message);
}

# Message Evenet  : Location 

function sentLineMessage_location($message = array()) {
    $messages = [
        'type' => 'text',
        'text' => json_encode($message)
    ];
    $data = [
        'replyToken' => $message['replyToken'],
        'messages' => [$messages],
    ];


    $message['post'] = json_encode($data);
    apiPost($message);
}


# Message Evenet  : Non case out off service

function sentLineMessage_noncase($message = array())
{
    $messages = [
        'type' => 'text',
        'text' => 'กรุณาพิมพ์ข้อความของท่านให้ถูกต้องด้วยคะ เช่น ข้อความ รูปภาพ หรืแ ตำแหน่งที่ตั้งของท่าน ' . "\r\n" . 'กรณีส่งเป็น ไฟล์เอกสาร วีดีโอ และ เสียง สามารถส่งมาได้ที่ email นี้ค่ะ'
    ];
    $data = [
        'replyToken' => $message['replyToken'],
        'messages' => [$messages],
    ];
    $message['post'] = json_encode($data);
    apiPost($message);
}

 # Message Type Event  : Postback

 function postbackData($message = array()) {


    $messages = [
        'type' => 'text',
        'text' => 'Data ข้อมูลของคุณคือ' . $message['postbackData']
    ];
    $data = [
        'replyToken' => $message['replyToken'],
        'messages' => [$messages],
    ];

    $message['post'] = json_encode($data);
    apiPost($message);
}

# Post API Reply

function apiPost($message = array())
{
    $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $message['userLineToken']);
    $url = 'https://api.line.me/v2/bot/message/reply';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message['post']);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    echo $result . "\r\n";
}

#Follow Event ประเภทนี้เกิดจากที่ผู้ใช้เพิ่มเราเป็นเพื่อน หรือ unblock

function followEvent($message = array())
{
    /*
      {
      "events": [{
      "type": "follow",
      "replyToken": "31476556789a4365...",
      "source": {
      "userId": "U3c28a70ed7c5e7ce2...",
      "type": "user"
      },
      "timestamp": 1547104557739
      }],
      "destination": "U820116ffcbe3f3ca71..."
      }
     *          
     */

       /*  
        
        start Get Profile  API 
        https://api.line.me/v2/bot/profile/{User LINE ID}'

        - displayName	 
        - userId	
        - pictureUrl	
        - statusMessage	

        */

        $url = 'https://api.line.me/v2/bot/profile/' . $message['userId'];
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $message['userLineToken']);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result);

        /* end */

    $messages = [
        'type' => 'text',
        'text' => 'ยินดีต้อนรับเข้าสู่ LINE Official Account ครับคุณ'  . $response->displayName
    ];
    $data = [
        'replyToken' => $message['replyToken'],
        'messages' => [$messages],
    ];

    $message['post'] = json_encode($data);
    apiPost($message);
}
function UnfollowEvent($message = array())
{
 
      //// Config LINE Notify 
      //// ส่ง Message  ไป Function แจ้งเตือน
      // $message['message'] = 'พบผู้ใช้ Block LINE'
      // sendNotify($message);

}


function sendNotify($message = array())
{


    $txt = 'ผู้ใช้งานพิมพ์ข้อความ ' . "\r\n" . $message['message'];

    #API
    $url = 'https://notify-api.line.me/api/notify';
    $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $message['line_notify']);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "message=" . $txt);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);
}