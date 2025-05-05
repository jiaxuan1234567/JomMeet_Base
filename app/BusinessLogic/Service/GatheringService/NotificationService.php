<?php

namespace BusinessLogic\Service\GatheringService;

use HTTP_Request2;
use HTTP_Request2_Exception;

require_once '../vendor/autoload.php';

class NotificationService
{
    private $API_KEY = ""; // Limited access to this API key for testing purposes, use when needed
    // private $API_KEY = "2f88f443abb07b91f86701b545ebc262-561bb53d-a772-4e91-9336-77652d981007";

    public function sendInfobipWhatsAppTemplate($to, $name, $gathering, $action)
    {
        $to = "6".$to; // Malaysia number format
        $template = "reservation_reminder";
        $name = $name . ", JomMeet here";
        $text = $gathering['date'] . " " . $gathering['startTime'] . ".";

        if ($action == "user_joined") {
            $text = $text . " The gathering '" . $gathering['theme'] . "' has a new participant. Check the app for the latest info";
        } else if ($action == "user_left") {
            $text = $text . "A participant left the gathering '" . $gathering['theme'] . "'. Check the app for the latest info";
        } else if ($action == "gathering_cancelled") {
            $text = $text . " The gathering '" . $gathering['theme'] . "' has been cancelled. Thanks for your interest";
        } else if ($action == "gathering_updated") {
            $text = $text . " The gathering '" . $gathering['theme'] . "' has been updated. Check the app for the latest info";
        }

        $request = new HTTP_Request2();
        $request->setUrl('https://kqyrq3.api.infobip.com/whatsapp/1/message/template');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array('follow_redirects' => TRUE));
        $request->setHeader(array(
            'Authorization' => 'App ' . $this->API_KEY,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ));

        $body = [
            "messages" => [[
                "from" => "447860099299", // Your sender number
                "to" => $to,
                "messageId" => uniqid(),
                "content" => [
                    "templateName" => $template,
                    "templateData" => [
                        "body" => [
                            "placeholders" => [$name, $text]
                        ]
                    ],
                    "language" => "en"
                ]
            ]]
        ];

        $request->setBody(json_encode($body));

        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                echo "Message sent: " . $response->getBody();
            } else {
                echo "Unexpected HTTP status: " . $response->getStatus() . ' ' . $response->getReasonPhrase();
            }
        } catch (HTTP_Request2_Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
