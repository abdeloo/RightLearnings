<?php

defined('SYSPATH') or die('No direct script access.');

class General {

    public static function CatchErrorMSGSAjax($errors) {
        $Lang_Error = array();
        foreach ($errors as $key => $value) {
            $srting = '';
            if (is_array($value)) {
                foreach ($value as $subval) {
                    $srting_sub = '';
                    $errr = explode('*', $subval);
                    if (count($errr) > 0) {
                        foreach ($errr as $aaa) {
                            $srting_sub .= Lang::__($aaa) . ' ';
                        }
                        array_push($Lang_Error, $srting_sub);
                    }
                }
            } else {
                $errr = explode('*', $value);
                if (count($errr) > 0) {
                    foreach ($errr as $aaa) {
                        $srting .= Lang::__($aaa) . ' ';
                    }
                    array_push($Lang_Error, $srting);
                }
            }
        }
        return $Lang_Error;
    }

    /*
     * Conver elemnts of array to string and add </br>
     */

    public static function ArrayToString($Array) {
        $String = "";
        $Count = count($Array);
        $x = 1;
        foreach ($Array as $elemnt) {
            if ($Count > 1) {
                $String .= $x . ' - ';
            }
            $String .= $elemnt;
            if ($x != $Count) {
                $String .= "</br>";
            }
            $x++;
        }
        return $String;
    }

    /*
     * Conver elemnts of array to string and add </br>
     */

    public static function Jquery_To_ORM_Rules($Filtered_array, $JqueryRules) {
        $extra_rules = Validation::factory($Filtered_array);

        foreach ($JqueryRules as $FiledName => $JqueryRulesArray) {
            foreach ($JqueryRulesArray as $Rule => $Value) {
                switch ($Rule) {
                    case "required":
                        if ($Value == TRUE) {
                            $extra_rules->rule($FiledName, 'not_empty');
                        }
                        break;
                    case "maxlength":
                        $extra_rules->rule($FiledName, 'max_length', array(':value', $Value));
                        break;
                    case "minlength":
                        $extra_rules->rule($FiledName, 'min_length', array(':value', $Value));
                        break;
                    case "number":
                        $extra_rules->rule($FiledName, 'numeric');
                        break;
                    case "email":
                        $extra_rules->rule($FiledName, 'email');
                        break;
                    case "date":
                        $extra_rules->rule($FiledName, 'date');
                        break;
                    case "range":
                        $from = (isset($Value[0])) ? $Value[0] : 0;
                        $to = (isset($Value[1])) ? $Value[1] : 0;
                        $extra_rules->rule($FiledName, 'range', array(':value', $from, $to));
                        break;

                    default:
                        break;
                }
            }
        }

        return $extra_rules;
    }

    /*
     * 
     */

