<?php

defined('SYSPATH') or die('No direct script access.');

class Search {

    public static function action_Filtered_array($Filtered_array) {
        foreach ($Filtered_array as $key => $value) {
            if (!is_array($value) && $key != "description") {
                $Filtered_array[$key] = Lang::safehtml($value);
                if (Lang::safehtml($value) == '') {
                    $Filtered_array[$key] = NULL;
                }
            }
        }
        return $Filtered_array;
    }
    public static function action_FindAll_To_Array($ORM_FindAll_array) {
        $array_ = array();
        foreach ($ORM_FindAll_array as $k => $d) {
            $array_[$k] = $d->as_array();
        }
        return $array_;
    }
    public static function action_StoredProcedureArray($Stored_Procedure_Fnction) {
        $query = "call " . $Stored_Procedure_Fnction . ";";
        $ana = DB::query(Database::SELECT, $query)->execute();
        $arr_ = array();
        foreach ($ana as $value) {
            array_push($arr_, $value);
        }

        return $arr_;
    }

    public static function action_GetOne($array, $field, $value) {
        foreach ($array as $key => $v) {
            if (strtolower($v[$field]) === strtolower($value)) {
                return $v;
            }
        }
        return false;
    }

    public static function action_GetALL($array, $field, $value) {
        $ret_array = array();
        foreach ($array as $key => $v) {
            if (strtolower($v[$field]) === strtolower($value)) {
                array_push($ret_array, $v);
            }
        }
        return $ret_array;
    }

}
