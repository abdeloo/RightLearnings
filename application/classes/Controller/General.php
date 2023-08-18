<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_General extends Controller {

    public $user_online = NULL;

    public function __construct(Request $request, Response $response) {
        // You must call parent::__construct at some point in your function
        parent::__construct($request, $response);

        // Do whatever else you want
        // 
        // Load the user information
        $user = Auth::instance()->get_user();

        // if a user is not logged in, redirect to login page
        if (!empty($user)) {
            try {
                $this->user_online = ORM::factory('User', $user);
                $this->user_online->last_active = date("Y-m-d H:i:s");
                $this->user_online->save();
            } catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('');
                $Lang_Error = array();
                foreach ($errors as $key => $value) {
                    $srting = '';
                    $errr = explode('*', $value);
                    if (count($errr) > 0) {
                        foreach ($errr as $aaa) {
                            $srting .= Lang::__($aaa) . ' ';
                        }
                        array_push($Lang_Error, $srting);
                    }
                }
                print_r($Lang_Error);
            }
        }
    }

//    public function action_CheckDictionary() {
//        $Dictionary = ORM::factory('Dictionary')->find_all();
//        foreach ($Dictionary as $old) {
//            $DictionaryNew = ORM::factory('Dictionarynew',$old->id);
//            if($DictionaryNew->token != $old->token){
//                echo $old->id .' - token</br>';
//                echo 'old:'. $old->token.'</br>';
//                echo 'new:'. $DictionaryNew->token.'</br>';
//                
//                $DictionaryNew->token = $old->token;
//                $DictionaryNew->update();
//            }
//            if($DictionaryNew->txt_ar != $old->txt_ar){
//                echo $old->id .' - txt_ar</br>';
//                echo 'old:'. $old->txt_ar.'</br>';
//                echo 'new:'. $DictionaryNew->txt_ar.'</br>';
//                
//                $DictionaryNew->txt_ar = $old->txt_ar;
//                $DictionaryNew->update();
//            }
//            if($DictionaryNew->txt_en != $old->txt_en){
//                echo $old->id .' - txt_en</br>';
//                echo 'old:'. $old->txt_en.'</br>';
//                echo 'new:'. $DictionaryNew->txt_en.'</br>';
//                
//                $DictionaryNew->txt_en = $old->txt_en;
//                $DictionaryNew->update();
//            }
//        }
//    }

 

    public function action_ChngLang() {
        $lang = $this->request->param('par1');

        if (!in_array($lang, array('ar', 'en'))) {
            $lang = ORM::factory('Variables', 13)->value;
        }
        Cookie::set('lang', $lang);
        I18n::lang($lang);

	$URL = $this->request->referrer() ? $this->request->referrer() : "/";

        HTTP::redirect($URL, 302);
    }

    public function action_ChngMode() {
        $mode = ($this->request->param('par1') == 'dark')? TRUE : FALSE;

        $dark_mode = $mode;
        if (!in_array($mode, array('dark', 'light'))) {
            $dark_mode = FALSE;
        }
        if(isset($this->user_online)){
            $this->user_online->dark_mode = $dark_mode;
            $this->user_online->save();
        }
	    $URL = $this->request->referrer() ? $this->request->referrer() : "/";
        HTTP::redirect($URL, 302);
    }

    public function action_jsLang() {
        $cook_lang = Cookie::get('lang');
        if (empty($cook_lang)) {
            $cook_lang = 'ar';
        }
        $result = array();

        $ids = array(12, 19, 21, 27, 30, 38, 31, 88,89,90, 96,120, 122, 137, 157, 220, 120, 221, 222, 223, 224, 225, 226, 227, 76, 172, 173, 299, 317, 350, 363, 555, 880, 1248, 1299, 1076, 1823, 1816, 1818, 1821, 1819, 1820, 1817, 1864, 1794, 1381, 2997, 3011, 3012, 3066, 2935, 3067, 3115, 3116, 3205, 3206, 3221, 3222, 3258, 3275, 3286, 3289, 3302, 3429, 3449, 3527, 3548,3556,3557,3558,3559,3560,3561, 3387,3570,3701,3550,3543,3583,3584,3585,3586,3587,3588,3547,3574,3612,3613,3614,3615,3705);
        $ORM = ORM::factory('Dictionary')
                ->where('id', 'in', $ids)
                ->find_all();
        $result['theLang'] = $cook_lang;
        foreach ($ORM as $value) {
            $result[$value->token] = $value->{'txt_' . $cook_lang};
        }
        echo json_encode($result);
    }

    public function action_UploadCkeditor() {
        $result = array();
        if ($this->request->method() == Request::POST) {
            if (isset($_FILES['upload'])) {

                //$WaterMark_Status = ORM::factory('Variables', 40)->value;
                //$WaterMark_Transparents = ORM::factory('Variables', 41)->value;
                //$WaterMark_Logo = ORM::factory('Variables', 39)->value;
                $directory = 'files/uploads/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';

                $img = $this->_save_image($_FILES['upload'], 600, 600, $directory, NULL);



                if ($img) {

                    $url = URL::base() . $directory . $img;
                    $funcNum = $_GET['CKEditorFuncNum'];
                    // Optional: instance name (might be used to load a specific configuration file or anything else).
                    $CKEditor = $_GET['CKEditor'];
                    // Optional: might be used to provide localized messages.
                    $langCode = $_GET['langCode'];
                    $message = '';

                    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";

                    $url = URL::base() . $directory . $img;
                } else {
                    $result['error'] = Lang::__('Error');
                }
            }
        }
        // echo json_encode($result);
    }

    protected function _save_image($image, $width = NULL, $height = NULL, $directory = NULL, $directory_thumbs = NULL) {
        if (!Upload::valid($image) OR ! Upload::not_empty($image) OR ! Upload::type($image, array('jpg', 'jpeg', 'png', 'gif'))) {
            return FALSE;
        }
        $directory_D = DOCROOT . str_replace("\\", "/", $directory);
        if (!file_exists($directory_D)) {
            mkdir($directory_D, 0777, true);
        }
        if ($file = Upload::save($image, NULL, $directory)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $filename = strtolower(Text::random('alnum', rand(10, 30))) . '.' . $ext;

            $img = Image::factory($file);
            $img->resize($width, $height, Image::INVERSE);
            $img->save($directory . $filename, 100);

            if (!empty($directory_thumbs)) {
                $img->resize($width, $height, Image::INVERSE);
                $img->save($directory_thumbs . $filename, 100);
            }

            // Delete the temporary file
            unlink($file);

            return $filename;
        }

        return FALSE;
    }

}

// End General
