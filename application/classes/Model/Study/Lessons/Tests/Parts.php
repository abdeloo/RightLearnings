<?php

class Model_Study_Lessons_Tests_Parts extends ORM {

    protected $_table_name = 'study_lessons_test_parts';
    protected $_belongs_to = array(
        'Lesson_Test' => array('model' => 'Study_Lessons_Documents', 'foreign_key' => 'study_document_id'),
    );
    protected $_has_many = array(
        'Questions' => array('model' => 'Study_Lessons_Tests_Questions', 'foreign_key' => 'study_part_id'),
    );
  

   
    
}
