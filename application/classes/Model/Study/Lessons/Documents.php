<?php

class Model_Study_Lessons_Documents extends ORM {

    protected $_table_name = 'study_lessons_documents';
    protected $_belongs_to = array(
        'Study_Lesson' => array('model' => 'Study_Sections_Lessons', 'foreign_key' => 'lesson'),
        'Section' => array('model' => 'Study_Sections', 'foreign_key' => 'section'),
        'Teacher' => array('model' => 'User', 'foreign_key' => 'teacher'),
    );
    protected $_has_many = array(
        'Document' => array('model' => 'Students_Lessons_Documents', 'foreign_key' => 'document'),
        'Parts' => array('model' => 'Study_Lessons_Tests_Parts', 'foreign_key' => 'study_document_id'),
        'Students' => array('model' => 'Study_Lessons_Students', 'foreign_key' => 'study_document_id'),
    );
    protected $_has_one = array(
        'Student_Exercise' => array('model' => 'Students_Lessons_Documents', 'foreign_key' => 'document'),
    );

    public function rules() {
        return array(
            'lesson' => array(
                array('not_empty'),
                array('Model_Study_Lessons_Documents::CheckLoaded', array('Study_Sections_Lessons', ':field', ':validation', ':value')),
            ),  
            // 'file_path' => array(
            //     array('not_empty'),
            // ),       
        );
    }

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

    public static function GetSectionLessons($Study_Sections,$student) {
        $days_ago = date("Y-m-d", strtotime("-3 day"));
        
        $study_chapters = ORM::factory('Study_Sections_Chapters')->where('section_id','IN',$Study_Sections)->where('is_deleted', '=', NULL)->find_all();
        $chapters = array(0);
        foreach ($study_chapters as $chapter) {
            array_push($chapters, $chapter->id);
        }
        $study_lessons = ORM::factory('Study_Sections_Lessons')->where('chapter_id','IN',$chapters)->where('is_deleted', '=', NULL)->find_all();
        $lessons = array(0);
        foreach ($study_lessons as $lesson) {
            array_push($lessons, $lesson->id);
        }
        $student_lessons_documents = ORM::factory('Students_Lessons_Documents')->where('student','=', $student)->where('is_deleted', '=', NULL)->find_all();
        $student_docs = array(0);
        foreach ($student_lessons_documents as $student_doc) {
            array_push($student_docs, $student_doc->document);
        }
        $study_lessons_documents = ORM::factory('Study_Lessons_Documents')
        ->where('lesson','IN',$lessons)
        ->where('is_deleted', '=', NULL)
        ->and_where_open()
            ->or_where_open()
                ->and_where_open()
                    ->or_where_open()
                        ->where('last_update_date', '>', $days_ago)
                    ->or_where_close()
                    ->or_where_open()
                        ->where('Created_date', '>', $days_ago)
                    ->or_where_close()
                    ->where('document_type','IN',["f","v"])
                ->and_where_close()
            ->or_where_close()
            ->or_where_open()
                ->and_where_open()
                    ->where('id','NOT IN',$student_docs)
                    ->where('document_type','=',"t")
                    ->where('download_limit','>',date("Y-m-d"))
                ->and_where_close()
            ->or_where_close()
            ->or_where_open()
                ->and_where_open()
                    ->where('id','NOT IN',$student_docs)
                    ->where('document_type','=',"t")
                    ->where('upload_limit','>',date("Y-m-d"))
                ->and_where_close()            
            ->or_where_close()
        ->and_where_close()
        ->find_all();
        return $study_lessons_documents;
    }

    public static function GetLessonsDocuments($study_lesson,$student,$teacher_id) {
        $days_ago = date("Y-m-d", strtotime("-3 day"));
        
        $student_lessons_documents = ORM::factory('Students_Lessons_Documents')->where('student','=', $student)->where('is_deleted', '=', NULL)->find_all();
        $student_docs = array(0);
        foreach ($student_lessons_documents as $student_doc) {
            array_push($student_docs, $student_doc->document);
        }
        $study_lessons_documents = ORM::factory('Study_Lessons_Documents')
        ->where('lesson','=',$study_lesson)
        ->where('is_deleted', '=', NULL)
        ->and_where_open()
            ->or_where_open()
                ->and_where_open()
                    ->or_where_open()
                        ->where('last_update_date', '>', $days_ago)
                    ->or_where_close()
                    ->or_where_open()
                        ->where('Created_date', '>', $days_ago)
                    ->or_where_close()
                        ->where('document_type','IN',["f","v"])
                ->and_where_close()
            ->or_where_close()
            ->or_where_open()
                ->and_where_open()
                    ->where('id','NOT IN',$student_docs)
                    ->where('document_type','IN',["t","e","q"])
                    ->where('download_limit','>',date("Y-m-d"))
                    ->where('Created_by','=',$teacher_id)
                ->and_where_close()
            ->or_where_close()
            ->or_where_open()
                ->and_where_open()
                    ->where('id','NOT IN',$student_docs)
                    ->where('document_type','IN',["t","e","q"])
                    ->where('upload_limit','>',date("Y-m-d"))
                    ->where('Created_by','=',$teacher_id)
                ->and_where_close()            
            ->or_where_close()
        ->and_where_close()
        ->find_all();
        return $study_lessons_documents;
    }

