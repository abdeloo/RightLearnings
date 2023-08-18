<?php

defined('SYSPATH') or die('No direct script access.');
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;



class Controller_NewHome_Sections extends Controller_Template_Theme
{
    public function __construct(Request $request, Response $response)
    {
        // You must call parent::__construct at some point in your function
        parent::__construct($request, $response);
        // Do whatever else you want

        $this->Current_Term = ORM::factory('Study_Terms', ORm::factory('Variables', 78)->value);
        if ($this->user_online && $this->user_online->user_groub == 3 && $this->user_online->Student_Information->S_Type == 1) {
            $this->studentId = $this->user_online->id;
        } else {
            $this->studentId = NULL;
        }
        $this->locale = ["ar", "ar", "en"];
    }


    public function action_index()
    {

        $Sections = ORM::factory('Study_Sections')
            ->where('is_package', '=', NULL)
            ->where('parent', '=', NULL)
            ->where('is_deleted', '=', NULL);

        if (!empty($_GET['courses'])) {
            $courses = explode(",", $_GET['courses']);
            $Sections = $Sections->where('course', 'IN', $courses);
        }
        $Sections->reset(FALSE); // useful to keep the existing query conditions for another query
        //you can change items_per_page
        $num = 15;

        //you can configure routes and custom routes params
        $pagination = Pagination::factory(array(
                'total_items' => $Sections->count_all(),
                'items_per_page' => $num,
                //'current_page'   => Request::current()->param("page"),
            )
        )->route_params(array(
                'directory' => Request::current()->directory(),
                'controller' => Request::current()->controller(),
                'action' => Request::current()->action(),
                "par1" => NULL,
                "par2" => NULL,
            )
        );
        //now select from your DB using calculated offset
        $Sections = $Sections->order_by("id", "DESC")
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->find_all();

        $Categories = ORM::factory('Categories')->where('is_deleted', '=', NULL)->find_all();

        $this->template->layout = new View('new_theme/sections/view_all');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Packages = ORM::factory('Study_Sections')
            ->where('term', '=', $this->Current_Term->id)
            ->where('status', '=', 1)
            ->where('is_package', '=', 1)
            ->where('study_sections.is_deleted', '=', NULL)
            ->order_by('id', 'desc')
            ->find_all();
        $this->template->layout->Sections = $Sections;
        $this->template->layout->Categories = $Categories;
        $this->template->layout->pagination = $pagination;
        $this->template->layout->Plans = ORM::factory('Study_Plans')->where('is_deleted', '=', NULL)->find_all();
        $this->template->layout->Courses = ORM::factory('Study_Courses')->where('is_deleted', '=', NULL)->find_all();
    }

    public function action_GetData()
    {
        $results = '<div class="row">';
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());

        $Sections = ORM::factory('Study_Sections')
            ->where('is_package', '=', NULL)
            ->where('parent', '=', NULL)
            ->where('is_deleted', '=', NULL);

        if (!empty($Filtered_array['course'])) {
            $Sections = $Sections->where('course', '=', $Filtered_array['course']);
        }

        $Sections->reset(FALSE); // useful to keep the existing query conditions for another query
        //you can change items_per_page
        $num = 15;

        //you can configure routes and custom routes params
        $pagination = Pagination::factory(array(
                'total_items' => $Sections->count_all(),
                'items_per_page' => $num,
                //'current_page'   => Request::current()->param("page"),
            )
        )->route_params(array(
                'directory' => Request::current()->directory(),
                'controller' => Request::current()->controller(),
                'action' => Request::current()->action(),
                "par1" => NULL,
                "par2" => NULL,
            )
        );
        //now select from your DB using calculated offset
        $Sections = $Sections->order_by("id", "DESC")
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->find_all();

        foreach ($Sections as $Section) {
            $section_logo = ($Section->logo_path != NULL) ? $Section->logo_path : $Section->Course->img;
            if ($section_logo == NULL) {
                $section_logo = ($Section->College->file_path != NULL) ? $Section->College->file_path : ORM::factory('Variables', 1)->value;
            }
            $description = $Section->Descriptions->where('is_deleted', '=', NULL)->find();
            if ($description->loaded() && $description->{'name_' . $this->lang} != NULL) {
                $name = $description->{'name_' . $this->lang};
                $desc = $description->{'desc_' . $this->lang};
            } else {
                $name = ($Section->{'name_' . $this->lang} != NULL) ? $Section->{'name_' . $this->lang} : $Section->Course->{'name_' . $this->lang};
                $desc = ($Section->{'desc_' . $this->lang} != NULL) ? $Section->{'desc_' . $this->lang} : $Section->College->{'name_' . $this->lang};;
            }
            $Application = $Section->Teacher->Employee_Information;
            if (isset($Application) && ($Application->img_path != NULL)) {
                $teacher_img = $Application->img_path;
            } else {
                $teacher_img = 'assets/site/images/default-user.png';
            }

            $results .= '<div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="course-wrapper-2 mb-30">
                                <div class="student-course-img">
                                    <img width="300" height="190" src="' . URL::base() . $section_logo . '" alt="courde-img">
                                </div>
                                <div class="course-cart">
                                    <div class="course-info-wrapper">
                                        <div class="cart-info-body">
                                            <span class="category-color category-color-1">
                                            <a href="course.html">Development</a>
                                            </span>
                                            <a href="' . URL::base() . 'NewHome_Sections/CourseDetails/' . $Section->id . '">
                                            <h3>' . $name . '</h3>
                                            </a>
                                            <div class="cart-lavel">
                                            <h5>Level : <span>Beginner</span></h5>
                                            <p>' . $desc . '</p>
                                            </div>
                                            <div class="info-cart-text">
                                            <ul>
                                                <li><i class="far fa-check"></i>Scratch to HTML</li>
                                                <li><i class="far fa-check"></i>Learn how to code in Python</li>
                                                <li><i class="far fa-check"></i>Unlimited backend database creation</li>
                                                <li><i class="far fa-check"></i>Adobe XD Tutorials</li>
                                            </ul>
                                            </div>
                                            <div class="course-action">
                                            <a href="' . URL::base() . 'NewHome_Sections/CourseDetails/' . $Section->id . '" class="view-details-btn">View Details</a>
                                            <button class="wishlist-btn"><i class="flaticon-like"></i></button>
                                            <a href="course-details.html" class="c-share-btn"><i
                                                    class="flaticon-previous"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="student-course-footer">
                                    <div class="student-course-linkter">
                                        <div class="course-lessons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.471" height="16.471" viewBox="0 0 16.471 16.471">
                                            <g id="blackboard-8" transform="translate(-0.008)">
                                                <path id="Path_106" data-name="Path 101"
                                                    d="M16,1.222H8.726V.483a.483.483,0,1,0-.965,0v.74H.491A.483.483,0,0,0,.008,1.7V13.517A.483.483,0,0,0,.491,14H5.24L4.23,15.748a.483.483,0,1,0,.836.483L6.354,14H7.761v1.99a.483.483,0,0,0,.965,0V14h1.407l1.288,2.231a.483.483,0,1,0,.836-.483L11.247,14H16a.483.483,0,0,0,.483-.483V1.7A.483.483,0,0,0,16,1.222Zm-.483.965v8.905H.973V2.187Zm0,10.847H.973v-.976H15.514Z"
                                                    fill="#575757" />
                                            </g>
                                            </svg>
                                            <span class="ms-2">12 Lessons</span>
                                        </div>
                                        <div class="portfolio-price">
                                            <span>$12.57</span>
                                            <del>$24.50</del>
                                        </div>
                                    </div>
                                    <div class="student-course-text">
                                        <h3><a href="' . URL::base() . 'NewHome_Sections/CourseDetails/' . $Section->id . '">' . $name . '</a>
                                        </h3>
                                    </div>
                                    <div class="portfolio-user">
                                        <div class="user-icon">
                                            <a href="' . URL::base() . 'NewHome_Instructors_Profile/Page/' . $Section->teacher . '"><i class="fas fa-user"></i>' . $Section->Teacher->{'name_' . $this->lang} . '</a>
                                        </div>
                                        <div class="course-icon">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <span>(25)</span>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>';
        }


