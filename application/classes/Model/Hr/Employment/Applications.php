<?php

class Model_Hr_Employment_Applications extends ORM {

    protected $_table_name = 'hr_employment_applications';
    protected $_belongs_to = array(
        'Marital_Status' => array('model' => 'General_Maritalstatus', 'foreign_key' => 'marital_status'),
        'Nationality' => array('model' => 'General_Countries', 'foreign_key' => 'nationality'),
        'City' => array('model' => 'General_Cities', 'foreign_key' => 'city'),
        'Country' => array('model' => 'General_Countries', 'foreign_key' => 'country'),
        'Religion' => array('model' => 'General_Religions', 'foreign_key' => 'religion'),
        'Gender' => array('model' => 'General_Genders', 'foreign_key' => 'gender'),
        'HR_Section' => array('model' => 'General_Hr_Sections', 'foreign_key' => 'hr_section'),
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college'),
        'Employee_Type' => array('model' => 'General_Hr_Employee_Types', 'foreign_key' => 'employee_type'),
        'Department' => array('model' => 'General_Hr_Departments', 'foreign_key' => 'department'),
        'Hr_Position' => array('model' => 'General_Hr_Positions', 'foreign_key' => 'hr_position'),
        'Bank' => array('model' => 'General_Banks', 'foreign_key' => 'bank'),
        'Approved_by' => array('model' => 'User', 'foreign_key' => 'approved_by'),
        'Rejected_by' => array('model' => 'User', 'foreign_key' => 'rejected_by'),
        'Employee_User' => array('model' => 'User', 'foreign_key' => 'user_id'),
        'Account' => array('model' => 'Financials_Accounts', 'foreign_key' => 'account_id'),
        'SettingInheritance' => array('model' => 'Study_Colleges', 'foreign_key' => 'from_college'),
        'ReferredBy' => array('model' => 'Study_Colleges', 'foreign_key' => 'referred_by'),

    );
    protected $_has_many = array(
        'Stores_Goods_Receipts_Vouchers' => array('model' => 'Stores_Goods_Receipts_Voucher', 'foreign_key' => 'employee'),
        'Stores_Goods_Delivery_Vouchers' => array('model' => 'Stores_Goods_Delivery_Voucher', 'foreign_key' => 'employee'),
        'Accounting_Employees_Salarys' => array('model' => 'Accounting_Employees_Salary', 'foreign_key' => 'employee'),
        'Hr_Rewards' => array('model' => 'Hr_Rewards', 'foreign_key' => 'employee'),
        'Fines_Warnings' => array('model' => 'Hr_Fineswarnings', 'foreign_key' => 'employee'),
        'Qualifications' => array('model' => 'Hr_Employment_Applications_Qualifications', 'foreign_key' => 'application_id'),
        'Certificates' => array('model' => 'Hr_Employment_Applications_Certificates', 'foreign_key' => 'application_id'),
        'Experiances' => array('model' => 'Hr_Employment_Applications_Experiances', 'foreign_key' => 'application_id'),
        'Allowances_Relations' => array('model' => 'Hr_Allowances_Relations', 'foreign_key' => 'employee'),
        'Allowances_Types' => array(
            'model' => 'Hr_Allowances_Types',
            'through' => 'hr_allowances_relations',
            'far_key' => 'type',
            'foreign_key' => 'employee',
        ),
        'Deductions_Relations' => array('model' => 'Hr_Deductions_Relations', 'foreign_key' => 'employee'),
        'Deductions_Types' => array(
            'model' => 'Hr_Deductions_Types',
            'through' => 'hr_deductions_relations',
            'far_key' => 'type',
            'foreign_key' => 'employee',
        ),
    );
    protected $_has_one = array(
    );

    public function GetFullName($lang) {
        return $this->{'name_first_' . $lang} . ' ' . $this->{'name_father_' . $lang} . ' ' . $this->{'name_grandfather_' . $lang} . ' ' . $this->{'name_last_' . $lang};
    }

