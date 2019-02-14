<?php
/**
 * Created by PhpStorm.
 * User: stanislaw
 * Date: 2/5/19
 * Time: 13:44
 */

/**
 * Class EmailSender
 */
class EmailSender
{
    /**
     * Send email to $receiver_email with $subject and $message from $sender_email
     *
     * @param $sender_email
     * @param $receiver_email
     * @param $sender_name
     * @param $subject
     * @param $message
     */
    public static function send  ($sender_email, $receiver_email, $sender_name, $subject, $message) {

        $headers  = "Content-type: text/html; charset=\"utf - 8\" \r\n";
        $headers .= "From: ".$sender_name." <".$sender_email.">\r\n";
        $headers .= "Bcc: ".$sender_email."\r\n";

        if (mail($receiver_email, $subject, $message, $headers)) {
            echo "200 OK";
        } else {
            echo "SENDING ERROR";
        }

    }
}