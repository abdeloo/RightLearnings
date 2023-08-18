<?php

defined('SYSPATH') or die('No direct script access.');

class Common_Statistics_Exams {


    public static function GetTeacherExams($teacher,$from_date,$to_date){
        $results = array();
        $shared_exams_ids = array(0);

        //get shared exam ids
        $shared_exams = ORM::factory('Online_Exams_Shares')->where('CourseExam.approved', '=', 1)->where('Section.teacher','=',$teacher)->where('CourseExam.is_cancelled', '=', NULL )->where('online_exams_shares.is_deleted', '=', NULL )->where('CourseExam.is_deleted', '=', NULL )->where('Section.is_deleted', '=', NULL )->with('Section')->with('CourseExam');
        if($from_date != NULL){
            $shared_exams = $shared_exams->where('CourseExam.start_at', '>=', $from_date);
        }
        if($to_date != NULL){
            $shared_exams = $shared_exams->where('CourseExam.start_at', '<=', $to_date);
        }
        $shared_exams = $shared_exams->find_all();

        foreach($shared_exams as $shared_exam){
            array_push($shared_exams_ids, $shared_exam->course_exam_id);
        }

        //search on teacher exams or teacher shared exams
        $all_exams = ORM::factory('Online_Exams')
                ->and_where_open()
                    ->or_where('Section.teacher', '=', $teacher)
                    ->or_where('online_exams.id','IN',$shared_exams_ids)
                ->and_where_close()
                ->where('approved', '=', 1)
                ->where('is_cancelled', '=', NULL)
                ->where('online_exams.is_deleted', '=', NULL)
                ->with('Section');
        if($from_date != NULL){
            $all_exams = $all_exams->where('start_at', '>=', $from_date);
        }
        if($to_date != NULL){
            $all_exams = $all_exams->where('start_at', '<=', $to_date);
        }
        $all_exams = $all_exams->find_all();
        
        return $all_exams;
    }

    public static function GetTeacherExamsDetails($teacher,$from_date, $to_date){
        $results = array();
        $total_students_for_all_exams = 0;
        $total_succeed_students_for_all_exams = 0;

        $total_absent_students = 0;
        $total_completed_students = 0;
        $total_not_completed_students = 0;

        $teacher_exams = Common_Statistics_Exams::GetTeacherExams($teacher,$from_date,$to_date);
        foreach($teacher_exams as $teacher_exam){
            $exam_statistics = Common_Statistics_Exams::GetExamDetails($teacher_exam);
            $total_students_for_all_exams += $exam_statistics['total_students'];
            $total_succeed_students_for_all_exams += $exam_statistics['succeeded_students'];

            $total_absent_students += $exam_statistics['no_of_absents'];
            $total_completed_students += $exam_statistics['attendees_completed_exams'];
            $total_not_completed_students += $exam_statistics['attendees_not_completed_exams'];

        }
        $results["total_students"]  = $total_students_for_all_exams;
        $results["succeeded_students"]  = $total_succeed_students_for_all_exams;
        $results["percentage"]  = ($total_students_for_all_exams > 0)? (($total_succeed_students_for_all_exams/$total_students_for_all_exams)*100) : 0;

        $results["total_absent_students"] = $total_absent_students;
        $results["total_completed_students"] = $total_completed_students;
        $results["total_not_completed_students"] = $total_not_completed_students;

        return $results;
    }

    //as Controller_Online_Exams::GetExamStudents($exam);
    public static function GetExamDetails($exam){

        $results = array();
        $exam_sections_ids = array(); 
        array_push($exam_sections_ids,$exam->section);
        $exam_sections = ORM::factory('Online_Exams_Shares')->where('course_exam_id', '=',$exam->id)->where('is_deleted', '=', NULL)->find_all();
        foreach($exam_sections as $exam_section){
            array_push($exam_sections_ids,$exam_section->section);
        }
        if($exam->is_general == 2){
            $all_students = ORM::factory('Online_Exams_Students')->where('course_exam_id', '=',$exam->id)->where('is_deleted', '=', NULL)->count_all();
        }else{
            $all_students = ORM::factory('Students_Sections')->where('term', '=', $exam->term)->where('is_deleted', '=', NULL)->where('state', '=', 3)->where('section', 'IN', $exam_sections_ids)->count_all();
        }
        $no_of_attendees = ORM::factory('Online_StudentLogins')->where('exam','=',$exam->id)->count_all();
        $students_have_answers = ORM::factory('Online_StudentExams')->where('exam','=',$exam->id)->count_all();
        $students_completed_exams = ORM::factory('Online_StudentExams')->where('exam','=',$exam->id)->where('is_saved','=',1)->count_all();
        $students_not_completed_exams = ORM::factory('Online_StudentExams')->where('exam','=',$exam->id)->where('is_saved','=',NULL)->count_all();

        $results['total_students'] = $no_of_attendees;
        $results['no_of_attendees'] = $no_of_attendees;
        $results['no_of_absents'] = $all_students - $no_of_attendees;
        $results['attendees_no_answers'] = $no_of_attendees - $students_have_answers;
        $results['attendees_completed_exams'] = $students_completed_exams;
        $results['attendees_not_completed_exams'] = $students_not_completed_exams;

        $succeeded_students = Common_Statistics_Exams::GetSucceededStudents($exam->id,$exam->total);
        $failed_students = ($results['no_of_attendees'] - $succeeded_students) + $results['no_of_absents'];

        $results['succeeded_students'] = $succeeded_students;
        $results['failed_students'] = $failed_students;

        return $results;
    }

    //like this Controller_Online_Exams::GetSucceededStudents($exam->id,$total_degree);
    public static function GetSucceededStudents($exam_id,$total_mark){
        $succeeded_students = 0;
        $students_exams = ORM::factory('Online_StudentExams')->where('exam','=',$exam_id)->where('is_deleted','=',NULL)->find_all();
        foreach($students_exams as $students_exam){
            $student_mark = Common_Statistics_Exams::GetStudentExamResults($students_exam);
            if ((($student_mark/$total_mark)*100) >= 50){
                $succeeded_students += 1;
            }
        }
        return $succeeded_students;
    }


    // as Model_Online_Exams::getExamResult($examId,$students_exam->student);
    public static function GetStudentExamResults($students_exam){
        $student_total = 0;

        $student_total = DB::select(array(DB::expr('SUM(`mark`)'), 'student_total'))
            ->from('online_student_exam_answers')
            ->where('student_exam','=',$students_exam->id)
            ->execute()
            ->get('student_total');

        return round($student_total,2);

    }








}