    public function rules() {
        //Check _belongs_to if exist
        $Auto_arr = array();
        $belongs_to = $this->_belongs_to;
        foreach ($belongs_to as $key => $value) {
            if (!empty($value['model']) && !empty($value['foreign_key'])) {
                $Auto_arr[$value['foreign_key']] = array(
                    array('Model_Rules::CheckLoaded', array($value['model'], ':field', ':validation', ':value')),
                );
            }
        }
        //End Check _belongs_to if exist
        return array_merge_recursive($Auto_arr, array(
            'name_first_en' => array(
                array('Model_Rules::englishonly', array(':field', ':validation', ':value')),
            ),
            // 'name_father_en' => array(
            //     array('Model_Rules::englishonly', array(':field', ':validation', ':value')),
            // ),
            // 'name_grandfather_en' => array(
            //     array('Model_Rules::englishonly', array(':field', ':validation', ':value')),
            // ),
            // 'name_last_en' => array(
            //     array('Model_Rules::englishonly', array(':field', ':validation', ':value')),
            // ),
            'name_first_ar' => array(
                array('Model_Rules::arabiconly', array(':field', ':validation', ':value')),
            ),
            // 'name_father_ar' => array(
            //     array('Model_Rules::arabiconly', array(':field', ':validation', ':value')),
            // ),
            // 'name_grandfather_ar' => array(
            //     array('Model_Rules::arabiconly', array(':field', ':validation', ':value')),
            // ),
            // 'name_last_ar' => array(
            //     array('Model_Rules::arabiconly', array(':field', ':validation', ':value')),
            // ),
            'email' => array(
                array(array($this, 'unique'), array('email', ':value')),
            ),
            'id_no' => array(
                array(array($this, 'unique'), array('id_no', ':value')),
            ),
            'user_id' => array(
                array(array($this, 'unique'), array('user_id', ':value')),
            ),
        ));
    }

    /*
     * return array of errors
     * or return true if no errors
     */

    public function CheckDeleteRules() {
        $This_ORM = $this->_object; //array_of_this_orm
        $errors = array();

        if ((!$this->_loaded) || !empty($This_ORM['is_deleted'])) {
            array_push($errors, Lang::__('Not_found_the_desired_item'));
        } else {
            foreach ($this->_has_many as $key => $value) {
                if ($this->$key->count_all() > 0) {
                    array_push($errors, Lang::__('Unable_to_deletion_because_it_linked_with') . ' ' . Lang::__($key));
                }
            }
        }

        return (!empty($errors)) ? $errors : TRUE;
    }

    /*
     * Return App_Status ass array
     */

    public static function App_Status($app_orm) {
        switch ($app_orm->app_status) {
            case NULL:
                return array("state" => Lang::__('Waiting for approval'), "notes" => $app_orm->notes);
                break;
            case 1:
                return array("state" => Lang::__('App_Approved'), "notes" => $app_orm->notes);
                break;
            case 2:
                return array("state" => Lang::__('rejected'), "notes" => $app_orm->notes);
                break;

            default:
                break;
        }
    }

    /*
     * Jquery Plugin Rules when admin add/modify row...
     */

