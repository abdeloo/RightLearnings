<?php

defined('SYSPATH') or die('No direct script access.');

class Mailer {

    public static function SendEmail($From_email = NULL, $From_Name = NULL, $Reciver_email, $msg_title, $msg_text) {
        require_once(DOCROOT . "phpmailer/class.phpmailer.php");
        require_once(DOCROOT . "phpmailer/class.smtp.php");

        $lang = Cookie::get('lang');
        if(empty($lang)){
            $lang = 'ar';
        }
        $PHPMailer = new PHPMailer();
        if (!empty($From_email)) {
            $From = $From_email;
        } else {
            $From = ORM::factory('Variables', 6)->value; //بريد المرسل
        }
        if (!empty($From_Name)) {
            $FromName = $From_Name;
        } else {
            $FromName = ORM::factory('Variables', 2)->{"value_" . $lang}; //اسم المرسل
        }
        $Subject = Lang::safehtml($msg_title); //عنوان الرسالة         
        $Message = Lang::safehtml($msg_text);


        $PHPMailer->isHtml(True); //True or false  
        $PHPMailer->CharSet = "UTF-8"; //التحكم بترميز الرسالة  
        $PHPMailer->From = $From; //بريد المرسل
        $PHPMailer->FromName = $FromName; //اسم المرسل

        if (is_array($Reciver_email)) {
            foreach ($Reciver_email as $value) {
                $PHPMailer->AddAddress($value); //بريد المستقبل   
            }
        } else {
            $PHPMailer->AddAddress($Reciver_email); //بريد المستقبل   
        }



        $PHPMailer->Subject = $Subject; //عنوان الرسالة
        $PHPMailer->Body = $Message; //نص الرسالة  

        $PHPMailer->IsSMTP(True); //هل الإرسال بإسخدام ال SMTP  
        $PHPMailer->SMTPAuth = true; //توثيق الدخول للسيرفر يتبعها اسم مستخدم وكلمة مرور
        $PHPMailer->SMTPSecure = "ssl";
        $PHPMailer->SMTPDebug = 0;
        $PHPMailer->Host = ORM::factory('Variables', 8)->value; //سيرفر الـ SMTP  
        $PHPMailer->Port = ORM::factory('Variables', 9)->value; //البورت الإفتراضي للسيرفر   //25 or 465                     
        $PHPMailer->Username = ORM::factory('Variables', 6)->value; //اسم المستخدم
        $PHPMailer->Password = ORM::factory('Variables', 7)->value; //كلمة المرور  

        //$PHPMailer->Send();
        

        if ($PHPMailer->Send()) {
            //print_r($PHPMailer);
            return TRUE;
        } else {
            //print_r($PHPMailer->ErrorInfo);
            //die();
            return $PHPMailer->ErrorInfo;
        }
    }

}
