<?php

class Model_Rules extends ORM {
    /*
     * Aceept 0 or positive number
     */

    public static function positive_number($field, $validation, $value) {
        if (!empty($value)) {
            if ($value < 0) {
                $validation->error($field, 'Number_Must_be_more_eq_0');
            }
        }
    }
    /*
     * Aceept $x > 0
     */

    public static function positive_number_nonzero($field, $validation, $value) {
            if ($value < 1) {
                $validation->error($field, 'Number_Must_be_more_0');
            }
        
    }
    /*
     * Aceept value as arabic with spaces
     */

    public static function arabiconly($field, $validation, $vaule) {
        if (!empty($vaule)) {
            if (!preg_match("~^[\s\p{Arabic}]{1,60}$~iu", $vaule)) {
                $validation->error($field, 'Contain_Non_arabic_Chars');
            }
        }
    }

    /*
     * Aceept value as english with spaces and - . '
     */

    public static function englishonly($field, $validation, $vaule) {
        if (!empty($vaule)) {
            if (!preg_match("/^[a-zA-Z-'.\s]+$/", $vaule)) {
                $validation->error($field, 'Contain_Non_english_Chars');
            }
        }
    }
    
    /*
     * فحص اذا موجودة القيمة في جدول معين
     */
    public static function CheckLoaded($model, $field, $validation, $id) {
        if (!empty($id)) {
            $R = ORM::factory($model, $id);
            if ($R->loaded()) {
                return TRUE;
            } else {
                $validation->error($field, 'Not_Loaded_In_Database');
            }
        }
    }

}
