<?php

class Model_Study_Courses_Exams extends ORM {

    protected $_table_name = 'study_courses_exams';
    protected $_belongs_to = array(
        'Term' => array('model' => 'Study_Terms', 'foreign_key' => 'term'),
        'Exam' => array('model' => 'Study_Exams', 'foreign_key' => 'exam'),
        'Course' => array('model' => 'Study_Courses', 'foreign_key' => 'course'),
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college'),
        'Branch' => array('model' => 'Study_Majors', 'foreign_key' => 'major'),
        'Program' => array('model' => 'Study_Programs', 'foreign_key' => 'program'),
        'Plan' => array('model' => 'Study_Plans', 'foreign_key' => 'plan'),
      );
    protected $_has_many = array(
       );

    public function rules() {
        return array(
            'exam' => array(
                array('not_empty'),
                array(array($this, 'Repeated_Exam')),
            ),
            // 'course' => array(
            //     array('not_empty'),
            // ),
            'term' => array(
                array('not_empty'),
            ),
            'degree' => array(
                array('not_empty'),
                array('numeric'),
                array(array($this, 'Exceed_max_Limit')),
            ),

        );
        
    }

    public function Repeated_Exam($exam) {
        $This_ORM = $this->_object;
        if (empty($exam)) {
            return FALSE;
        } else {
            $is_repeated_exams = ORM::factory('Study_Courses_Exams')->where('id', '!=', $This_ORM['id'])->where('college', '=', $This_ORM['college'])->where('major', '=', $This_ORM['major'])->where('program', '=', $This_ORM['program'])->where('plan', '=', $This_ORM['plan'])->where('course', '=', $This_ORM['course'])->where('exam', '=', $This_ORM['exam'])->where('term', '=', $This_ORM['term'])->where('is_deleted', '=', NULL)->find();
            if ($is_repeated_exams->loaded()) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function Exceed_max_Limit($degree) {
        $This_ORM = $this->_object;
        if (empty($degree)) {
            return FALSE;
        } else {
            if (empty($This_ORM['course'])) {
                return TRUE;
            }else{
                $program_exams_ids = array();
                $total_degrees = $degree;
                
                $course_limits = Model_Study_Courses::GetMaxMinDegree($This_ORM['course'],$This_ORM['college'],$This_ORM['major'],$This_ORM['program'],$This_ORM['plan']);          
                
                $other_course_exams = ORM::factory('Study_Courses_Exams')->where('course', '=', $This_ORM['course'])->where('college', '=', $This_ORM['college'])->where('major', '=', $This_ORM['major'])->where('program', '=', $This_ORM['program'])->where('plan', '=', $This_ORM['plan'])->where('term', '=', $This_ORM['term'])->where('is_deleted', '=', NULL)->find_all();
                foreach ($other_course_exams as $value){
                    $total_degrees += $value->degree;
                }
                if ($total_degrees > $course_limits['term_mark']) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        }
    }

    /*
     * return array of errors
     * or return true if no errors
     */

    public function CheckDeleteRules() {
        $This_ORM = $this->_object; //array_of_this_orm
        $errors = array();


        if ( (!$this->_loaded) || !empty($This_ORM['is_deleted'])){
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

    public static function GetSectionExams($term,$Section) {
        $valid_exams = array();
        $course_grade_exams =  ORM::factory('Study_Courses_Exams')->where('term','=',$term)->where('course','=',$Section->course)->where('plan','=',$Section->plan)->where('is_deleted','=',NULL)->find_all(); //صف مع مادة
        if(count($course_grade_exams) > 0){
            $valid_exams = $course_grade_exams;
        }else{
            $course_stage_exams =  ORM::factory('Study_Courses_Exams')->where('term','=',$term)->where('course','=',$Section->course)->where('plan','=',NULL)->where('program','=',$Section->program)->where('is_deleted','=',NULL)->find_all();//مرحلة مع مادة
            if(count($course_stage_exams) > 0){
                $valid_exams = $course_stage_exams;
            }else{
                $course_branch_exams =  ORM::factory('Study_Courses_Exams')->where('term','=',$term)->where('course','=',$Section->course)->where('plan','=',NULL)->where('program','=',NULL)->where('major','=',$Section->major)->where('is_deleted','=',NULL)->find_all();//فرع مع مادة
                if(count($course_branch_exams) > 0){
                    $valid_exams = $course_branch_exams;
                }else{
                    $nocourse_grade_exams =  ORM::factory('Study_Courses_Exams')->where('term','=',$term)->where('course','=',NULL)->where('plan','=',$Section->plan)->where('is_deleted','=',NULL)->find_all(); //صف فقط
                    if(count($nocourse_grade_exams) > 0){
                        $valid_exams = $nocourse_grade_exams;
                    }else{
                        $nocourse_stage_exams =  ORM::factory('Study_Courses_Exams')->where('term','=',$term)->where('course','=',NULL)->where('plan','=',NULL)->where('program','=',$Section->program)->where('is_deleted','=',NULL)->find_all();//مرحلة فقط
                        if(count($nocourse_stage_exams) > 0){
                            $valid_exams = $nocourse_stage_exams;
                        }else{
                            $nocourse_branch_exams =  ORM::factory('Study_Courses_Exams')->where('term','=',$term)->where('course','=',NULL)->where('plan','=',NULL)->where('program','=',NULL)->where('major','=',$Section->major)->where('is_deleted','=',NULL)->find_all();//فرع فقط
                            if(count($nocourse_branch_exams) > 0){
                                $valid_exams = $nocourse_branch_exams;
                            }else{
                                $course_school_exams =  ORM::factory('Study_Courses_Exams')->where('term','=',$term)->where('course','=',$Section->course)->where('plan','=',NULL)->where('program','=',NULL)->where('major','=',NULL)->where('college','=',$Section->college)->where('is_deleted','=',NULL)->find_all();//مدرسة مع مادة
                                if(count($course_school_exams) > 0){
                                    $valid_exams = $course_school_exams;
                                }else{
                                    $nocourse_school_exams =  ORM::factory('Study_Courses_Exams')->where('term','=',$term)->where('course','=',NULL)->where('plan','=',NULL)->where('program','=',NULL)->where('major','=',NULL)->where('college','=',$Section->college)->where('is_deleted','=',NULL)->find_all();//مدرسة فقط
                                    if(count($nocourse_school_exams) > 0){
                                        $valid_exams = $nocourse_school_exams;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $valid_exams;
    }
}