        $results .= '</div><div class="row">
                        <div class="edu-pagination mt-30 mb-20">
                            <ul>' . $pagination .
            '</ul>
                        </div>
                        </div>
                    ';

        echo json_encode($results);
    }

    public function action_CourseDetails()
    {
        $sectionId = $this->request->param('par1');
        $Section = ORM::factory('Study_Sections', $sectionId); 
        if ($this->lang != $this->locale[$Section->locale]) {
            Cookie::set('lang', $this->locale[$Section->locale]);
            I18n::lang($this->locale[$Section->locale]);
            return HTTP::redirect($this->request->uri(), 302);
        }

        $Students_Ratings = array();
        $Students_Ratings[1] = 0;
        $Students_Ratings[2] = 0;
        $Students_Ratings[3] = 0;
        $Students_Ratings[4] = 0;
        $Students_Ratings[5] = 0;
        $total_rates = 0;
        $total_student_rates = 0;
        $rated_students = array();
        $Ratings = ORM::factory('Students_Sections_Ratings')->where('rate', '!=', NULL)->where('section', '=', $Section->id)->find_all();
        $Reviews = ORM::factory('Students_Sections_Ratings')->where('rate', '=', NULL)->where('section', '=', $Section->id)->group_by('student')->find_all();
        foreach ($Ratings as $Rating) {
            if (!in_array($Rating->student, $rated_students)) {
                array_push($rated_students, $Rating->student);
            }
            $Students_Ratings[$Rating->rate] += 1;
            $total_student_rates += $Rating->rate;
            $total_rates += 1;
        }

        foreach ($Students_Ratings as $key => $value) {
            $Students_Ratings[$key] = ($total_rates > 0) ? (($value / $total_rates) * 100) : 0;
        }
        $total_section_rates = ($total_rates > 0) ? (($total_student_rates * 5) / ($total_rates * 5)) : 0;

        $wishlist_class = "video-wishlist-btn";
        if(!empty($this->user_online) && $this->user_online->user_groub == 3){
            $Student_Section = ORM::factory('Students_Sections')->where('student','=',$this->user_online->id)->where('section','=',$Section->id)->where('status','=',1)->where('state','=',3)->where('is_deleted','=',NULL)->find();
            if($Student_Section->loaded()){
                $in_wishList = ORM::factory("Students_Sections_Wishlists")->where('student','=',$this->user_online->id)->where('item_type','=',NULL)->where('section','=',$Section->id)->find();
                if($in_wishList->loaded()){
                    $wishlist_class = "video-cart-btn"; 
                }
            }
        }

        $registed_student = (isset($Student_Section) && $Student_Section->loaded())? "true" : "false";       

        $this->template->layout = new View('new_theme/sections/course_details');
        $this->template->layout->Section = $Section;
        $this->template->layout->Descriptions = $Section->Descriptions->where('type', '=', 1)->where('is_deleted', '=', NULL)->find_all();
        $this->template->layout->LearningPoints = array_filter(explode(',', $Section->{'learning_points_' . $this->lang})); //$Section->Descriptions->where('type','=',2)->where('is_deleted','=',NULL)->find_all();
        $this->template->layout->Requirements = array_filter(explode(',', $Section->{'requirements_' . $this->lang})); //$Section->Descriptions->where('type','=',3)->where('is_deleted','=',NULL)->find_all();
        $this->template->layout->RelatedSections = ORM::factory('Study_Sections_Related')->where('section', '=', $Section->id)->find_all();
        $this->template->layout->Chapters = ORM::factory('Study_Sections_Chapters')->where('section_id', '=', $Section->id)->where('is_deleted', '=', NULL)->where('status', '=', 1)->find_all();

        $this->template->layout->Students_Ratings = $Students_Ratings;
        $this->template->layout->total_section_rates = $total_section_rates;
        $this->template->layout->rated_students = $rated_students;
        $this->template->layout->Reviews = $Reviews;
        $this->template->layout->wishlist_class = $wishlist_class;
        $this->template->layout->lang = $this->lang;
        $this->template->layout->logo = $this->logo;
        $this->template->layout->footer = $this->footer;
        $this->template->layout->user_online = $this->user_online;
        $this->template->layout->registed_student = $registed_student;
    }

    public function action_DetailsModal2()
    {
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $url = !empty($Filtered_array['par1']) ? mb_strtolower($Filtered_array['par1']) : NULL;
        $title = Lang::__('Details');
        $view = View::factory('new_theme/sections/DetailsModal');
        $view->set('title', $title);
        $view->set('lang', $this->lang);
        $view->set('url', $url);
        $this->response->body($view);
    }

    public function action_DetailsModal()
    {
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $lesson = !empty($Filtered_array['lesson_id']) ? mb_strtolower($Filtered_array['lesson_id']) : NULL;
        $Lesson = ORM::factory('Study_Sections_Lessons', $lesson);
        $Documents = $Lesson->Documents->where('document_type', '=', "f")->where('is_deleted', '=', NULL)->find_all();
        $Videos = $Lesson->Documents->where('document_type', '=', "v")->where('is_deleted', '=', NULL)->find_all();
        $Section = $Lesson->Chapter->Section;
        $image_Documents = array();
        $docx_documents = array();
        $pdf_documents = array();
        $video_documents = array();
        $student_exams = array();

        $previous_lesson_id = NULL;
        $next_lesson_id = NULL;
        $other_previous_chapter = FALSE;
        $other_next_chapter = FALSE;
        $previous_lesson = ORM::factory('Study_Sections_Lessons')->where('chapter_id','=',$Lesson->chapter_id)->where('lesson_order','<',$Lesson->lesson_order)->where('is_deleted','=',NULL)->order_by('lesson_order','DESC')->find();
        if($previous_lesson->loaded()){
            $previous_lesson_id = $previous_lesson->id;
        }else{
            //check for previous chapter
            $previous_lesson = ORM::factory('Study_Sections_Lessons')->where('Chapter.section_id','=',$Section->id)->where('chapter_id','!=',$Lesson->chapter_id)->where('Chapter.chapter_order','<',$Lesson->Chapter->chapter_order)->where('Chapter.is_deleted','=',NULL)->where('study_sections_lessons.is_deleted','=',NULL)->order_by('lesson_order','DESC')->with('Chapter')->find();
            if($previous_lesson->loaded()){
                $previous_lesson_id = $previous_lesson->id;
                $other_previous_chapter = TRUE;
            }
        }

        $next_lesson = ORM::factory('Study_Sections_Lessons')->where('chapter_id','=',$Lesson->chapter_id)->where('lesson_order','>',$Lesson->lesson_order)->where('is_deleted','=',NULL)->order_by('lesson_order','ASC')->find();
        if($next_lesson->loaded()){
            $next_lesson_id = $next_lesson->id;
        }else{
            //check for next chapter
            $next_lesson = ORM::factory('Study_Sections_Lessons')->where('Chapter.section_id','=',$Section->id)->where('chapter_id','!=',$Lesson->chapter_id)->where('Chapter.chapter_order','>',$Lesson->Chapter->chapter_order)->where('Chapter.is_deleted','=',NULL)->where('study_sections_lessons.is_deleted','=',NULL)->order_by('lesson_order','ASC')->with('Chapter')->find();
            if($next_lesson->loaded()){
                $next_lesson_id = $next_lesson->id;
                $other_next_chapter = TRUE;
            }
        }
        

        $Wish_list_ids = array(0);
        if($this->studentId != NULL){
            $Student_Section = ORM::factory('Students_Sections')->where('student','=',$this->user_online->id)->where('section','=',$Section->id)->where('status','=',1)->where('state','=',3)->where('is_deleted','=',NULL)->find();
            if($Student_Section->loaded()){
                $File_Wishlists = ORM::factory('Students_Sections_Wishlists')->where('student','=',$this->user_online->id)->where('section','=',$Section->id)->where('item_type','=',2)->find_all();
                foreach($File_Wishlists as $File_Wishlist){
                    array_push($Wish_list_ids, $File_Wishlist->item_id);
                }
            }
        }

        foreach ($Documents as $File) {
            $doc_type = pathinfo($File->file_path, PATHINFO_EXTENSION);
            $file_name = ($File->file_name != NULL)? $File->file_name  : basename($File->file_path, "." . $doc_type);
            if(in_array($File->id, $Wish_list_ids)){
                $wishlist_status = "wishlist-btn w-added";
            }else{
                $wishlist_status = "";
            }
            if ($File->document_type == "f") {
                if(in_array($File->id, $Wish_list_ids)){
                    $wishlist_status = "wishlist-btn w-added";
                }else{
                    $wishlist_status = "";
                }
                if ((in_array($doc_type, ["docx", "doc", "xlsx", "xls", "ppt", "pptx"]))) {
                    array_push($docx_documents, array("id" => $File->id, "file_name" => $file_name,"is_general" => $File->display_for_all, "wishlist_status" => $wishlist_status, 'src' => ORM::factory('Variables', 2)->value . $File->file_path, "type" => $doc_type));
                } else if (in_array($doc_type, ["jpg", "jpeg", "png", "gif"])) {
                    array_push($image_Documents, array("id" => $File->id, "file_name" => $file_name,"is_general" => $File->display_for_all, "wishlist_status" => $wishlist_status, 'src' => ORM::factory('Variables', 2)->value . $File->file_path, "type" => "image"));
                } else {
                    array_push($pdf_documents, array("id" => $File->id, "file_name" => $file_name,"is_general" => $File->display_for_all, "wishlist_status" => $wishlist_status, 'src' => ORM::factory('Variables', 2)->value . $File->file_path, "type" => "pdf"));
                }
            }
        }
        foreach ($Videos as $Video) {
            if(in_array($Video->id, $Wish_list_ids)){
                $wishlist_status = "wishlist-btn w-added";
            }else{
                $wishlist_status = "";
            }
            if ($Video->file_path != NULL) {
                $doc_type = pathinfo($File->file_path, PATHINFO_EXTENSION);
                $file_name = ($File->file_name != NULL)? $File->file_name  : basename($File->file_path, "." . $doc_type);
                array_push($video_documents, array("id" => $Video->id,"file_name" => $file_name,"is_general" => $File->display_for_all, "wishlist_status" => $wishlist_status, 'src' => ORM::factory('Variables', 2)->value . $Video->file_path, "type" => "local_video"));
            } else {
                $file_name = ($File->file_name != NULL)? $File->file_name  : $File->file_path;
                array_push($video_documents, array("id" => $Video->id,"file_name" => $file_name,"is_general" => $File->display_for_all, "wishlist_status" => $wishlist_status, 'src' => $Video->youtube_link, "type" => "youtube"));
            }
        }
        $exams = ORM::factory('Online_Exams')->where('section', '=', $Section->id)->where('term', '=', $this->Current_Term)->where('approved', "=", 1)->where('start_at', "!=", NULL)->where('is_deleted', '=', NULL)->where('is_cancelled', '=', NULL)->order_by('start_at', 'DESC')->find_all();

        if(isset($Student_Section) && $Student_Section->loaded()){
            $StudentRate = ORM::factory('Students_Sections_Ratings')->where('student','=',$this->user_online->id)->where('section','=',$Section->id)->where('item_type','=',1)->where('item_id','=',$Lesson->id)->find();
            foreach ($exams as $exam) {
                $student_complete_exam = ORM::factory('Online_StudentExams')->where('student','=',$this->user_online->id)->where('exam','=',$exam->id)->where('is_saved','=',1)->where('is_deleted','=',NULL)->find();
                $is_open_result=0;
                if($exam->Exam->display_result == 1 && $student_complete_exam->loaded()){
                    $is_open_result=1;
                }
                if ($exam->is_general == 1) {
                    if ($exam->section == $Section->id) {
                        array_push($student_exams, array('is_open_result'=>$is_open_result, 'exam' => $exam, 'course' => $exam->Course->{'name_' . $this->lang}, 'teacher' => $exam->Section->Teacher->{'name_' . $this->lang}));
                    }
                    $shares = $exam->Shares->where('section', '=', $Section->id)->where('is_deleted', '=', NULL)->find_all();
                    foreach ($shares as $share) {
                        array_push($student_exams, array('is_open_result'=>$is_open_result,'exam' => $share->CourseExam, 'course' => $share->Section->Course->{'name_' . $this->lang}, 'teacher' => $share->Section->Teacher->{'name_' . $this->lang}));
                    }
                } else {
                    $student_exam = $exam->Students->where('student', '=', $this->user_online->id)->where('is_deleted', '=', NULL)->find();
                    if ($student_exam->loaded()) {
                        if ($exam->section == $Section->id) {
                            array_push($student_exams, array('is_open_result'=>$is_open_result,'exam' => $exam, 'course' => $exam->Course->{'name_' . $this->lang}, 'teacher' => $exam->Section->Teacher->{'name_' . $this->lang}));
                        } else {
                            $shares = $exam->Shares->where('section', '=', $Section->id)->where('is_deleted', '=', NULL)->find_all();
                            foreach ($shares as $share) {
                                array_push($student_exams, array('is_open_result'=>$is_open_result,'exam' => $share->CourseExam, 'course' => $share->Section->Course->{'name_' . $this->lang}, 'teacher' => $share->Section->Teacher->{'name_' . $this->lang}));
                            }
                        }
                    }
                }
            }
        }
        
        $registed_student = (isset($Student_Section) && $Student_Section->loaded())? TRUE : FALSE;
        $title = Lang::__('Details');
        $view = View::factory('new_theme/sections/DetailsModal');
        $view->set('title', $title);
        $view->set('lang', $this->lang);
        $view->set('Lesson', $Lesson);
        $view->set('student_exams', $student_exams);
        $view->set('section_id', $Section->id);
        $view->set('image_Documents', $image_Documents);
        $view->set('docx_documents', $docx_documents);
        $view->set('pdf_documents', $pdf_documents);
        $view->set('video_documents', $video_documents);
        $view->set('user_online', $this->user_online);
        $view->set('registed_student', $registed_student);

        $view->set('previous_lesson_id', $previous_lesson_id);
        $view->set('next_lesson_id', $next_lesson_id);
        $view->set('other_previous_chapter', $other_previous_chapter);
        $view->set('other_next_chapter', $other_next_chapter);

        if(isset($StudentRate) && $StudentRate->loaded()){
            $view->set('StudentRate', $StudentRate);
        }
        $this->response->body($view);
    }


    public function action_GetExamFromModal()
    {
        if (empty($this->user_online)) {
            $view = View::factory('system/show_msg');
            $view->set('class_color', 'danger');
            $view->set('msg', "must login");

            $this->response->body($view);
        } else {
            $req = Request::current(); //fillter requset
            $Filtered_array = Search::action_Filtered_array($req->post());
            $exam = $Filtered_array['par1'];


            $course_exam = ORM::factory('Online_Exams', $exam);

//            if ($this->lang != $this->locale[$course_exam->locale]) {
//                Cookie::set('lang', $this->locale[$course_exam->locale]);
//                I18n::lang($this->locale[$course_exam->locale]);
//                return HTTP::redirect($this->request->uri(), 302);
//            }

            $time = new DateTime($course_exam->start_at);
            $time->add(new DateInterval('PT' . $course_exam->duration . 'M'));
            $exam_finished = $time->format('Y-m-d H:i');
            $current_date = date("Y-m-d H:i:s");
            $set_timer = round((strtotime($exam_finished) - strtotime($current_date)) / 60, 0);

            $previous_taken = ORM::factory('Online_StudentExams')->where('exam', '=', $exam)->where('student', '=', $this->user_online->id)->find();

            if (($course_exam->loaded()) && ($course_exam->is_cancelled == NULL) && ($course_exam->approved == 1) && ($course_exam->is_deleted == NULL) && ($course_exam->term == $this->Current_Term->id) && ($course_exam->start_at != NULL) && ($course_exam->start_at <= date("Y-m-d H:i:s")) && (date("Y-m-d H:i:s") <= $exam_finished)) {
                if ((!$previous_taken->loaded()) || ($previous_taken->loaded() && $previous_taken->is_saved == NULL)) {
                    if ($course_exam->is_general == 2) {
                        $allowed_student = False;
                        $student_exam = $course_exam->Students->where('student', '=', $this->user_online->id)->where('is_deleted', '=', NULL)->find();
                        if ($student_exam->loaded()) {
                            $allowed_student = TRUE;
                        }
                    } else {
                        $allowed_student = TRUE;
                    }
                    $student_login = ORM::factory('Online_StudentLogins')->where('student', '=', $this->user_online->id)->where('exam', '=', $exam)->find();
                    if (!$student_login->loaded()) {
                        $student_login->student = $this->user_online->id;
                        $student_login->exam = $exam;
                        $student_login->Created_by = $this->user_online->id;
                        $student_login->Created_date = date("Y-m-d H:i:s");
                        $student_login->save();
                    }

                    $student_section = Controller_Student_Assessment::getSharedSections($course_exam, $this->user_online->id);
                    //$exam_sections = array();
                    //array_push($exam_sections,$course_exam->section);
                    //$shared_sections = $course_exam->Shares->find_all();//to get sections that share this exam
                    //foreach($shared_sections as $shared_section){
                    //array_push($exam_sections,$shared_section->section);
                    //}
                    // $student_section = ORM::factory('Students_Sections')->where('section','IN',$exam_sections)->where('student','=',$this->user_online->id)->where('state','=',3)->where('term','=',$course_exam->term)->where('is_deleted', '=', NULL)->find();
                    if (($student_section->loaded()) && ($student_section->exam_status == 1) && ($allowed_student == TRUE)) {            //check if this student is allowed to enter this exam
                        $results = Model_Online_Exams::getExam($exam);

                        $Student_Info = $this->user_online->Student_Information;

                        $view = View::factory('new_theme/sections/ukexamForModal');
                        $view->set('title', Lang::__('exam'));
                        $view->set('course_exam', $course_exam);
                        $view->set('course', $student_section->Section->Course->{'name_' . $this->locale[$course_exam->locale]});
                        $view->set('results', $results);
                        $view->set('exam', $exam);
                        $view->set('previous_saved_exam', $previous_taken);
                        $view->set('Student_Info', $Student_Info);
                        $view->set('set_timer', $set_timer);
                        $view->set('title', Lang::__('exam'));
                        $view->set('lang', $this->locale[$course_exam->locale]);

                        $this->response->body($view);


                    } else {
                        $view = View::factory('system/show_msg');
                        $view->set('class_color', 'danger');
                        $view->set('msg', Lang::__('You_have_completed_this_exam'));

                        $this->response->body($view);
                    }
                } else {
                    $view = View::factory('system/show_msg');
                    $view->set('class_color', 'danger');
                    $view->set('msg', Lang::__('You_have_completed_this_exam'));

                    $this->response->body($view);
                }
            } else {
                $view = View::factory('system/show_msg');
                $view->set('class_color', 'danger');
                $view->set('msg', Lang::__('You_dont_have_permission_to_access_this_page'));

                $this->response->body($view);
            }

        }
    }
    public function action_getResults()
    {
        $results = array();
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        #$questions = !empty($Filtered_array['questionId'])? $Filtered_array['questionId'] : NULL;
        $answers = !empty($Filtered_array['answer']) ? $Filtered_array['answer'] : NULL;
        $examid = !empty($Filtered_array['examId']) ? $Filtered_array['examId'] : NULL;
        $exam = ORM::factory('Online_Exams', $examid);
        $display_result = $exam->Exam->display_result;
        $time = new DateTime($exam->start_at);
        $duration = $exam->duration + 5;
        $time->add(new DateInterval('PT' . $duration . 'M'));
        $exam_finished = $time->format('Y-m-d H:i');
        $student_section = Controller_Student_Assessment::getSharedSections($exam, $this->user_online->id);
        // //$all_parts_ids = array();
        // $total_select_marks = 0;          //درجة السؤال كاملا
        // $total_truefalse_marks = 0;       //درجة السؤال كاملا
        // $total_match_marks = 0;           //درجة السؤال كاملا
        // $total_rearrange_marks = 0;       //درجة السؤال كاملا
        // $total_select_questions = 0;      //عدد أسئلة الاختيار من متعدد
        // $total_truefalse_questions = 0;   //عدد أسئلة صح غلط
        // $total_match_questions = 0;       //عدد أسئلة التوصيل
        // $total_arrange_questions = 0;     //عدد أسئلة الترتيب
        // $select_question_mark = 0;        //درجة كل سؤال فى الاختيار من متعدد
        // $truefalse_question_mark = 0;     //درجة كل سؤال فى صح غلط
        // $match_question_mark = 0;         //درجة كل سؤال فى التوصيل
        // $rearrange_question_mark = 0;     //درجة كل سؤال فى الترتيب

        // $exam_parts = ORM::factory('Online_Exams_Parts')->where('course_exam_id', '=', $exam)->where('is_deleted', '=', NULL)->find_all();
        // foreach($exam_parts as $exam_part){
        //     $part_type = $exam_part->Questions->find();
        //     if($part_type->Question->question_type == 1){
        //         $total_select_marks = $exam_part->total_score;                //د
        //         $total_select_questions += $exam_part->Questions->where('is_deleted','=',NULL)->count_all();
        //     }elseif($part_type->Question->question_type == 3){
        //         $total_truefalse_marks = $exam_part->total_score;
        //         $total_truefalse_questions += $exam_part->Questions->where('is_deleted','=',NULL)->count_all();
        //     }elseif($part_type->Question->question_type == 6){   //rearrange
        //         $total_rearrange_marks = $exam_part->total_score; //عدد اسئلة الترتيب لسة هنحسب كم نقطة فى السؤال الواحد
        //         //$total_arrange_questions += $exam_part->Questions->where('is_deleted','=',NULL)->count_all();
        //     }elseif($part_type->Question->question_type == 7){   //match
        //         $total_match_marks = $exam_part->total_score;  //عدد اسئلة التوصيل لسة هنحسب كم نقطة فى السؤال الواحد
        //         //$total_match_questions += $exam_part->Questions->where('is_deleted','=',NULL)->count_all();
        //     }
        // }
        // if($total_select_marks != 0){
        //     $select_question_mark = ($total_select_marks/$total_select_questions);
        // }
        // if($total_truefalse_marks != 0){
        //     $truefalse_question_mark = ($total_truefalse_marks/$total_truefalse_questions);
        // }
        // // if($total_match_marks != 0){
        // //     $match_question_mark = ($total_match_marks/$total_match_questions);
        // // }
        // // if($total_rearrange_marks != 0){
        // //     $rearrange_question_mark = ($total_rearrange_marks/$total_arrange_questions);
        // // }


        $student_exam = ORM::factory('Online_StudentExams')->where('exam', '=', $examid)->where('student', '=', $this->user_online->id)->find();
        // $correct_select_answers = 0;
        // $correct_truefalse_answers = 0;
        // $correct_rearrange_answers = 0;
        // $correct_match_answers = 0;
        $has_written = 0;
        if (($exam->loaded()) && ((!$student_exam->loaded()) || ($student_exam->loaded() && $student_exam->is_saved == NULL))) {
            if (date("Y-m-d H:i:s") <= $exam_finished) {
                if (!$student_exam->loaded()) {
                    #$student_exam = ORM::factory('Online_StudentExams');
                    $student_exam->exam = $exam->id;
                    $student_exam->section = $student_section->section;
                    $student_exam->student = $this->user_online->id;
                    $student_exam->Created_by = $this->user_online->id;
                    $student_exam->Created_date = date("Y-m-d H:i:s");
                } else {
                    $student_exam->last_update_by = $this->user_online->id;
                    $student_exam->last_update_date = date("Y-m-d H:i:s");
                }
                $student_exam->is_saved = 1;

                if ($student_exam->save()) {

                    //حساب درجات أسئلة الترتيب والتوصيل
                    $match_rearrange_answers = ORM::factory('Online_StudentAnswers')->where('student_exam', '=', $student_exam->id)->find_all();
                    foreach ($match_rearrange_answers as $answer) {
                        if ($answer->Question->question_type == 6) { //سؤال ترتيب
                            //$total_arrange_questions += 1;
                            if ($answer->status == 1) {
                                $question_total_mark = ORM::factory('Online_Exams_Questions')->where('course_exam_id', '=', $exam)->where('question_id', '=', $answer->question)->find()->score;
                                $this_question_peices = $answer->Question->Answers->where('is_deleted', '=', NULL)->count_all();
                                $answer->mark = number_format(($question_total_mark / $this_question_peices), 2, '.', '');
                                //$correct_rearrange_answers += 1;
                            } else {
                                $answer->mark = 0;
                            }
                        } elseif ($answer->Question->question_type == 7) { //سؤال توصيل
                            //$total_match_questions += 1;
                            if ($answer->status == 1) {
                                $question_total_mark = ORM::factory('Online_Exams_Questions')->where('course_exam_id', '=', $exam)->where('question_id', '=', $answer->question)->find()->score;
                                $this_question_peices = $answer->Question->Answers->where('is_deleted', '=', NULL)->count_all();
                                $answer->mark = number_format(($question_total_mark / $this_question_peices), 2, '.', '');
                                //$correct_match_answers += 1;
                            } else {
                                $answer->mark = 0;
                            }
                        }
                        $answer->save();
                        // elseif($answer->Question->question_type == 5){ //سؤال خريطة
                        //     $has_written = 1;
                        // }
                    }

                    if (count($answers) > 0) {
                        $this_status = NULL;
                        //foreach($questions as $question){ //لو هنحدد الاسئلة اللى الطالب محلهاش
                        foreach ($answers as $key => $value) {
                            if (strpos($key, '_') !== false) {
                                $split = explode("_", $key);
                                $key = $split[0]; //question id
                                $order = $split[1];
                                // print_r($key);
                                // print_r($order);
                            }
                            $question_type = ORM::factory('Online_Courses_Questions', $key);
                            if ($question_type->loaded() && $question_type->question_type == 5 && isset($order)) {
                                $student_answer = ORM::factory('Online_StudentAnswers')->where('student_exam', '=', $student_exam->id)->where('question', '=', $key)->where('answer_order', '=', $order)->find();
                            } else {
                                $student_answer = ORM::factory('Online_StudentAnswers')->where('student_exam', '=', $student_exam->id)->where('question', '=', $key)->find();
                            }
                            if (!$student_answer->loaded()) {
                                $student_answer->student_exam = $student_exam->id;
                                $student_answer->question = $key;
                                $student_answer->Created_by = $this->user_online->id;
                                $student_answer->Created_date = date("Y-m-d H:i:s");
                            } else {
                                $student_answer->last_update_by = $this->user_online->id;
                                $student_answer->last_update_date = date("Y-m-d H:i:s");
                            }
                            if ($question_type->loaded() && in_array($question_type->question_type, [1, 2])) {
                                //foreach($value as $answer){  //this loop for multichoice questions
                                //$answerid = $answer;
                                $check_answer = ORM::factory('Online_Courses_Answers', $value);
                                if (($check_answer->loaded()) && ($check_answer->status == 1)) {
                                    //$correct_select_answers += 1;
                                    $exam_question = ORM::factory('Online_Exams_Questions')->where('course_exam_id', '=', $exam)->where('question_id', '=', $key)->find();
                                    $student_answer->mark = $exam_question->score;
                                    $this_status = 1;
                                } else {
                                    $student_answer->mark = 0;
                                    $this_status = 2;
                                }
                                $student_answer->answer = $value;

                            } elseif ($question_type->loaded() && $question_type->question_type == 3) {
                                $check_answer = $question_type->status;
                                if ($check_answer == $value) {
                                    //$correct_truefalse_answers += 1;
                                    $exam_question = ORM::factory('Online_Exams_Questions')->where('course_exam_id', '=', $exam)->where('question_id', '=', $key)->find();
                                    $student_answer->mark = $exam_question->score;
                                    $this_status = 1;
                                } else {
                                    $student_answer->mark = 0;
                                    $this_status = 2;
                                }
                                $student_answer->student_answer = $value;
                            } elseif ($question_type->loaded() && $question_type->question_type == 4) {
                                $student_answer->answer_description = $value;
                                $this_status = NULL;
                                $has_written = 1;
                            } elseif ($question_type->loaded() && $question_type->question_type == 5 && isset($order)) {
                                $student_answer->answer_description = $value;
                                $student_answer->answer_order = $order;
                                $this_status = NULL;
                                $has_written = 1;
                            }
                            $student_answer->status = $this_status;


                            $student_answer->save();
                            if (!empty($_FILES['answer'])) {
                                // validation file image
                                $files = Validation::factory($_FILES)
                                    ->rule('answer', 'Upload::type', array(':value', array('jpg', 'jpeg', 'png', 'docx', 'pdf', 'doc')));

                                if ($files->check() && !empty($_FILES['answer']['name']['photo'][$key])) {

                                    Upload::$remove_spaces = TRUE;
                                    $answer = array();
                                    $uploadedfile['name'] = preg_replace('/\s+/', '', $_FILES['answer']['name']['photo'][$key]);
                                    $uploadedfile['type'] = $_FILES['answer']['type']['photo'][$key] . "</br>";
                                    $uploadedfile['tmp_name'] = $_FILES['answer']['tmp_name']['photo'][$key];
                                    $uploadedfile['error'] = $_FILES['answer']['error']['photo'][$key];
                                    $uploadedfile['size'] = $_FILES['answer']['size']['photo'][$key];
                                    $array = array('name' => $uploadedfile['name'],
                                        'type' => $uploadedfile['type'],
                                        'tmp_name' => $uploadedfile['tmp_name'],
                                        'error' => $uploadedfile['error'],
                                        'size' => $uploadedfile['size']);
                                    $answer[$key] = $array;
                                    $image1 = '';
                                    if (isset($answer[$key]) && $answer[$key]['name'] != '') {
                                        $image1 = $this->_save_file($answer[$key]);
                                    }
                                    if ($image1 != '') {
                                        $doc = ORM::factory('Online_Attachments')->where('type', '=', 3)->where('type_id', '=', $student_answer->id)->find();
                                        if ($doc->loaded()) {
                                            if (!empty($doc->file_path)) {
                                                $DelFilePath = DOCROOT . $doc->file_path;
                                                if (is_file($DelFilePath)) {
                                                    unlink($DelFilePath);
                                                }
                                            }
                                            $doc->last_update_by = $this->user_online->id;
                                            $doc->last_update_date = date("Y-m-d H:i:s");
                                        } else {
                                            $doc->Created_by = $this->user_online->id;
                                            $doc->Created_date = date("Y-m-d H:i:s");
                                            $doc->type = 3;//answer+student id
                                            $doc->type_id = $student_answer->id;
                                        }
                                        $doc->file_path = $image1;
                                        $doc->save();
                                    }

                                }
                            }


                            //}

                        }
                    }
                    //}
                }
                // if($total_rearrange_marks != 0){
                //     $rearrange_question_mark = ($total_rearrange_marks/$total_arrange_questions);  //لان مجموع اسئلة الترتيب مختلف عن الباقى
                // }

                // if($total_match_marks != 0){
                //     $match_question_mark = ($total_match_marks/$total_match_questions);  //لان مجموع اسئلة التوصيل مختلف عن الباقى
                // }

                // $total_select_degrees = ($correct_select_answers*$select_question_mark);
                // $total_truefalse_degrees = ($correct_truefalse_answers*$truefalse_question_mark);

                // $total_match_degrees = ($correct_match_answers*$match_question_mark);
                // $total_rearrange_degrees = ($correct_rearrange_answers*$rearrange_question_mark);

                //$total_exam_degrees = $total_select_marks + $total_truefalse_marks + $total_match_marks + $total_rearrange_marks;
                //$total_student_degrees = round(($total_select_degrees + $total_truefalse_degrees + $total_match_degrees + $total_rearrange_degrees),2);
                $total_exam_degrees = $exam->total;
                $total_student_degrees = Controller_NewHome_Sections::GetTotalDegrees($exam, $this->user_online->id);
                if ($has_written == 0 && ($exam->trial != 1) && ($exam->Exam->id != 4)) {
                    $student_degree = ORM::factory('Students_Courses_Degrees')->where('term', '=', $this->Current_Term->id)->where('student', '=', $this->user_online->id)->where('course', '=', $student_section->Section->course)->where('exam', '=', $exam->exam)->find();
                    if ($student_degree->loaded()) {
                        $student_degree->degree = $total_student_degrees;
                        $student_degree->last_update_by = $this->user_online->id;
                        $student_degree->last_update_date = date("Y-m-d H:i:s");
                    } else {
                        $student_degree->term = $this->Current_Term->id;
                        $student_degree->student = $this->user_online->id;
                        $student_degree->course = $student_section->Section->course;
                        $student_degree->section = $student_section->section;
                        $student_degree->exam = $exam->exam;
                        $student_degree->degree = number_format($total_student_degrees, 2, '.', '');
                        $student_degree->status = 2;
                        $student_degree->Created_by = $this->user_online->id;
                        $student_degree->Created_date = date("Y-m-d H:i:s");
                    }
                    $student_degree->save();
                }

                if ($display_result != 1) {  //لو اعدادات الاختبار تمنع عرض الدرجات
                    $has_written = 1;
                }
                $view = View::factory('new_theme/sections/ukresult');
                $view->set('lang', $this->locale[$exam->locale]);
                $view->set('course_exam', $exam);
                $view->set('course', $student_section->Section->Course->{'name_' . $this->locale[$exam->locale]});

                $view->set('teacher', $student_section->Section->Teacher->{'name_' . $this->locale[$exam->locale]});
                $view->set('has_written', $has_written);
                $view->set('Student_Info', $this->user_online);
                $view->set('total_exam_degrees', $total_exam_degrees);
                $view->set('total_student_degrees', $total_student_degrees);

                $this->response->body($view);

            } else {

                $view = View::factory('system/show_msg');
                $view->set('class_color', 'danger');
                $view->set('msg', Lang::__('exam_time_run_out'));

                $this->response->body($view);

            }

        } else {
            $view = View::factory('system/show_msg');
            $view->set('class_color', 'danger');
            $view->set('msg', Lang::__('You_have_completed_this_exam'));

            $this->response->body($view);


        }
    }


    public function GetTotalDegrees($examid, $student)
    {
        $total = 0;
        $exam = ORM::factory('Online_Exams', $examid);
        $student_exam = ORM::factory('Online_StudentExams')->where('exam', '=', $examid)->where('student', '=', $student)->find();
        $student_answers = ORM::factory('Online_StudentAnswers')->where('student_exam', '=', $student_exam->id)->find_all();
        foreach ($student_answers as $student_answer) {
            if ($student_answer->mark != NULL) {
                $total += $student_answer->mark;
            }
        }
        return $total;
    }

    public function action_Answers(){


        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $exam = $Filtered_array['par1'];

        $student =  $this->user_online->id;
        $exam = ORM::factory('Online_Exams', $exam);
        $display_result = $exam->Exam->display_result;
        $has_written=0;
        if ($display_result != 1) {  //لو اعدادات الاختبار تمنع عرض الدرجات
            $has_written = 1;
        }

        $student_section = Controller_Student_Assessment::getSharedSections($exam, $student);
        $total_exam_degrees = $exam->total;

        $total_student_degrees = Controller_NewHome_Sections::GetTotalDegrees($exam,$student);


        $view = View::factory('new_theme/sections/ukresult');
        $view->set('lang', $this->locale[$exam->locale]);
        $view->set('course_exam', $exam);
        $view->set('course', $student_section->Section->Course->{'name_' . $this->locale[$exam->locale]});

        $view->set('teacher', $student_section->Section->Teacher->{'name_' . $this->locale[$exam->locale]});
        $view->set('has_written', $has_written);
        $view->set('Student_Info', $this->user_online);
        $view->set('total_exam_degrees', $total_exam_degrees);
        $view->set('total_student_degrees', $total_student_degrees);

        $this->response->body($view);

    }


    public function action_RightAnswer()
    {


        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $exam = 1;//$Filtered_array['par1'];

        $student =  $this->user_online->id;

        $student_exam = ORM::factory('Online_StudentExams',$exam);
        $exam_parts = $student_exam->Exam->Parts->where('is_deleted','=',NULL)->find_all();



        $exam_total = $student_exam->Exam->total;
        $total_mark = 0;
        $student_answers = $student_exam->StudentAnswers->find_all();
        foreach($student_answers as $student_answer){
            $total_mark += $student_answer->mark;
        }

        if($total_mark > $exam_total){
            $total_mark = $exam_total;
        }

        if ($student_exam->loaded() && ($student_exam->student == $this->user_online->id) && ($student_exam->Exam->Exam->display_result == 1)) {
            $title = Lang::__('exam');
            $view = View::factory('new_theme/sections/exercise_right_answers');
            $view->set('title', Lang::__('student_answers'));
            $view->set('exam', $student_exam->exam);
            $view->set('student_exam',$student_exam );
            $view->set('student',$student );
            $view->set('exam_parts', $exam_parts);
            $view->set('exam_total', $exam_total);
            $view->set('total_mark',$total_mark );
            $view->set('title', $title);
            $view->set('lang',$this->lang );



            $this->response->body($view);


        }
        else{

            $view = View::factory('system/show_msg');
            $view->set('class_color', 'danger');
            $view->set('msg', Lang::__('You_dont_have_permission_to_access_this_page'));

            $this->response->body($view);
        }
    }


    public function action_WrongAnswer()
    {


        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $exam = 1;//$Filtered_array['par1'];

        $student =  $this->user_online->id;

        $student_exam = ORM::factory('Online_StudentExams',$exam);
        $exam_parts = $student_exam->Exam->Parts->where('is_deleted','=',NULL)->find_all();



        $exam_total = $student_exam->Exam->total;
        $total_mark = 0;
        $student_answers = $student_exam->StudentAnswers->find_all();
        foreach($student_answers as $student_answer){
            $total_mark += $student_answer->mark;
        }

        if($total_mark > $exam_total){
            $total_mark = $exam_total;
        }

        if ($student_exam->loaded() && ($student_exam->student == $this->user_online->id) && ($student_exam->Exam->Exam->display_result == 1)) {
            $title = Lang::__('exam');
            $view = View::factory('new_theme/sections/exercise_wrong_answers');
            $view->set('title', Lang::__('student_answers'));
            $view->set('exam', $student_exam->exam);
            $view->set('student_exam',$student_exam );
            $view->set('student',$student );
            $view->set('exam_parts', $exam_parts);
            $view->set('exam_total', $exam_total);
            $view->set('total_mark',$total_mark );
            $view->set('title', $title);
            $view->set('lang',$this->lang );



            $this->response->body($view);


        }
        else{

            $view = View::factory('system/show_msg');
            $view->set('class_color', 'danger');
            $view->set('msg', Lang::__('You_dont_have_permission_to_access_this_page'));

            $this->response->body($view);
        }
    }


}