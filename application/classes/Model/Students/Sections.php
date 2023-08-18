<?php

class Model_Students_Sections extends ORM
{

    protected $_table_name = 'students_sections';
    protected $_belongs_to = array(
        'Section' => array('model' => 'Study_Sections', 'foreign_key' => 'section'),
        'Term' => array('model' => 'Study_Terms', 'foreign_key' => 'term'),
        'Student' => array('model' => 'User', 'foreign_key' => 'student'),
        'Rejecter' => array('model' => 'User', 'foreign_key' => 'rejected_by'),
        'Created' => array('model' => 'User', 'foreign_key' => 'Created_by'),
    );
    protected $_has_many = array(
        'Payment' => array('model' => 'Students_Payments', 'foreign_key' => 'student_section'),
    );

    public function rules()
    {
        return array(
            'term' => array(
                array('not_empty'),
                //array('Model_Students_Sections::CheckMaxHours', array(':field', ':validation', ':value')),
            ),
            'student' => array(
                array('not_empty'),
            ),
            'section' => array(
                array('not_empty'),
                //array('Model_Students_Sections::CheckSameCourseRegCurrentTerm', array(':field', ':validation', ':value')),
            ),
        );
    }

    /* return array of errors
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

    public static function CheckMaxHours($field, $validation, $id)
    {
        $data = $validation->data();

        if (empty($data['is_deleted'])) {
            $Term = ORM::factory('Study_Terms', $id);
            if($Term->academicyear != 2){          //this is a virtual academic year for students archieve marks
                $CurrentSection = ORM::factory('Study_Sections', $data['section']);
                if ($CurrentSection->loaded()) {
                    if ($Term->loaded()) {
                        $Student = ORM::factory('User', $data['student']);
                        if ($Student->loaded() && ($Student->user_groub == 3)) {
                            $AllCurrentSections = ORM::factory('Students_Sections')
                                ->where('is_deleted', '=', NULL)
                                ->where('term', '=', $Term->id)
                                ->where('state', '!=', 4)
                                ->where('student', '=', $Student->id)
                                ->find_all();
                            $AllCurrentHour = 0;
                            $s_ids = array();
                            foreach ($AllCurrentSections as $Section) {
                                array_push($s_ids, $Section->Section->id);
                                $AllCurrentHour += $Section->Section->Course->credit_hours;
                            }
                            if (!in_array($CurrentSection->id, $s_ids)) { //للتأكد من عدم اضافة نفس الشعبة الحالية
                                $AllCurrentHour += $CurrentSection->Course->credit_hours;
                            }
                            $Study_Terms_Hours = ORM::factory('Study_Terms_Hours')->where('term', '=', $data['term'])->where('degree', '=', $Student->Student_Information->major)->where('is_deleted', '=', NULL)->find();
                            $max_hours = $Study_Terms_Hours->max_hours;

                            if ($AllCurrentHour > $max_hours) {

                                $validation->error('term', 'The_number_of_hours_to_be_registered_more_than_possible_for_this_term');
                            }


                            $NoConflect = TRUE;
                            $AllCurrentDateOfSection = ORM::factory('Study_Sections_Dates')->where('section', '=', $CurrentSection->id)->find_all();
                            foreach ($AllCurrentSections as $value) {
                                $Sect = ORM::factory('Study_Sections_Dates')->where('section', '=', $value->section)->find_all();
                                foreach ($Sect as $SecDate) {
                                    foreach ($AllCurrentDateOfSection as $CurrentSTime) {
                                        //اذا كانت الايام مثل بعض ابحث في الوقت نفسه
                                        if (strtolower($SecDate->day) == strtolower($CurrentSTime->day)) {
                                            $Res = Model_Students_Sections::intersectCheck($SecDate->start, $CurrentSTime->start, $SecDate->end, $CurrentSTime->end);
                                            if ($Res == FALSE && ($value->section != $CurrentSection->id)) {
                                                $NoConflect = FALSE;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            if ($NoConflect == FALSE) {
                                $validation->error('student', 'Section_DateTime_Conflict');
                            }
                        } else {
                            $validation->error('student', 'Not_Loaded_In_Database');
                        }
                    } else {
                        $validation->error($field, 'Not_Loaded_In_Database');
                    }
                } else {
                    $validation->error('section', 'Not_Loaded_In_Database');
                }
            }
        }

    }

    public static function CheckSameCourseRegCurrentTerm($field, $validation, $id)
    {
        $data = $validation->data();
        if (empty($data['id'])) { //حتى يتجاهل اذا كان الخيار تعديل
            $Course = ORM::factory('Study_Sections', $id)->Course;
            $RelEx = ORM::factory('Students_Sections')
                ->where('students_sections.is_deleted', '=', NULL)
                ->where('students_sections.student', '=', $data['student'])
                ->where('students_sections.term', '=', $data['term'])
                ->with('Section')->with('Section:Course')
                ->where('Section:Course.id', '=', $Course)
                ->find();
            if ($RelEx->loaded()) {
                $validation->error($field, 'Reg_On_Current_Term');
            }
        }
    }

    /**
     *
     * مقارنة اذا في تعارض بين وقتين
     * @param type $from
     * @param type $from_compare
     * @param type $to
     * @param type $to_compare
     * @return boolean
     */
    public static function intersectCheck($from, $from_compare, $to, $to_compare)
    {
        $from = strtotime($from);
        $from_compare = strtotime($from_compare);
        $to = strtotime($to);
        $to_compare = strtotime($to_compare);
        $intersect = min($to, $to_compare) - max($from, $from_compare);
        if ($intersect < 0)
            $intersect = 0;
        $overlap = $intersect / 3600;
        if ($overlap <= 0):
            // There are no time conflicts
            return TRUE;
        else:
            // There is a time conflict
            // echo '<p>There is a time conflict where the times overlap by ' , $overlap , ' hours.</p>';
            return FALSE;
        endif;
    }

    public static function CheckSectionValidity($Section,$Student){
        $result = TRUE;
        if($Section->department != NULL){
            if($Student->department != $Section->department){
                $result = FALSE;
            }
        }
        if($Section->plan != NULL){
            if($Student->plan != $Section->plan){
                $result = FALSE;
            }
        }
        if($Section->program != NULL){
            if($Student->program != $Section->program){
                $result = FALSE;
            }
        }
        if($Section->major != NULL){
            if($Student->major != $Section->major){
                $result = FALSE;
            }
        }
        return $result;

    }


}