    public static function Steps_Of_Admission_Application($ORM_Application, $Current_step) {

        $Steps_Array = array(
            'StepA' => array(
                'title' => Lang::__('Applicant info'),
                'href' => 'javascript:void(0);',
                'active' => FALSE
            ),
            // 'StepB' => array(
            //     'title' => Lang::__('Sponsor information'),
            //     'href' => 'javascript:void(0);',
            //     'active' => FALSE
            // ),
            // 'StepC' => array(
            //     'title' => Lang::__('Study History'),
            //     'href' => 'javascript:void(0);',
            //     'active' => FALSE
            // ),
            // 'StepD' => array(
            //     'title' => Lang::__('Gardian information'),
            //     'href' => 'javascript:void(0);',
            //     'active' => FALSE
            // ),
            // 'StepE' => array(
            //     'title' => Lang::__('Employment information'),
            //     'href' => 'javascript:void(0);',
            //     'active' => FALSE
            // ),
            // 'StepF' => array(
            //     'title' => Lang::__('Required documents'),
            //     'href' => 'javascript:void(0);',
            //     'active' => FALSE
            // ),
        );

        if ($ORM_Application->loaded()) {
            $StepsFinished = ORM::factory('Students_Applications_Steps')
                    ->where('student_application_id', '=', $ORM_Application->id)
                    ->find_all();
            foreach ($StepsFinished as $value) {
                if (isset($Steps_Array['Step' . $value->step]['href'])) {
                    $Steps_Array['Step' . $value->step]['href'] = URL::base() . "Onlinereg/Step" . $value->step . "/" . $ORM_Application->id;
                }
                $next = ++$value->step;
                if (($next == "B") && (($ORM_Application->Nationality == 1) || !empty($ORM_Application->Nationalitie->GCC))) {
                    ++$next;
                }
                if (isset($Steps_Array['Step' . $next]['href'])) {
                    $Steps_Array['Step' . $next]['href'] = URL::base() . "Onlinereg/Step" . $next . "/" . $ORM_Application->id;
                }
            }
        }


        if (!empty($Current_step)) {
            $Steps_Array[$Current_step]['active'] = TRUE;
        }
        if ($ORM_Application->loaded() && (($ORM_Application->Nationality == 1) || !empty($ORM_Application->Nationalitie->GCC))) {
            unset($Steps_Array['StepB']);
        }

        return $Steps_Array;
    }

    public static function Img_Replaces($Html_Code) {

        if (!empty($Html_Code)) {

            $doc = new DOMDocument();
            $Code = str_replace("&", "&amp;", $Html_Code);
            $Code = str_replace("<o:p>", "", $Code);
            $Code = str_replace("</o:p>", "", $Code);
            $Code = html_entity_decode($Code);
            $Code = mb_convert_encoding($Code, 'HTML-ENTITIES', 'UTF-8');
            $doc->loadHTML($Code);
            $tags = $doc->getElementsByTagName('img');
            foreach ($tags as $tag) {
                $old_src = $tag->getAttribute('src');
                //$new_src_url = URL::base() . 'Imagefly/index/' . str_replace("/", "-", $old_src) . '/600/450';
                $new_src_url = URL::base() . $old_src;
                $tag->setAttribute('src', $new_src_url);

                $old_alt = $tag->getAttribute('alt');

                $old_class = $tag->getAttribute('class');
                $new_class = $old_class . ' img-responsive';
                if (!empty($old_alt)) {
                    $new_class .= ' AltUnder';
                }
                $tag->setAttribute('class', $new_class);

                $old_style = $tag->getAttribute('style');
                $old_style = General::replace_content_inside_delimiters('height', ';', '', $old_style);
                $old_style = General::replace_content_inside_delimiters('width', ';', '', $old_style);
                $new_style = $old_style;
                $tag->setAttribute('style', $new_style);
            }
            return $doc->saveHTML();
        } else {

            return $Html_Code;
        }
    }

    public static function replace_content_inside_delimiters($start, $end, $new, $source) {
        return preg_replace('#(' . preg_quote($start) . ')(.*?)(' . preg_quote($end) . ')#si', '$1' . $new . '$3', $source);
    }

    public static function ConvertDateToHegri($GDate = NULL) {
        require_once DOCROOT . 'classes/Hijri_Greg/uCal.class.php';
        //$date = 'yyyy-mm-dd';
        $arr = explode('-', $GDate);
        $new_date = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        $timestamp = strtotime($new_date);

        $purifier = new HijriCalendar();
        $hgri = $purifier->GregorianToHijri($timestamp);

        return $hgri[2] . "-" . $hgri[0] . "-" . $hgri[1];
    }

    public static function humanTiming($time) {

        $time = time() - $time; // to get the time since that moment
        $time = ($time < 1) ? 1 : $time;
        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit)
                continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . Lang::__($text . (($numberOfUnits > 1) ? 's' : ''));
        }
    }
    /*
       * لتحويل الارقام الى اسماء اعمدة الاكسل
       */

    public static function getNameFromNumber($num) {
        $numeric = ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);
        if ($num2 > 0) {
            return getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }
}
