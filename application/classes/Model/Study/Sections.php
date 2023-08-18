<?php

class Model_Study_Sections extends ORM {

    protected $_table_name = 'study_sections';
    protected $_belongs_to = array(
        'Course' => array('model' => 'Study_Courses', 'foreign_key' => 'course'),
        'Term' => array('model' => 'Study_Terms', 'foreign_key' => 'term'),
        'Room' => array('model' => 'Study_Rooms', 'foreign_key' => 'room'),
        'Teacher' => array('model' => 'User', 'foreign_key' => 'teacher'),
        'Gender' => array('model' => 'General_Genders', 'foreign_key' => 'gender'),
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college',),
        'Major' => array('model' => 'Study_Majors', 'foreign_key' => 'major'),
        'Program' => array('model' => 'Study_Programs', 'foreign_key' => 'program'),
        'Plan' => array('model' => 'Study_Plans', 'foreign_key' => 'plan'),
        'Department' => array('model' => 'Study_Plandepartments', 'foreign_key' => 'department'),
        'CreatedBy' => array('model' => 'User', 'foreign_key' => 'Created_by'),
        'Currency' => array('model' => 'General_Currencies', 'foreign_key' => 'currency'),
    );
    protected $_has_many = array(
        'Study_Sections_Dates' => array('model' => 'Study_Sections_Dates', 'foreign_key' => 'section'),
        'Labs' => array('model' => 'Study_Labs', 'foreign_key' => 'section'),
        'Students_Sections' => array('model' => 'Students_Sections', 'foreign_key' => 'section'),
        'Students_Participates' => array('model' => 'Students_Participates', 'foreign_key' => 'section_id'),
        'Students_Infractions' => array('model' => 'Students_Infractions', 'foreign_key' => 'section_id'),
        'Students_Absents' => array('model' => 'Students_Absents', 'foreign_key' => 'section_id'),
        'Students_Notes' => array('model' => 'Students_Notes', 'foreign_key' => 'section_id'),
        'Chapters' => array('model' => 'Study_Sections_Chapters', 'foreign_key' => 'section_id'),
        'Descriptions' => array('model' => 'Study_Sections_Descriptions', 'foreign_key' => 'section'),
        'Sections' => array('model' => 'Study_Sections', 'foreign_key' => 'parent'),
        'Documents' => array('model' => 'Study_Lessons_Documents', 'foreign_key' => 'section'),
        'Online_Classes' => array('model' => 'VClassrooms_Classes', 'foreign_key' => 'section'),
    );

    public function rules() {
        return array(
            'plan' => array(
                array('not_empty'),
            ),
            'college' => array(
                array('not_empty'),
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

        if ((!$this->_loaded) || !empty($This_ORM['is_deleted'])) {
            array_push($errors, Lang::__('Not_found_the_desired_item'));
        } else {
            foreach ($this->_has_many as $key => $value) {
                if ($this->$key->count_all() > 0) {
                    if($key != "Study_Sections_Dates" && $key != "Labs" && $key != "Chapters"){
                        array_push($errors, Lang::__('Unable_to_deletion_because_it_linked_with') . ' ' . Lang::__($key));
                    }
                }
            }
        }

        return (!empty($errors)) ? $errors : TRUE;
    }


    // return section details
    public static function GetSectionValues($section_id,$from,$to) {
        $results = array();
        $from_date = isset($from)? $from : "2000-01-01";
        $to_date = isset($to)? $to : "2200-01-01";
        $all_course_sections_ids = array();
        $all_chapters_ids = array();
        $all_lessons_ids = array();
        $results['section_students'] = 0;
        $results['section_chapters'] = 0;
        $results['section_lessons'] = 0;
        $results['lessons_files'] = 0;
        $results['lessons_sheets'] = 0;
        $section = ORM::factory('Study_Sections')->where('id', '=', $section_id )->find(); //this section
        $all_course_sections = ORM::factory('Study_Sections')->where('course', '=', $section->course)->where('term', '=', $section->term)->where('teacher', '=', $section->teacher)->where('is_deleted', '=', NULL)->find_all(); //this and other sections for this section's course
        foreach ($all_course_sections as $section){
            array_push($all_course_sections_ids,$section->id);
        }
        $results['section_students'] = ORM::factory('Students_Sections')->where('is_deleted', '=', NULL)->where('state', '=', 3)->where('section', '=', $section_id )->count_all();
        if(!empty($all_course_sections_ids)){
            $section_chapters = ORM::factory('Study_Sections_Chapters')->where('Created_date', '>=', $from_date)->where('Created_date', '<=', $to_date)->where('section_id', 'IN', $all_course_sections_ids)->where('is_deleted', '=', NULL)->find_all();
            $results['section_chapters'] = count($section_chapters);
            foreach ($section_chapters as $chapter){
                array_push($all_chapters_ids,$chapter->id);
            }
            if(!empty($all_chapters_ids)){ 
                $section_lessons = ORM::factory('Study_Sections_Lessons')->where('Created_date', '>=', $from_date)->where('Created_date', '<=', $to_date)->where('chapter_id', 'IN', $all_chapters_ids)->where('is_deleted', '=', NULL)->find_all();
                $results['section_lessons'] = count($section_lessons);
                foreach ($section_lessons as $lesson){
                    array_push($all_lessons_ids,$lesson->id);
                }
                if(!empty($all_lessons_ids)){ 
                    $section_lessons_documents = ORM::factory('Study_Lessons_Documents')->where('Created_date', '>=', $from_date)->where('Created_date', '<=', $to_date)->where('lesson', 'IN', $all_lessons_ids)->where('is_deleted', '=', NULL)->find_all();
                    foreach ($section_lessons_documents as $document){
                        if($document->document_type == "t"){
                            $results['lessons_sheets'] += 1;
                        }
                        else{
                            $results['lessons_files'] += 1;
                        }
                    }
                }
            }
        }
        return $results;
    }

    public static function GetSectionStudents($section_id,$term_id) {
        $results = array();
        $student_sections = ORM::factory('Students_Sections')->where('section', '=', $section_id)->where('term', '=', $term_id)->where('is_deleted', '=', NULL )->find_all();
        $waiting_confirm = array();
        $waiting_accreditation = array();
        $waiting_payment = array();
        $registered = array();
        $rejected = array();
        foreach ($student_sections as $section){
            if($section->state == Null && (!in_array($section->student,$waiting_confirm))){
                array_push($waiting_confirm,$section->student);
            }
            elseif ($section->state == 1 && (!in_array($section->student,$waiting_accreditation))){
                array_push($waiting_accreditation,$section->student);
            }
            elseif ($section->state == 2 && (!in_array($section->student,$waiting_payment))){
                array_push($waiting_payment,$section->student);
            }
            elseif ($section->state == 3 && (!in_array($section->student,$registered))){
                array_push($registered,$section->student);
            }
            elseif ($section->state == 4 && (!in_array($section->student,$rejected))){
                array_push($rejected,$section->student);
            }
        }
        $results['waiting_confirm'] = count($waiting_confirm);
        $results['waiting_accreditation'] = count($waiting_accreditation);
        $results['waiting_payment'] = count($waiting_payment);
        $results['registered'] = count($registered);
        $results['rejected'] = count($rejected);
        return $results;
    }

}
