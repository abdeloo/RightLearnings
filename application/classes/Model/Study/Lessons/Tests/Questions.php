<?php

class Model_Study_Lessons_Tests_Questions extends ORM {

    protected $_table_name = 'study_lessons_test_questions';
    protected $_belongs_to = array(
        'Part' => array('model' => 'Study_Lessons_Tests_Parts', 'foreign_key' => 'study_part_id'),
        'Question' => array('model' => 'Online_Courses_Questions', 'foreign_key' => 'question_id'),
    );
    

   
    
}
