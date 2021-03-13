<?php

namespace app\engine;

use app\engine\App;

class Email
{
    public function send()
    {
        $headers = "Content-type: text/html; charset=utf-8";
        $name = App::call()->Request->getParams()['name'];
        $email = App::call()->Request->getParams()['email'];
        $message = App::call()->Request->getParams()['message'];
        $adminEmail = 'lotos_toi@mail.ru';
        $theme = 'Сообщение с моего сайта!';


        $msg = "<!DOCTYPE html><body style='font-family:Arial,sans-serif;'>";
        $msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Новое сообщение:</h2>\r\n";
        $msg .= "<p>" . $message . "</p>\r\n";
        $msg .= "<p><strong>Email клиента:</strong> " .  $email . "</p>\r\n";
        $msg .= "<p><strong>Имя клиента:</strong> " . $name . "</p>\r\n";
        $msg .= "</body></html>";

        $msg_cl = "<!DOCTYPE html><body style='font-family:Arial,sans-serif;'>";
        $msg_cl .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Новое сообщение:</h2>\r\n";
        $msg_cl .= "<p>" . "<strong>Ваше сообщение:  \"</strong> " . $message . "<strong>\" успешно доставлено, мы свяжемся с вами в ближайшее время!</strong>" . "</p>\r\n";
        $msg_cl .= "<p><strong>Адрес отправителя:</strong> " . $adminEmail . "</p>\r\n";
        $msg_cl .= "</body></html>";

        $sendToMe = mail($adminEmail, $theme, $msg, $headers);
        $sendToClient  = mail($email, "Ваше сообщение отправленно", $msg_cl, $headers);

        if ($sendToMe) {
            if ($sendToClient) {
                $reply = array('result' => true);
                echo json_encode($reply);
            } else {
                $reply = array('result' => false);
                echo json_encode($reply);
            }
        } else {
            $reply = array('result' => false);
            echo json_encode($reply);
        }
    }
}