    public static function GetStudentWaitingSheets($student_section_ids,$student) {
        $waiting_sheets = array();
        $student_lessons_documents = ORM::factory('Students_Lessons_Documents')->where('section','IN', $student_section_ids)->where('student','=', $student)->where('is_deleted', '=', NULL)->find_all();
        $student_docs = array(0);
        foreach ($student_lessons_documents as $student_doc) {
            array_push($student_docs, $student_doc->document);
        }

        $waiting_solve_sheets = ORM::factory('Study_Lessons_Documents')
        ->where('section','IN',$student_section_ids)
        ->where('is_deleted', '=', NULL)
        ->where('id','NOT IN',$student_docs)
        ->where('document_type','IN',["t","e","q"])
        ->where('upload_limit','>=',date("Y-m-d"))
        //->and_where_open()
            // ->or_where_open()
            //     ->and_where_open()
            //         ->where('id','NOT IN',$student_docs)
            //         ->where('document_type','IN',["t","e","q"])
            //         ->where('download_limit','<=',date("Y-m-d"))
            //     ->and_where_close()
            // ->or_where_close()
        //     ->or_where_open()
        //         ->and_where_open()
        //             ->where('id','NOT IN',$student_docs)
        //             ->where('document_type','=',["t","e","q"])
        //             ->where('upload_limit','>=',date("Y-m-d"))
        //         ->and_where_close()            
        //     ->or_where_close()
        // ->and_where_close()
        ->find_all();

        foreach($waiting_solve_sheets as $sheet){
            if($sheet->is_general == 1){
                array_push($waiting_sheets,$sheet );
            }else{
                $student_sheet = $sheet->Students->where('student','=',$student)->where('is_deleted','=',NULL)->find();
                if($student_sheet->loaded()){
                    array_push($waiting_sheets,$sheet );
                }
            }
        }
        return $waiting_sheets;
    }

    public static function GetStudentNotSolvedSheets($student_section_ids,$student) {
        $time_out_sheets = array();
        $student_lessons_documents = ORM::factory('Students_Lessons_Documents')->where('section','IN', $student_section_ids)->where('student','=', $student)->where('is_deleted', '=', NULL)->find_all();
        $student_docs = array(0);
        foreach ($student_lessons_documents as $student_doc) {
            array_push($student_docs, $student_doc->document);
        }

        $not_solved_sheets = ORM::factory('Study_Lessons_Documents')
        ->where('section','IN',$student_section_ids)
        ->where('is_deleted', '=', NULL)
        ->where('id','NOT IN',$student_docs)
        ->where('document_type','IN',["t","e","q"])
        ->where('upload_limit','<',date("Y-m-d"))
        //->and_where_open()
            // ->or_where_open()
            //     ->and_where_open()
            //         ->where('id','NOT IN',$student_docs)
            //         ->where('document_type','IN',["t","e","q"])
            //         ->where('download_limit','<=',date("Y-m-d"))
            //     ->and_where_close()
            // ->or_where_close()
        //     ->or_where_open()
        //         ->and_where_open()
        //             ->where('id','NOT IN',$student_docs)
        //             ->where('document_type','=',["t","e","q"])
        //             ->where('upload_limit','<',date("Y-m-d"))
        //         ->and_where_close()            
        //     ->or_where_close()
        // ->and_where_close()
        ->find_all();

        foreach($not_solved_sheets as $sheet){
            if($sheet->is_general == 1){
                array_push($time_out_sheets,$sheet );
            }else{
                $student_sheet = $sheet->Students->where('student','=',$student)->where('is_deleted','=',NULL)->find();
                if($student_sheet->loaded()){
                    array_push($time_out_sheets,$sheet );
                }
            }
        }
        return $time_out_sheets;
    }

    //لعرض الواجبات المطلوبة من الطالب لدرس محدد
    public static function GetStudentLessonSheets($lesson,$student,$teacher) {  
        $results = array();   
        $waiting_sheets = array();
        $waiting_exercises = array();
        $waiting_solve_sheets = ORM::factory('Study_Lessons_Documents')
        ->where('lesson','=',$lesson)
        ->where('is_deleted', '=', NULL)
        ->where('document_type','IN',["t","e","q"])
        ->where('upload_limit','>=',date("Y-m-d"))
        ->where('Created_by','=',$teacher)        
        ->find_all();

        foreach($waiting_solve_sheets as $sheet){
            if($sheet->is_general == 1){
                if($sheet->document_type == "e"){
                    array_push($waiting_exercises,$sheet );
                }else{
                    array_push($waiting_sheets,$sheet );
                }
            }else{
                $student_sheet = $sheet->Students->where('student','=',$student)->where('is_deleted','=',NULL)->find();
                if($student_sheet->loaded()){
                    if($sheet->document_type == "e"){
                        array_push($waiting_exercises,$sheet );
                    }else{
                        array_push($waiting_sheets,$sheet );
                    }
                }
            }
        }
        $results["exercises"] = $waiting_exercises;
        $results["sheets"] = $waiting_sheets;
        return $results;
    }

