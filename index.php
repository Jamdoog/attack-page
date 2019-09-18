<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

function sendMail($type, $name) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.googlemail.com';  //gmail SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'email';   //username
    $mail->Password = 'password';   //password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;                    //SMTP port
    $mail->setFrom('email', 'FROM_NAME');
    $mail->addAddress('email', 'RECEPIENT_NAME');

    $mail->isHTML(true);
    if($type == "new") {
        $mail->Subject = 'New DDoS Attack on server: ' . $name;
        $mail->Body    = '<b>New DDoS detected on ' . $_GET['data']['ip'] . '</b>';
    }
    else if($type == "end") {
        if($_GET['data']['pps'] == 0) {
            $mail->Subject = 'End of DDoS Attack on server: ' . $name;
            $mail->Body    = '<b>End DDoS Attack on ' . $_POST['data']['ip'] . '. The power of the attack was not provided.</b>';
        }
        else {
            $mail->Subject = 'End of DDoS Attack on server: ' . $name;
            $mail->Body    = '<b>End DDoS Attack on ' . $_POST['data']['ip'] . '. The throughput was ' . $_POST['data']['mbps'] . ' / the pps was ' . $_POST['data']['pps'] . '</b>';
        }
    }
    $mail->send();
    echo 'Message has been sent';
}


// Setting up a function for posting the webhook to Discord, this will be used later.
// Please edit line 47 and place in your webhook link.
    $postdata = file_get_contents("php://input");
    $_POST = json_decode($postdata, true);
    function postToDiscord($message)
    {
        $data = array("content" => $message, "Attack Monitor" => "Webhooks");
        $curl = curl_init("Webhook Link Here");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curl);
    }
  /*
  
  For this section, you neeed to fill in your own IP's in the switch statement.
  If the IP which has been passed through on the POST request, it will set the $server variable to be equal to the name of
  the server. So for example, in the case of 213.32.7.96, I might make the $server variable display "Webhost 1". 
  Then once you have received the POST request, the webhook will post the name of the $server variable in the discord.
  
  */
    if(isset($_POST['data'])) {
        switch ($_POST['data']['ip']) {
            case "1.1.1.1":
                $server = "My FTH server here";
                break;
            default:
                $server = "None-FTH Server";
                break;
        }
        
        $power = round($_POST['data']['mbps']) / 1024;
// Checking if it is sending a start of a attack or a end of an attck. 
// If you'd rather keep the values of the power in mbps, please edit accordingly.
// This currently displays it in gigabit. Although I noticed some POST requests didn't include the power.
        if($_POST['data']['status'] == 'start') {
            postToDiscord('An attack on server: ' . $server . ' (' . $_POST['data']['ip'] . ') has initiated.');
            sendMail("new", $server);
        }
        else {
            if(round($power) == 0 && $_POST['data']['pps'] == 0) {
                postToDiscord('The attack on server: ' . $server . ' (' . $_POST['data']['ip'] . ') has ended. The throughput was not given by OVH');
                sendMail("end", $server);
            }
            else {
                postToDiscord('The attack on server: ' . $server . ' (' . $_POST['data']['ip'] . ') has ended. The throughput was ' . round($power) . 'GBPS and had the pps of ' . $_POST['data']['pps']);
                sendMail("end", $server);
            }
        }
    }
?>
