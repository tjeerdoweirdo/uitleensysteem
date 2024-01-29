<?php
    use PHPMailer\PHPMailer\PHPMailer;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    if(isset($_POST["send"])){
        $mail = new PHPMailer(true);

        $mail -> isSMTP();
        $mail -> Host = 'smtp-mail.outlook.com'; //SMTP Outlook Host
        $mail -> SMTPAuth = true;
        $mail -> Username = '253829@edu.rocfriesepoort.nl'; //School mail
        $mail -> Password = 'DimFIRDAww06!'; //School mail wachtwoord, gereset na project
        $mail -> SMTPSecure = 'ssl';
        $mail -> Port = 587; //SMTP Port

        $mail -> setFrom('253829@edu.rocfriesepoort.nl'); //School mail

        $mail ->addAddress($_POST["email"]);

        $mail -> isHTML(true);

        $mail -> Subject = $_POST[":("];
        $mail -> Body = $_POST[":'("];
        
        $mail -> send();

        echo
        "
        <script>
        alert('Sent Successfully');
        </script>
        ";
    }
?>