    public static function AdminRules($ORM) {

        $RulesJquery = array(
            'code' => array(
                'required' => TRUE,
                'maxlength' => 255,
            ),
            'vacations_balance' => array(
                'number' => TRUE,
            ),
            'name_first_en' => array(
                'required' => TRUE,
                'englishonly' => TRUE,
                'maxlength' => 255,
            ),
            // 'name_father_en' => array(
            //     'required' => TRUE,
            //     'englishonly' => TRUE,
            //     'maxlength' => 255,
            // ),
            // 'name_grandfather_en' => array(
            //     'required' => TRUE,
            //     'englishonly' => TRUE,
            //     'maxlength' => 255,
            // ),
            // 'name_last_en' => array(
            //     'required' => TRUE,
            //     'englishonly' => TRUE,
            //     'maxlength' => 255,
            // ),
            'name_first_ar' => array(
                'required' => TRUE,
                'arabiconly' => TRUE,
                'maxlength' => 255,
            ),
            // 'name_father_ar' => array(
            //     'required' => TRUE,
            //     'arabiconly' => TRUE,
            //     'maxlength' => 255,
            // ),
            // 'name_grandfather_ar' => array(
            //     'required' => TRUE,
            //     'arabiconly' => TRUE,
            //     'maxlength' => 255,
            // ),
            // 'name_last_ar' => array(
            //     'required' => TRUE,
            //     'arabiconly' => TRUE,
            //     'maxlength' => 255,
            // ),
            // 'place_of_birth' => array(
            //     'required' => TRUE,
            //     'maxlength' => 255,
            // ),
            // 'date_of_birth' => array(
            //     'required' => TRUE,
            //     'date' => TRUE,
            // ),
            // 'date_of_birth_higri' => array(
            //     'required' => TRUE,
            //     'maxlength' => 30,
            // ),
            // 'marital_status' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'number_of_dependents' => array(
            //     'required' => TRUE,
            //     'range' => [0, 30],
            // ),
            // 'nationality' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            'id_no' => array(
                'required' => TRUE,
                'number' => TRUE,
            ),
            'img_path' => array(
                //'required' => empty($ORM->id_copy) ? TRUE : FALSE,
                'accept' => "jpg|jpeg|png|gif",
                'filesize' => 5 * 1000000, //B
            ),
            'cv_file' => array(
                //'required' => empty($ORM->cv_file) ? TRUE : FALSE,
                'accept' => "pdf|jpg|jpeg|png|gif|doc|docx",
                'filesize' => 5 * 1000000, //B
            ),
            // 'id_expiry_date_higri' => array(
            //     'required' => TRUE,
            //     'maxlength' => 30,
            // ),
            // 'id_expiry_date' => array(
            //     'required' => TRUE,
            //     'date' => TRUE,
            // ),
            'mobile' => array(
                'required' => TRUE,
                'number' => TRUE,
                'minlength' => 10,
                'maxlength' => 12,
            ),
            'email' => array(
                'required' => TRUE,
                'email' => TRUE,
            ),
            // 'religion' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            'gender' => array(
                'required' => TRUE,
                'number' => TRUE,
            ),
            // 'passport_number' => array(
            //     'maxlength' => 30,
            // ),
            'hr_section' => array(
                'required' => TRUE,
                'number' => TRUE,
            ),
            // 'college' => array(
            //     'required' => TRUE,
            // ),
            'employee_type' => array(
                'required' => TRUE,
                'number' => TRUE,
            ),
            // 'passport_expiration_date' => array(
            //     'date' => TRUE,
            // ),
            'department' => array(
                'required' => TRUE,
                'number' => TRUE,
            ),
            'insurance_number' => array(
                'maxlength' => 100,
            ),
            'hr_position' => array(
                'required' => TRUE,
                'number' => TRUE,
            ),
            'starting_date' => array(
                'date' => TRUE,
            ),
            'employee_not_in_payroll' => array(
                'number' => TRUE,
            ),
            'job_title' => array(
                'required' => TRUE,
                'maxlength' => 200,
            ),
            //بداية حقول التواريخ
            // 'bd_year_gregorian' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'bd_month_gregorian' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'bd_day_gregorian' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'bd_year' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'bd_month' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'bd_day' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'passport_expiration_date_year' => array(
            //     'number' => TRUE,
            // ),
            // 'passport_expiration_date_month' => array(
            //     'number' => TRUE,
            // ),
            // 'passport_expiration_date_day' => array(
            //     'number' => TRUE,
            // ),
            // 'id_expiry_year_gregorian' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'id_expiry_month_gregorian' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'id_expiry_day_gregorian' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'id_expiry_year' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'id_expiry_month' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            // 'id_expiry_day' => array(
            //     'required' => TRUE,
            //     'number' => TRUE,
            // ),
            'starting_date_year' => array(
                'number' => TRUE,
            ),
            'starting_date_month' => array(
                'number' => TRUE,
            ),
            'starting_date_day' => array(
                'number' => TRUE,
            ),
            //نهاية حقول التواريخ
            'basic_salary' => array(
                'number' => TRUE,
            ),
            'country' => array(
                'required' => TRUE,
                'number' => TRUE,
            ),
            'city' => array(
                'required' => TRUE,
                'number' => TRUE,
            ),
            'app_type' => array(
                'required' => TRUE,
            ),
        );

        return $RulesJquery;
    }

    public static function VisitorRules($ORM) {
            $RulesJquery = array();
            if(empty($ORM) || $ORM->app_type == 3){
                $id = NULL;              
            }else{
                $id = $ORM->college;
            }
            $School_Form = ORM::factory('Hr_Employment_Forms')->where('for_college','=',$id)->where('is_deleted','=',NULL)->find();
            if(!$School_Form->loaded()){
                $School_Form = ORM::factory('Hr_Employment_Forms')->where('for_college','=',NULL)->where('is_deleted','=',NULL)->find();
            }
            $only_teacher_elements = ["majors", "programs", "plans", "courses"];
            $Form_Elements = ["college", "name_first_ar", "name_first_en", "id_no", "mobile", "gender","img_path", "email", "cv_file", "country", "city"];
       
            if ($School_Form->loaded()) {
                $RulesJquery = array();
                if($ORM->app_type == 2){
                    foreach($only_teacher_elements as $Element){
                        $element_array = array();
                        if($School_Form->{$Element} == 1){
                            $element_array["required"] = TRUE;
                        }
                        $RulesJquery[$Element] = $element_array;
                    }
                }
                foreach($Form_Elements as $Element){
                    $element_array = array();
                    if($School_Form->{$Element} == 1){
                        $element_array["required"] = TRUE;
                        // array_push($element_array, array(
                        //     'required' => TRUE,
                        // ));
                    }
                    if($Element == "name_first_ar"){
                        $element_array["arabiconly"] = TRUE;
                        $element_array["maxlength"] = 255;

                        // array_push($element_array, array(
                        //     'arabiconly' => TRUE,
                        //     'maxlength' => 255,
                        // ));
                    }else if($Element == "name_first_en"){
                        $element_array["englishonly"] = TRUE;
                        $element_array["maxlength"] = 255;

                        // array_push($element_array, array(
                        //     'englishonly' => TRUE,
                        //     'maxlength' => 255,
                        // ));
                    }else if($Element == "id_no"){
                        $element_array["number"] = TRUE;

                        // array_push($element_array, array(
                        //     'number' => TRUE,
                        // ));
                    }else if($Element == "mobile"){
                        $element_array["number"] = TRUE;
                        $element_array["minlength"] = 10;
                        $element_array["maxlength"] = 12;

                        // array_push($element_array, array(
                        //     'number' => TRUE,
                        //     'minlength' => 11,
                        //     'maxlength' => 11,
                        // ));
                    }else if($Element == "gender"){
                        $element_array["number"] = TRUE;

                        // array_push($element_array, array(
                        //     'number' => TRUE,
                        // ));
                    }else if($Element == "email"){
                        $element_array["email"] = TRUE;

                        // array_push($element_array, array(
                        //     'email' => true,
                        // ));
                    }else if($Element == "img_path"){
                        $element_array["accept"] = "jpg|jpeg|png|gif";
                        $element_array["filesize"] = 5 * 1000000 ;

                        // array_push($element_array, array(
                        //     'accept' => "jpg|jpeg|png|gif",
                        //     'filesize' => 5 * 1000000, //B
                        // ));
                    }else if($Element == "cv_file"){
                        $element_array["accept"] = "pdf|jpg|jpeg|png|gif|doc|docx";
                        $element_array["filesize"] = 5 * 1000000 ;

                        // array_push($element_array, array(
                        //     'accept' => "pdf|jpg|jpeg|png|gif|doc|docx",
                        //     'filesize' => 5 * 1000000, //B
                        // ));
                    }
                    $RulesJquery[$Element] = $element_array;
                }
            }
        // print_r($RulesJquery);
        // die();

        // $RulesJquery = array(
        //     'name_first_en' => array(
        //         'required' => TRUE,
        //         'englishonly' => TRUE,
        //         'maxlength' => 255,
        //     ),
        //     // 'name_father_en' => array(
        //     //     'required' => TRUE,
        //     //     'englishonly' => TRUE,
        //     //     'maxlength' => 255,
        //     // ),
        //     // 'name_grandfather_en' => array(
        //     //     'required' => TRUE,
        //     //     'englishonly' => TRUE,
        //     //     'maxlength' => 255,
        //     // ),
        //     // 'name_last_en' => array(
        //     //     'required' => TRUE,
        //     //     'englishonly' => TRUE,
        //     //     'maxlength' => 255,
        //     // ),
        //     'name_first_ar' => array(
        //         'required' => TRUE,
        //         'arabiconly' => TRUE,
        //         'maxlength' => 255,
        //     ),
        //     // 'name_father_ar' => array(
        //     //     'required' => TRUE,
        //     //     'arabiconly' => TRUE,
        //     //     'maxlength' => 255,
        //     // ),
        //     // 'name_grandfather_ar' => array(
        //     //     'required' => TRUE,
        //     //     'arabiconly' => TRUE,
        //     //     'maxlength' => 255,
        //     // ),
        //     // 'name_last_ar' => array(
        //     //     'required' => TRUE,
        //     //     'arabiconly' => TRUE,
        //     //     'maxlength' => 255,
        //     // ),
        //     // 'place_of_birth' => array(
        //     //     'required' => TRUE,
        //     //     'maxlength' => 255,
        //     // ),
        //     // 'date_of_birth' => array(
        //     //     'required' => TRUE,
        //     //     'date' => TRUE,
        //     // ),
        //     // 'date_of_birth_higri' => array(
        //     //     'required' => TRUE,
        //     //     'maxlength' => 30,
        //     // ),
        //     // 'marital_status' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'number_of_dependents' => array(
        //     //     'required' => TRUE,
        //     //     'range' => [0, 30],
        //     // ),
        //     // 'nationality' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     'id_no' => array(
        //         'required' => TRUE,
        //         'number' => TRUE,
        //     ),
        //     'img_path' => array(
        //         //'required' => empty($ORM->id_copy) ? TRUE : FALSE,
        //         'accept' => "jpg|jpeg|png|gif",
        //         'filesize' => 5 * 1000000, //B
        //     ),
        //     'cv_file' => array(
        //         //'required' => empty($ORM->cv_file) ? TRUE : FALSE,
        //         'accept' => "pdf|jpg|jpeg|png|gif|doc|docx",
        //         'filesize' => 5 * 1000000, //B
        //     ),
        //     // 'id_expiry_date_higri' => array(
        //     //     'required' => TRUE,
        //     //     'maxlength' => 30,
        //     // ),
        //     // 'id_expiry_date' => array(
        //     //     'required' => TRUE,
        //     //     'date' => TRUE,
        //     // ),
        //     'mobile' => array(
        //         'required' => TRUE,
        //         'number' => TRUE,
        //         'minlength' => 11,
        //         'maxlength' => 11,
        //     ),
        //     'email' => array(
        //         'required' => TRUE,
        //         'email' => TRUE,
        //     ),
        //     // 'religion' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     'gender' => array(
        //         'required' => TRUE,
        //         'number' => TRUE,
        //     ),
        //     // 'passport_number' => array(
        //     //     'maxlength' => 30,
        //     // ),
        //     // 'passport_expiration_date' => array(
        //     //     'date' => TRUE,
        //     // ),
        //     // //بداية حقول التواريخ
        //     // 'bd_year_gregorian' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'bd_month_gregorian' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'bd_day_gregorian' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'bd_year' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'bd_month' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'bd_day' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'passport_expiration_date_year' => array(
        //     //     'number' => TRUE,
        //     // ),
        //     // 'passport_expiration_date_month' => array(
        //     //     'number' => TRUE,
        //     // ),
        //     // 'passport_expiration_date_day' => array(
        //     //     'number' => TRUE,
        //     // ),
        //     // 'id_expiry_year_gregorian' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'id_expiry_month_gregorian' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'id_expiry_day_gregorian' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'id_expiry_year' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'id_expiry_month' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     // 'id_expiry_day' => array(
        //     //     'required' => TRUE,
        //     //     'number' => TRUE,
        //     // ),
        //     'app_type' => array(
        //         'required' => TRUE,
        //     ),
        // );

        return $RulesJquery;
    }

    /*
     * حساب راتب الموظف
     */

    public static function CalculateSalarie($ORM_Obj, $Year, $Month) {

        $notes = array();
        $Discounts = 0;
        $TRewards = 0;
        $Tallowances = 0;
        $Tdeductions = 0;

        $FullDate = Date('Y-m-d', strtotime($Year . '-' . $Month));
        $basic_salary = $ORM_Obj->basic_salary;
        $net_salary = $ORM_Obj->basic_salary;
        array_push($notes, Lang::__('basic_salary') . ': ' . $basic_salary);
        //بداية حسابات التاخير عن الدوام
        $DBODA = 0; //Discount based on delay and absence
        //$DaysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
        $DaysInThisMonth = 30;
        array_push($notes, Lang::__('DaysInThisMonth') . ': ' . $DaysInThisMonth);
        $OneDayCost = $basic_salary / $DaysInThisMonth; //تكلفة يوم العمل
        array_push($notes, Lang::__('OneDayCost') . ': ' . intval($OneDayCost));
        $from_1_to_15 = 0;
        $from_15_to_60 = 0;
        $more_60 = 0;
        $absent = 0;
        $Hr_Attendances = ORM::factory('Hr_Attendance')
                ->where('reviewed', '=', 1)
                ->where('employee', '=', $ORM_Obj->id)
                ->where('is_deleted', '=', NULL)
                ->find_all();
        $this_month_late_minute = 0; //اجمالي ساعات التأخير
        foreach ($Hr_Attendances as $Attendance) {
            $exploded_date = explode("-", $Attendance->date);
            $Exp_Year = isset($exploded_date[0]) ? $exploded_date[0] : NULL;
            $Exp_Month = isset($exploded_date[1]) ? $exploded_date[1] : NULL;
            if (($Exp_Year == $Year) && ($Exp_Month == $Month)) {

                if ($Attendance->absent == 1) { //اذا غايب
                    $absent++; // بنزيد يوم عالغيابات  ولكن في حال كان مقدم اجازة رح ينقص يوم من تحت
                    //بنفحص اذا كان مقدم طلب اجازة وموافق عليه
//                    $Salary_Affect_array = array(0); //للتاكد من سبب الاجازة اذا كان باثر عالراتب او لأ
//                    $Letters_Dropdowns_Options = ORM::factory('Letters_Dropdowns_Options')
//                            ->where('salary_affect', '=', 1)
//                            ->where('dropdown', '=', 6)
//                            ->where('is_deleted', '=', NULL)
//                            ->find_all();
//                    foreach ($Letters_Dropdowns_Options as $value) {
//                        array_push($Salary_Affect_array, $value->id);
//                    }
                    $Letters_Applications_absent = ORM::factory('Letters_Applications')
                            ->where('Created_by', '=', $ORM_Obj->Employee_User->id) //تحديد المستخدم
                            ->where('letter', '=', 6) //رقم طلب الاجازة
                            ->where('date1', '<=', $Attendance->date)
                            ->where('date2', '>=', $Attendance->date)
                          //  ->where('dropdown1', 'IN', $Salary_Affect_array)
                            ->where('is_deleted', '=', NULL)
                            ->find_all();

                    foreach ($Letters_Applications_absent as $Application) {
                        //لفحص انه الطلب موافق عليه من جميع الجهات
                        if ($Application->CheckIfApproved()) {
                            $absent--; //بنطرح يوم من الغيابات
                            break;
                        }
                    }
                }

                $Exploded_late = explode(':', $Attendance->late);
                $Minutes_late = 0; //دقائق التاخير
                $Minutes_late += !empty($Exploded_late[0]) ? intval($Exploded_late[0]) * 60 : 0;
                $Minutes_late += !empty($Exploded_late[1]) ? intval($Exploded_late[1]) : 0;
                //نفحص في حال كان مقدم طلب تاخير وموافق عليه
                $Letters_Applications_late = ORM::factory('Letters_Applications')
                        ->where('Created_by', '=', $ORM_Obj->Employee_User->id) //تحديد المستخدم
                        ->where('letter', '=', 7) //رقم استجواب التاخير
                        ->where('date1', '=', $Attendance->date)
                        ->where('is_deleted', '=', NULL)
                        ->find_all();
                foreach ($Letters_Applications_late as $Application) {
                   
                    if ($Application->CheckIfApproved() === TRUE) {
                       $Minutes_late = 0;
                       break;
                    }
                }
                

                $Exploded_early = explode(':', $Attendance->early);
                $Minutes_early = 0; //دقائق الترويحة المبكرة
                $Minutes_early += !empty($Exploded_early[0]) ? intval($Exploded_early[0]) * 60 : 0;
                $Minutes_early += !empty($Exploded_early[1]) ? intval($Exploded_early[1]) : 0;
                
                //نفحص في حال كان مقدم طلب مغادرة وموافق عليه
                $Letters_Applications_early = ORM::factory('Letters_Applications')
                        ->where('Created_by', '=', $ORM_Obj->Employee_User->id) //تحديد المستخدم
                        ->where('letter', '=', 4) //رقم طلب المغادرات
                        ->where('date1', '=', $Attendance->date)
                        ->where('is_deleted', '=', NULL)
                        ->find_all();
                foreach ($Letters_Applications_early as $Application) {
                    if ($Application->CheckIfApproved()) {
                        
                        if (($Attendance->clock_out >= $Application->time1) && $Attendance->clock_out <= $Application->time2) {
                            $Minutes_early = 0;
                            break;
                        }
                    }
                }


              

                $Total_Minutes = $Minutes_late + $Minutes_early;
                $this_month_late_minute += $Total_Minutes;
                if (!empty($Total_Minutes)) {
                    if ($Total_Minutes <= 15) {
                        $from_1_to_15++;
                    } elseif ($Total_Minutes <= 60) {
                        $from_15_to_60++;
                    } elseif ($Total_Minutes > 60) {
                        $more_60++;
                    }
                }
            }
        }

        if ($from_1_to_15 == 2) {
            $DBODA += ($OneDayCost / 4); //خصم ربع يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('Quarter day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost / 4) . ' (' . Lang::__('Because he was delayed for the second time for less than a quarter of an hour') . ')');
        } elseif ($from_1_to_15 == 3) {
            $DBODA += ($OneDayCost / 4); //خصم ربع يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('Quarter day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost / 4) . ' (' . Lang::__('Because he was delayed for the second time for less than a quarter of an hour') . ')');

            $DBODA += ($OneDayCost / 2); //خصم نصف يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('Half-day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost / 2) . ' (' . Lang::__('Because he was delayed for the third time for less than a quarter of an hour') . ')');
        } elseif ($from_1_to_15 >= 4) {
            $DBODA += ($OneDayCost / 4); //خصم ربع يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('Quarter day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost / 4) . ' (' . Lang::__('Because he was delayed for the second time for less than a quarter of an hour') . ')');

            $DBODA += ($OneDayCost / 2); //خصم نصف يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('Half-day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost / 2) . ' (' . Lang::__('Because he was delayed for the third time for less than a quarter of an hour') . ')');

            $DBODA += $OneDayCost * ($from_1_to_15 - 3); //خصم  يوم
            array_push($notes, Lang::__('deducted') . ' 1 ' . Lang::__('day') . (($from_1_to_15 - 3 > 4) ? ($from_1_to_15 - 3) . ' ' . Lang::__('Times_d') : NULL) . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost * ($from_1_to_15 - 3)) . ' (' . Lang::__('Because it was delayed for') . ' ' . ($from_1_to_15 - 3) . Lang::__('Day for less than a quarter of an hour') . ')');
        }

        if ($from_15_to_60 == 1) {
            $DBODA += ($OneDayCost / 2); //خصم نصف يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('Half-day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost / 2) . ' (' . Lang::__('Due to a delay of one day for more than a quarter of an hour and less than one hour') . ')');
        } elseif ($from_15_to_60 == 2) {
            $DBODA += ($OneDayCost / 2); //خصم نصف يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('Half-day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost / 2) . ' (' . Lang::__('Due to a delay of one day for more than a quarter of an hour and less than one hour') . ')');

            $DBODA += ($OneDayCost ); //خصم  يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost) . ' (' . Lang::__('Because of his second delay for more than a quarter of an hour and less than one hour') . ')');
        } elseif ($from_15_to_60 == 3) {
            $DBODA += ($OneDayCost / 2); //خصم نصف يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('Half-day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost / 2) . ' (' . Lang::__('Due to a delay of one day for more than a quarter of an hour and less than one hour') . ')');

            $DBODA += ($OneDayCost ); //خصم  يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost) . ' (' . Lang::__('Because of his second delay for more than a quarter of an hour and less than one hour') . ')');

            $DBODA += ($OneDayCost * 2); //خصم  يومان
            array_push($notes, Lang::__('deducted') . ' 2 ' . Lang::__('day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost * 2) . ' (' . Lang::__('Because of his third delay for more than a quarter of an hour and less than one hour') . ')');
        } elseif ($from_15_to_60 >= 4) {
            $DBODA += ($OneDayCost / 2); //خصم نصف يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('Half-day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost / 2) . ' (' . Lang::__('Due to a delay of one day for more than a quarter of an hour and less than one hour') . ')');

            $DBODA += ($OneDayCost ); //خصم  يوم
            array_push($notes, Lang::__('deducted') . ' ' . Lang::__('day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost) . ' (' . Lang::__('Because of his second delay for more than a quarter of an hour and less than one hour') . ')');

            $DBODA += ($OneDayCost * 2); //خصم  يومان
            array_push($notes, Lang::__('deducted') . ' 2 ' . Lang::__('day') . ' - ' . Lang::__('amount') . ': ' . intval($OneDayCost * 2) . ' (' . Lang::__('Because of his third delay for more than a quarter of an hour and less than one hour') . ')');

            $DBODA += ($OneDayCost * 3) * ($from_15_to_60 - 3); //خصم  ثلاث ايام
            array_push($notes, Lang::__('deducted') . ' 3 ' . Lang::__('day') . ' ' . (($from_15_to_60 - 3 > 4) ? ($from_15_to_60 - 3) . ' ' . Lang::__('Times_d') : NULL) . ' - ' . Lang::__('amount') . ': ' . intval(($OneDayCost * 3) * ($from_15_to_60 - 3)) . ' (' . Lang::__('Because it was delayed for') . ' ' . ($from_15_to_60 - 3) . Lang::__('Day duration of more than a quarter of an hour and less than one hour') . ')');
        }


        if ($more_60 > 0) {
            $DBODA += ($OneDayCost * 2) * $more_60; //خصم يومين عن كل يوم
            array_push($notes, Lang::__('deducted') . ' 2 ' . Lang::__('day') . ' ' . (($more_60 > 1) ? ($more_60) . ' ' . Lang::__('Times_d') : NULL) . ' - ' . Lang::__('amount') . ': ' . intval(($OneDayCost * 2) * $more_60) . ' (' . Lang::__('Because it was delayed for') . ' ' . ($more_60) . ' ' . Lang::__('Day duration of more than a hour') . ')');
        }

        if ($absent > 0) {
            $DBODA += ($OneDayCost * 2) * $absent; //خصم يومين عن كل يوم
            array_push($notes, Lang::__('deducted') . ' 2 ' . Lang::__('day') . ' ' . (($absent > 1) ? ($absent) . ' ' . Lang::__('Times_d') : NULL) . ' - ' . Lang::__('amount') . ': ' . intval(($OneDayCost * 2) * $absent) . ' (' . Lang::__('Because it was absent for') . ' ' . ($absent) . ' ' . Lang::__('day') . ')');
        }

        $net_salary -= intval($DBODA); //الخصم من الراتب الاساسي
        //نهاية حسابات التاخير عن الدوام
        //Calcualte Fines discounts
        $Fines_Warnings = $ORM_Obj->Fines_Warnings->where('year', '=', $Year)->where('month', '=', $Month)->where('fine_type', '!=', NULL)->where('status', '=', 1)->where('type', '=', 1)->find_all();
        foreach ($Fines_Warnings as $Fine) {
            $reason = (!empty($Fine->reason)) ? ' - ' . Lang::__('reason') . ': ' . $Fine->reason : NULL;

            $FType = $Fine->Fine_Type;
            if (!empty($FType->amount)) {
                $net_salary -= $FType->amount;
                $Discounts += $FType->amount;
                array_push($notes, Lang::__('Fine') . ': ' . Lang::__('Amount') . ' ( ' . $FType->amount . ' )' . $reason);
            } elseif (!empty($FType->percentage)) {
                $d_amount = ($basic_salary * $FType->percentage) / 100;
                $net_salary -= $d_amount;
                $Discounts += $d_amount;
                array_push($notes, Lang::__('Fine') . ': ' . Lang::__('Amount') . ' ( ' . $d_amount . ' )' . $reason);
            }
        }
        //End Calcualte Fines discounts
        //Calcualte Rewards
        $Rewards = $ORM_Obj->Hr_Rewards->where('status', '=', 1)->find_all();
        foreach ($Rewards as $Reward) {
            $FullRewardDate = Date('Y-m-d', strtotime($Reward->starting_due_year . '-' . $Reward->starting_due_month));
            if (($FullDate >= $FullRewardDate) && !empty($Reward->amount)) {
                $reason = (!empty($Reward->reason)) ? ' - ' . Lang::__('reason') . ': ' . $Reward->reason : NULL;
                array_push($notes, Lang::__('Reward') . ': ' . Lang::__('Amount') . ' ( ' . $Reward->amount . ' )' . $reason);
                $net_salary += $Reward->amount;
                $TRewards += $Reward->amount;
            }
        }
        //End Rewards
        //Calcualte Allowances
        $Allowances = ORM::factory('Hr_Allowances_Relations')->where('employee', '=', $ORM_Obj->id)->where('is_deleted', '=', NULL)->find_all();
        foreach ($Allowances as $Allowance) {
            $Allowances_Type = $Allowance->Allowances_Type;

            if (!empty($Allowances_Type->amount)) {
                $net_salary += $Allowances_Type->amount;
                $Tallowances += $Allowances_Type->amount;
                array_push($notes, Lang::__('Allowance') . ': ' . Lang::__('Amount') . ' ( ' . $Allowances_Type->amount . ' )');
            } elseif (!empty($Allowances_Type->percentage)) {
                $d_amount = ($basic_salary * $Allowances_Type->percentage) / 100;
                $net_salary += $d_amount;
                $Tallowances += $d_amount;
                array_push($notes, Lang::__('Allowance') . ': ' . Lang::__('Amount') . ' ( ' . $d_amount . ' )');
            }
        }
        //End Allowances
        //Calcualte Deductions
        $Deductions = ORM::factory('Hr_Deductions_Relations')->where('employee', '=', $ORM_Obj->id)->where('is_deleted', '=', NULL)->find_all();
        foreach ($Deductions as $Deduction) {

            $DeductionType = $Deduction->Deductions_Type;
            if (!empty($DeductionType->amount)) {
                $net_salary -= $DeductionType->amount;
                $Tdeductions += $DeductionType->amount;


                array_push($notes, Lang::__('Deduction') . ': ' . Lang::__('Amount') . ' ( ' . $DeductionType->amount . ' )');
            } elseif (!empty($DeductionType->percentage)) {
                $d_amount = ($basic_salary * $DeductionType->percentage) / 100;
                $net_salary -= $d_amount;
                $Tdeductions += $d_amount;
                array_push($notes, Lang::__('Deduction') . ': ' . Lang::__('Amount') . ' ( ' . $d_amount . ' )');
            }
        }
        //End Calcualte Deductions


        array_push($notes, Lang::__('net_salary') . ': ' . $net_salary);

        return
                array(
                    'notes' => $notes,
                    'net_salary' => $net_salary,
                    'Discounts' => $Discounts,
                    'Rewards' => $TRewards,
                    'allowances' => $Tallowances,
                    'deductions' => $Tdeductions,
                    'DBODA' => intval($DBODA),
                    'TotalMinutesLate' => $this_month_late_minute,
                    'absent' => $absent,
                    'DaysInThisMonth' => $DaysInThisMonth,
        );
    }

    /*
     * انشاء رقم اكاديمي للموظف
     */

    public function GenerateAcadimicNo() {
        $Groub = ORM::factory('Usersgroub', 5); // Student

        $username = $Groub->shortcut . date('y');
        $username .= sprintf("%07s", $this->id);

        return $username;
    }

}
