<?php
/*
** Autentications models
*
*/
class Auth {

    public function __construct() {
       
    }

    // As row as an ID to a specific user
    public static function authenticate($row) {
        //show($row); die;

        // Creating a session for every user logged in
       $_SESSION['USER'] = $row;

       //show($_SESSION['USER']); die;
    }


    // Logging out a user
    public static function logout() {

        // logging out a user and unseting a user logged in
        if(isset($_SESSION['USER'])) {
            unset($_SESSION['USER']);
        }
    }

    // Checking if logged in
    public static function logged_in() {
        
        // checking if user is logged in
        if(isset($_SESSION['USER'])) {
            return true;
        } 
        return false;

    }


    public static function user(string $data) {

         // Display user name
        if(isset($_SESSION['USER'])) {
            return $_SESSION['USER']->firstname;
        } 
        return false;

    }


    // Calling a an unknown static method an performing a specific functionalities.
    public static function __callStatic($method, $params) {
        $prop = strtolower(str_replace('get', "", $method));

        if(isset($_SESSION['USER']->$prop)) {
            //show($_SESSION['USER']); die;
            return $_SESSION['USER']->$prop;
        }

        return 'Unknown';
    }


    // Switching to different schools already created
    public static function switch_school(string $id): bool {
        // checking if the present user is a super_admin
       if(isset($_SESSION['USER']) && Auth::access('super_admin')) {
         $userModel = new User();
         $schoolModel = new School();

        // checking to get the match of where id in  school table is same with the router
        if($row = $schoolModel->where('id', $id)) {
            $row = $schoolModel->single();

            // echo '<pre>';
            // print_r($row);
            // echo '<pre/>';
            // die();

            // storing the school id in an array
            $data = [
                'school_id' => $row->school_id,
            ];

            //show($_SESSION['USER']); die;

            // Upadating the school_id in the user table
            if($userModel->update($_SESSION['USER']->id, $data)) {
                // Adding school_id and school to the session variable
                $_SESSION['USER']->school_id = $row->school_id;
                $_SESSION['USER']->school_name = $row->school;
            }

        }
        
        return true;
       } 
       return false;

   }

    // Access to different functionalities
    public static function access(string $rank = 'student'): bool {
            
        // 
        if(!isset($_SESSION['USER'])) {
            return false;
        } 

        // Checking if rank is logged in
        $loged_in_rank = $_SESSION['USER']->rank;

        // creating who have access
        $RANK['super_admin']    = ['super_admin', 'lecturer', 'admin', 'reception', 'student'];
        $RANK['admin']          = ['admin', 'lecturer', 'reception', 'student'];
        $RANK['lecturer']       = ['lecturer', 'reception', 'student'];
        $RANK['reception']      = ['reception', 'student'];
        $RANK['student']        = ['student'];

        // if the login user in not set
        if(!isset($RANK[$loged_in_rank])) {
            return false;
        }

        // checking if select rank is in array
        if(in_array($rank, $RANK[$loged_in_rank])) {
            return true;
        }
        return false;

    }

    public static function i_own_content($row) {

        if(!isset($_SESSION['USER'])) {
            return false;
        } 

        if(is_array($row)) {
            $row = $row[0];
        }

        // checking if the row user_id ID is same with the session user ID   
        if(isset($row)) {
            if($_SESSION['USER']->user_id == $row->user_id) {
                return true;
            }
        }

        // super admin and admin having access to owned contents
        $allowed[] = 'super_admin';
        $allowed[] = 'admin';

        if(in_array($_SESSION['USER']->rank, $allowed)) {
            return true;
        }
        return false;
    }


}