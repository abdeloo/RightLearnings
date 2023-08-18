<?php

defined('SYSPATH') or die('No direct script access.');

class Lang extends Kohana_I18n {

    public static function __($token, $lang = NULL) {
        $cook_lang = Cookie::get('lang');
        if (empty($cook_lang)) {
            Cookie::set('lang', 'ar');
            $cook_lang = 'ar';
        }

        if (empty($lang)) {
            $lang = $cook_lang;
        }

        $ORM = ORM::factory('Dictionary')
                ->where(DB::expr('lower(token)'), '=', strtolower($token))
                ->find();

        if ($ORM->loaded()) {
            if(empty($ORM->{"txt_" . $lang})){
                return $ORM->{"txt_" . 'ar'};
            }else{
              return $ORM->{"txt_" . $lang};  
            }
            
        } else {
            return $token;
        }
    }

    /*
     * اسم مجلد الستايل
     */

    public static function get_rtl() {
        $session = Session::instance();
        $dir = $session->get('dir');

        $files_name = NULL;
        if ($dir == 'rtl') {
            $files_name = '-rtl';
        }
        return $files_name;
    }

    /*
     * تنقية النصوص والمتغيرات من أي أكواد خبيثة
     */

    public static function safehtml($srt) {
        require_once DOCROOT . 'htmlpurifier/library/HTMLPurifier.auto.php';
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($srt);
    }

}
