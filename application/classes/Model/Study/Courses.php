<?php

class Model_Study_Courses extends ORM {

    protected $_table_name = 'study_courses';
    protected $_belongs_to = array(
        
        'Degree' => array('model' => 'Study_Degrees', 'foreign_key' => 'degree'),
    );
    protected $_has_many = array(
        'Financials_Fees_Courses' => array('model' => 'Financials_Fees_Courses', 'foreign_key' => 'course',),
        'Study_Sections' => array('model' => 'Study_Sections', 'foreign_key' => 'course',),
        'Study_Levels_Courses' => array('model' => 'Study_Levels_Courses', 'foreign_key' => 'course',),
        'Prerequisite' => array('model' => 'Study_Courses_Prerequisite', 'foreign_key' => 'prerequisite_course',),
    );

    public function rules() {
        return array(
            'name_ar' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
            'name_en' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
        );
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

    public static function GetMaxMinDegree($course,$college,$major,$program,$plan) {
        $result = array();
        $course_program_limits = ORM::factory('Study_Courses_Programs')->where('course','=',$course)->where('plan','=',$plan)->where('is_deleted','=',NULL)->find();
        if($course_program_limits->loaded()){
            $result['max_degree'] = $course_program_limits->max_degree;
            $result['min_degree'] = $course_program_limits->min_degree;
            //$result['max_absent'] = $course_program_limits->max_absent;
            $result['term_mark'] = $course_program_limits->term_mark;
        }else{
            $course_limits =  ORM::factory('Study_Courses_Programs')->where('course','=',$course)->where('program','=',$program)->where('plan','=',NULL)->where('is_deleted','=',NULL)->find();
            if($course_limits->loaded()){
                $result['max_degree'] = $course_limits->max_degree;
                $result['min_degree'] = $course_limits->min_degree;
                //$result['max_absent'] = $course_limits->max_absent;
                $result['term_mark'] = $course_limits->term_mark;
            }
            else{
                $program_limits = ORM::factory('Study_Courses_Programs')->where('course','=',$course)->where('major','=',$major)->where('program','=',NULL)->where('is_deleted','=',NULL)->find();
                if($program_limits->loaded()){
                    $result['max_degree'] = $program_limits->max_degree;
                    $result['min_degree'] = $program_limits->min_degree;
                    //$result['max_absent'] = $program_limits->max_absent;
                    $result['term_mark'] = $program_limits->term_mark;
                }else{
                    $nocourse_grade_limits = ORM::factory('Study_Courses_Programs')->where('course','=',NULL)->where('plan','=',$plan)->where('is_deleted','=',NULL)->find();
                    if($nocourse_grade_limits->loaded()){
                        $result['max_degree'] = $nocourse_grade_limits->max_degree;
                        $result['min_degree'] = $nocourse_grade_limits->min_degree;
                        //$result['max_absent'] = $nocourse_grade_limits->max_absent;
                        $result['term_mark'] = $nocourse_grade_limits->term_mark;
                    }else{
                        $nocourse_stage_limits =  ORM::factory('Study_Courses_Programs')->where('course','=',NULL)->where('program','=',$program)->where('plan','=',NULL)->where('is_deleted','=',NULL)->find();
                        if($nocourse_stage_limits->loaded()){
                            $result['max_degree'] = $nocourse_stage_limits->max_degree;
                            $result['min_degree'] = $nocourse_stage_limits->min_degree;
                            //$result['max_absent'] = $nocourse_stage_limits->max_absent;
                            $result['term_mark'] = $nocourse_stage_limits->term_mark;
                        }else{
                            $nocourse_branch_limits = ORM::factory('Study_Courses_Programs')->where('course','=',NULL)->where('major','=',$major)->where('program','=',NULL)->where('is_deleted','=',NULL)->find();
                            if($nocourse_branch_limits->loaded()){
                                $result['max_degree'] = $nocourse_branch_limits->max_degree;
                                $result['min_degree'] = $nocourse_branch_limits->min_degree;
                                //$result['max_absent'] = $nocourse_branch_limits->max_absent;
                                $result['term_mark'] = $nocourse_branch_limits->term_mark;
                            }else{
                                $school_limits = ORM::factory('Study_Courses_Programs')->where('course','=',$course)->where('college','=',$college)->where('major','=',NULL)->where('is_deleted','=',NULL)->find();
                                if($school_limits->loaded()){
                                    $result['max_degree'] = $school_limits->max_degree;
                                    $result['min_degree'] = $school_limits->min_degree;
                                    //$result['max_absent'] = $school_limits->max_absent;
                                    $result['term_mark'] = $school_limits->term_mark;
                                }else{
                                    $general_course_limits = ORM::factory('Study_Courses',$course);
                                    if($general_course_limits->loaded()){
                                        $result['max_degree'] = $general_course_limits->max_degree;
                                        $result['min_degree'] = $general_course_limits->min_degree;
                                        //$result['max_absent'] = ORM::factory('Variables',100)->value;
                                        $result['term_mark'] = $general_course_limits->max_degree;
                                    }
                                }
                            }

                        }

                    }
                }
                
            }
        }
        return $result;
    }


}
