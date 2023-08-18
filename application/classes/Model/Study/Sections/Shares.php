<?php

class Model_Study_Sections_Shares extends ORM {

    protected $_table_name = 'study_sections_shares';
    protected $_belongs_to = array(
        'TeacherFrom' => array('model' => 'User', 'foreign_key' => 'teacher_from'),
        'TeacherTo' => array('model' => 'User', 'foreign_key' => 'teacher_to'),
        'CourseFrom' => array('model' => 'Study_Courses', 'foreign_key' => 'course_from'),
        'Section' => array('model' => 'Study_Sections', 'foreign_key' => 'section'),
        'PlanFrom' => array('model' => 'Study_Plans', 'foreign_key' => 'plan_from'),
    );
    

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
            'section' => array(
                array('not_empty'),
            ),
            // 'teacher_from' => array(
            //     array('not_empty'),
            // ),
            'teacher_to' => array(
                array('not_empty'),
            ),
            'course_from' => array(
                array('not_empty'),
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

    public static function GetSharedSectionIds($section_id){
        $Section = ORM::factory('Study_Sections',$section_id);
        $allowed_sections = array($Section->id);
        //check if this section's teacher is allowed to display other teacher's materials
        $get_section_shares = ORM::factory('Study_Sections_Shares')->where('section','=',$section_id)->where('is_deleted','=',NULL)->find_all();
        if(count($get_section_shares) > 0){
            foreach($get_section_shares as $share_section){
                if($share_section->plan_from == NULL){
                    $shared_sections = ORM::factory('Study_Sections')->where('major','=',$Section->major)->where('program','=',$Section->program)->where('plan','=',$Section->plan)->where('course','=',$Section->course)->where('teacher','=',$share_section->teacher_from)->where('is_deleted','=',NULL)->find_all();
                    if(count($shared_sections) > 0){
                        foreach($shared_sections as $shared_section){
                            array_push($allowed_sections, $shared_section->id);
                        }
                    }
                }else{
                    $other_grade_sections = ORM::factory('Study_Sections')->where('plan','=',$share_section->plan_from)->where('course','=',$Section->course)->where('teacher','=',$share_section->teacher_to)->where('is_deleted','=',NULL)->find_all();
                    if(count($other_grade_sections) > 0){
                        foreach($other_grade_sections as $other_grade_section){
                            array_push($allowed_sections, $other_grade_section->id);
                        }
                    }
                }
            }
        }
        return $allowed_sections;
    }

    public static function GetSharedSheetSectionIds($section_id){
        $Section = ORM::factory('Study_Sections',$section_id);
        $allowed_sections = array($Section->id);
        //check if this section's teacher is allowed to display other teacher's materials
        $get_section_shares = ORM::factory('Study_Sections_Shares')->where('section','=',$section_id)->where('is_deleted','=',NULL)->find_all();
        if(count($get_section_shares) > 0){
            foreach($get_section_shares as $share_section){
                if(($share_section->plan_from == NULL) && ($share_section->teacher_from == $Section->teacher)){
                    $shared_sections = ORM::factory('Study_Sections')->where('major','=',$Section->major)->where('program','=',$Section->program)->where('plan','=',$Section->plan)->where('course','=',$Section->course)->where('teacher','=',$share_section->teacher_from)->where('is_deleted','=',NULL)->find_all();
                    if(count($shared_sections) > 0){
                        foreach($shared_sections as $shared_section){
                            array_push($allowed_sections, $shared_section->id);
                        }
                    }
                }else{
                    $other_grade_sections = ORM::factory('Study_Sections')->where('plan','=',$share_section->plan_from)->where('course','=',$Section->course)->where('teacher','=',$share_section->teacher_to)->where('is_deleted','=',NULL)->find_all();
                    if(count($other_grade_sections) > 0){
                        foreach($other_grade_sections as $other_grade_section){
                            if($other_grade_section->teacher == $Section->teacher){
                                array_push($allowed_sections, $other_grade_section->id);
                            }
                        }
                    }
                }
            }
        }
        return $allowed_sections;
    }



}
