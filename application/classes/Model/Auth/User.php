<?php

defined('SYSPATH') OR die('No direct access allowed.');
require DOCROOT . '/application/classes/phpqrcode/qrlib.php';

/**
 * Default auth user
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Model_Auth_User extends ORM {

    /**
     * A user has many tokens and roles
     *
     * @var array Relationhips
     */
    protected $_has_many = array(
        'user_tokens' => array('model' => 'User_Token'),
        'roles' => array('model' => 'Role', 'through' => 'roles_users'),
        'Permissions_Usersrelations' => array('model' => 'Permissions_Usersrelations', 'foreign_key' => 'user'),
        'Permissions_Relations' => array(
            'model' => 'Permissions_Relations',
            'through' => 'permissions_groups_users_relations',
            'far_key' => 'groub',
            'foreign_key' => 'user',
        ),
        'Student_Terms' => array('model' => 'Students_Terms', 'foreign_key' => 'student'),
        'Student_Sections' => array('model' => 'Students_Sections', 'foreign_key' => 'student'),
    );
    protected $_belongs_to = array(
        'User_Groub' => array('model' => 'Usersgroub', 'foreign_key' => 'user_groub'),
        'Creater' => array('model' => 'User', 'foreign_key' => 'Created_by'),
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college')
    );
    protected $_has_one = array(
        'Student_Information' => array('model' => 'Students_Applications', 'foreign_key' => 'student_id',),
        'Employee_Information' => array('model' => 'Hr_Employment_Applications', 'foreign_key' => 'user_id',),
        'Trainer' => array('model' => 'Trainers_Applications', 'foreign_key' => 'user_id',),
        'Trainee' => array('model' => 'Trainers_Trainees', 'foreign_key' => 'user_id',),
        'Parent_Information' => array('model' => 'Students_Parents', 'foreign_key' => 'user_id',)


    );

    public function GetPersonalImage() {
        $default_image = URL::base() . 'assets/new_theme/assets/img/user.png';
        if(!empty($this->img)){
            if (strpos($this->img, 'files/users/') !== false) {
                $default_image = URL::base() . $this->img;
            }else{
                $default_image = $this->img;
            }

        }else{
            if(!empty($this->Employee_Information) && ($this->Employee_Information->img_path != NULL)){
                $default_image = URL::base() . $$this->Employee_Information->img_path;
            }elseif (!empty($this->Student_Information)){
                $student_picture = $this->Student_Information->Documents->where('document_type','=',1)->where('is_deleted','=',NULL)->find();
                if($student_picture->loaded()){
                    $default_image = URL::base() .$student_picture->file_path;
                }
            }
        }
        return $default_image;
    }

    public function GetName($lang = 'ar') {
        if(!empty($this->Employee_Information)){
            return $this->Employee_Information->GetFullName($lang);
        }elseif (!empty($this->Student_Information)){
            return $this->Student_Information->FullName($lang);
        }else{
            return $this->{"name_".$lang};
        }
    }

    /**
     * Rules for the user model. Because the password is _always_ a hash
     * when it's set,you need to run an additional not_empty rule in your controller
     * to make sure you didn't hash an empty string. The password rules
     * should be enforced outside the model or with a model helper method.
     *
     * @return array Rules
     */
    public function rules() {
        return array(
            'username' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
                array(array($this, 'unique'), array('username', ':value')),
            ),
            'password' => array(
                array('not_empty'),
            ),
            'user_groub' => array(
                array('not_empty'),
            ),
            'email' => array(
                array('not_empty'),
            //array('email'),
            //array('email_domain'),
                array(array($this, 'unique'), array('email', ':value')),
            ),
            'name_en' => array(
                //array('Model_Rules::englishonly', array(':field', ':validation', ':value')),
            ),
            'name_ar' => array(
                //array('Model_Rules::arabiconly', array(':field', ':validation', ':value')),
            ),
        );
    }

    /**
     * Filters to run when data is set in this model. The password filter
     * automatically hashes the password when it's set in the model.
     *
     * @return array Filters
     */
    public function filters() {
        return array(
            'password' => array(
                array(array(Auth::instance(), 'hash'))
            )
        );
    }

    /**
     * Labels for fields in this model
     *
     * @return array Labels
     */
    public function labels() {
        return array(
            'username' => 'username',
            'email' => 'email address',
            'password' => 'password',
        );
    }

    /**
     * Complete the login for a user by incrementing the logins and saving login timestamp
     *
     * @return void
     */
    public function complete_login() {
        if ($this->_loaded) {
            // Update the number of logins
            $this->logins = new Database_Expression('logins + 1');

            // Set the last login date
            $this->last_login = date("Y-m-d H:i:s");

            // Save the user
            if($this->update()){
                $user_login = ORM::factory('Users_Logins');
                $user_login->user_id = $this->id;
                $user_login->Created_date = date("Y-m-d H:i:s");
                $user_login->Created_by = $this->id;
                $user_login->save();
            }
        }
    }

    /**
     * Tests if a unique key value exists in the database.
     *
     * @param   mixed    the value to test
     * @param   string   field name
     * @return  boolean
     */
    public function unique_key_exists($value, $field = NULL) {
        if ($field === NULL) {
            // Automatically determine field by looking at the value
            $field = $this->unique_key($value);
        }

        return (bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))
                        ->from($this->_table_name)
                        ->where($field, '=', $value)
                        ->where($this->_primary_key, '!=', $this->pk())
                        ->execute($this->_db)
                        ->get('total_count');
    }

    /**
     * Allows a model use both email and username as unique identifiers for login
     *
     * @param   string  unique value
     * @return  string  field name
     */
    public function unique_key($value) {
        return Valid::email($value) ? 'email' : 'username';
    }

    /**
     * Password validation for plain passwords.
     *
     * @param array $values
     * @return Validation
     */
    public static function get_password_validation($values) {
        return Validation::factory($values)
        //->rule('password', 'min_length', array(':value', 8))
        //->rule('password_confirm', 'matches', array(':validation', ':field', 'password'))
        ;
    }

    /**
     * Create a new user
     *
     * Example usage:
     * ~~~
     * $user = ORM::factory('User')->create_user($_POST, array(
     * 	'username',
     * 	'password',
     * 	'email',
     * );
     * ~~~
     *
     * @param array $values
     * @param array $expected
     * @throws ORM_Validation_Exception
     */
    public function create_user($values, $expected) {
        // Validation for passwords
        $extra_validation = Model_User::get_password_validation($values)
                ->rule('password', 'not_empty');

        return $this->values($values, $expected)->create($extra_validation);
    }

    /**
     * Update an existing user
     *
     * [!!] We make the assumption that if a user does not supply a password, that they do not wish to update their password.
     *
     * Example usage:
     * ~~~
     * $user = ORM::factory('User')
     * 	->where('username', '=', 'kiall')
     * 	->find()
     * 	->update_user($_POST, array(
     * 		'username',
     * 		'password',
     * 		'email',
     * 	);
     * ~~~
     *
     * @param array $values
     * @param array $expected
     * @throws ORM_Validation_Exception
     */
    public function update_user($values, $expected = NULL) {
        if (empty($values['password'])) {
            unset($values['password'], $values['password_confirm']);
        }

        // Validation for passwords
        $extra_validation = Model_User::get_password_validation($values);

        return $this->values($values, $expected)->update($extra_validation);
    }

    public function hasRole($alias, $far_keys = NULL) {
        //If user is admin reture true
        if ($this->user_groub == 1) {
            return TRUE;
        } else {
            //check direct role
            if ($this->has($alias, $far_keys)) {
                return TRUE;
            } else {
                //check groub roles
                $User_Groubs = $this->Permissions_Usersrelations->find_all();
                foreach ($User_Groubs as $User_Groub_Relation) {
                    if ($User_Groub_Relation->Groub->has('Roles', ORM::factory('Role', $far_keys))) {
                        return TRUE;
                    }
                }
            }
        }
    }

    /*
     * return true if user is teacher
     */

    public function IsTeacher() {
        //If user is admin reture true
        if ($this->user_groub == 4) {
            return TRUE;
        } else {
            if (!empty($this->Employee_Information->Employee_Type->is_teacher)) {
                return TRUE;
            }
        }

        return FALSE;
    }
    
    /*
     * return true if user is Girl
     */

    public function IsGirl() {
        //If user is admin reture true
        $is_girl = false;
        if ($this->Student_Information->Gender == 2) {
            $is_girl = TRUE;
        } elseif ($this->Employee_Information->gender == 2) {
           $is_girl = TRUE;
        }

        return $is_girl;
    }
    
    public static function GetAllTeachers() {
        $R = ORM::factory('User')->where('user_groub', 'IN', array(2,4,5))->where('is_deleted', '=', NULL)->find_all();
        $Teachers = array();
        foreach ($R as $user) {
            if($user->IsTeacher()){
                array_push($Teachers, $user);
            }
        }
        return $Teachers;
    }

    public static function GetAllSchoolTeachers($schoolId) {
        $ST = ORM::factory('User')->where('user_groub', 'IN', array(2,4,5))->where('college', '=', $schoolId)->where('is_deleted', '=', NULL)->find_all();
        $School_Teachers = array();
        foreach ($ST as $teacher) {
            if($teacher->IsTeacher()){
                array_push($School_Teachers, $teacher);
            }
        }
        return $School_Teachers;
    }

    /*
     * return true if user is exam coordinator
     */

    public function IsCoordinator() {
        $coordinator_courses = ORM::factory('Online_Coordinators')->where('teacher','=', $this->id)->where('is_deleted','=',NULL)->find();
        if ($coordinator_courses->loaded()) {
            return TRUE;
        } 
        return FALSE;
    }

    public static function SendEmail($subject, $email_to, $username, $content){
        //require 'vendor/autoload.php'; // If you're using Composer (recommended)
        // Comment out the above line if not using Composer
        require("application/config/sendgrid/sendgrid-php.php");
        // If not using Composer, uncomment the above line and
        // download sendgrid-php.zip from the latest release here,
        // replacing <PATH TO> with the path to the sendgrid-php.php file,
        // which is included in the download:
        // https://github.com/sendgrid/sendgrid-php/releases

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("edusoft2019@gmail.com", "Right Learning");
        $email->setSubject($subject);
        $email->addTo($email_to, $username);
        //$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
            "text/html", $content
        );
        
        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";

        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        } 
    }

    public function generateQrCode() {
        $directory = 'files/qrcodes/users/';
        if (!empty($directory)) {
            $directory_D = DOCROOT . str_replace("\\", "/", $directory);
            if (!file_exists($directory_D)) {
                mkdir($directory_D, 0777, true);
            }
        }
        // user data
        $errorCorrectionLevel = "H";
        $matrixPointSize = 5 ;
        $qr_code = md5($this->username.'|'.$errorCorrectionLevel.'|'.$matrixPointSize);
        $url = ORM::factory('Variables',2)->value . "Login/QRCodeLogin/" . $this->id . "/" . $qr_code ;
        $filename = $directory.$qr_code.'.png';
        QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 
        return  $qr_code;
    }

    public static function SetGoogleCredentials(){
        $results = array();
        $Google_Keys = ORM::factory('Variables',137);
        $results["client_id"] = $Google_Keys->value_ar;
        $results["client_secret"] = $Google_Keys->value_en;
        return $results;
    }


}

// End Auth User Model