    public static function GetExercise($exerciseId) { 
        $result = array();
        $questions_list = array();
        $select_questions = array();
        $truefalse_questions = array();
        $written_questions = array();
        $map_questions = array();
        $rearrange_questions = array();
        $match_questions = array();
        $result['total_degrees'] = 0;
        $result['total_select'] = 0;
        $result['total_truefalse'] = 0;
        $result['total_written'] = 0;
        $result['total_map'] = 0;
        $result['total_rearrange'] = 0;
        $result['total_match'] = 0;
        $exam_parts = ORM::factory('Study_Lessons_Tests_Parts')->where('study_document_id', '=', $exerciseId)->where('is_deleted', '=', NULL)->find_all();
        foreach($exam_parts as $exam_part){
            $part_type = $exam_part->Questions->where('is_deleted','=',NULL)->find();
            if($exam_part->question_type == 1){
                $result['total_select'] += $exam_part->total_score;                //مجموع درجات سؤال الاختيار من متعدد
            }elseif($exam_part->question_type == 3){      
                $result['total_truefalse'] += $exam_part->total_score;             //مجموع درجات سؤال صح أو خطأ
            }elseif($exam_part->question_type == 4){
                $result['total_written'] += $exam_part->total_score;               //مجموع درجات سؤال المقال
            }elseif($exam_part->question_type == 5){
                $result['total_map'] += $exam_part->total_score;                   //مجموع درجات سؤال الخريطة
            }elseif($exam_part->question_type == 6){
                $result['total_rearrange'] += $exam_part->total_score;             //مجموع درجات سؤال الترتيب
            }elseif($exam_part->question_type == 7){
                $result['total_match'] += $exam_part->total_score;                //مجموع درجات سؤال التوصيل
            }

            //array_push($all_parts_ids,$exam_part->id);
            $result['total_degrees'] += $exam_part->total_score;                 //مجموع درجات جميع أسئلة الاختبار
            $part_questions = $exam_part->Questions->where('is_deleted','=',NULL)->find_all();
            foreach($part_questions as $part_question){                 //جلب أسئلة كل نوع فى مصفوفة لكى يتم ترتيبها عشوائيا بعد ذلك
                //array_push($questions_list,$part_question);
                if(in_array($part_question->Question->question_type, [1,2])){
                    array_push($select_questions,$part_question);
                }elseif($part_question->Question->question_type == 3){
                    array_push($truefalse_questions,$part_question);
                }elseif($part_question->Question->question_type == 4){
                    array_push($written_questions,$part_question);
                }elseif($part_question->Question->question_type == 5){
                    array_push($map_questions,$part_question);
                }elseif($part_question->Question->question_type == 6){
                    array_push($rearrange_questions,$part_question);
                }elseif($part_question->Question->question_type == 7){
                    array_push($match_questions,$part_question);
                }
            }                    
        }  
        $random_array = [0,1,2,3,4,5];                            //randomize questions
        shuffle($random_array);
        $new_select_questions = ($select_questions);
        shuffle($new_select_questions);
        $new_truefalse_questions = ($truefalse_questions);
        shuffle($new_truefalse_questions);
        $new_written_questions = ($written_questions);
        shuffle($new_written_questions); 
        $new_map_questions = ($map_questions);
        shuffle($new_map_questions);
        $new_rearrange_questions = ($rearrange_questions);
        shuffle($new_rearrange_questions);
        $new_match_questions = ($match_questions);
        shuffle($new_match_questions);
        foreach($random_array as $random_key=>$random_number){        //وضع مصفوفة الأسئلة المرتبة عشوائيا فى مصفوفة مجمعة تمهيدا لعرض الأسئلة
            if($random_number == 0){
                foreach($new_select_questions as $key=>$value){
                    array_push($questions_list,$value);
                }
            }elseif($random_number == 1){
                foreach($new_truefalse_questions as $key=>$value){
                    array_push($questions_list,$value);
                }
            }elseif($random_number == 2){
                foreach($new_written_questions as $key=>$value){
                    array_push($questions_list,$value);
                }            
            }elseif($random_number == 3){
                foreach($new_map_questions as $key=>$value){
                    array_push($questions_list,$value);
                }
            }elseif($random_number == 4){
                foreach($new_rearrange_questions as $key=>$value){
                    array_push($questions_list,$value);
                }
            }elseif($random_number == 5){
                foreach($new_match_questions as $key=>$value){
                    array_push($questions_list,$value);
                }            
            }
        } 
        $result['questions_list'] = $questions_list;
        //$result['count_match'] = count($new_match_questions);
        return $result;    
        
    }



    
}
