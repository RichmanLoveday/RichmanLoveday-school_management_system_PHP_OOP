<?php
declare(strict_types=1);

use app\core\Database;

function clean_url($url) {
    $clean = rtrim($url, '/');
    $clean = filter_var($clean, FILTER_SANITIZE_URL);
    $clean = explode('/', $clean);

    return $clean;
}

function get_var(string $key, $default = NULL) {
    if(isset($_POST[$key])) {
        return $_POST[$key];
    }elseif(isset($_GET[$key])){
        return $_GET[$key];
    } 
    return $default;
}


function esc($var) {
    return htmlspecialchars($var);
}

function get_select(string $key, string $value): string {
    if(isset($_POST[$key])) {
        if($_POST[$key] == $value) {
            return "selected";
        }
    }

    return "";
}


function random_string(int $lenght): string {

    $array = [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    $text = '';
    for($x = 0; $x < $lenght; $x++) {
        $random = rand(0,61);
        $text .= $array[$random];

    }
    return $text;

}


function get_date($data) {

    return date('jS M, Y', strtotime($data));

}


function get_image($image, $gender) {

    if($image == NULL) {
        if($gender == 'male') {
            $image = ASSETS . '/user_male.png';
        } else {
            $image = ASSETS . '/user_female.png';
        }
    } else {
        $class = new Image();
        $image = URLROOT . '/' . $class->profile_thumb($image);
    }
    return $image;
    
}

function view_path(string $view):string {
    //extract($data);

    if(file_exists("../app/views/" . $view . ".inc.php")) {
        return "../app/views/" . $view . ".inc.php";
    } else {
        return "../app/views/404.view.php";
    }
    
}


function show($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}


function upload_image(array $file) {
    //check for files
    if(count($file) > 0) {
        //show($file); die;
        // file exist
        $allowed[] = 'image/jpeg';
        $allowed[] = 'image/png';

        // check if type of file is in array
        if($file['image']['error'] == 0 && in_array($file['image']['type'], $allowed)) {     
            
            // create a folder to move files
            $folder = 'uploads/';
            if(!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $destination = $folder . time() . '_' . $file['image']['name'];
            move_uploaded_file($file['image']['tmp_name'], $destination);            
            return $destination;
        }
    }
    return false;
}

function has_taken_test($test_id) {
    return 'No';
}


function can_take_test($test_id) {

    // Instance of class model to get a classs
    $class = new ClassM();
    $class_combination = '';

    // switching rank between lecturer and students from the user table to to send to the test tab
    if(Auth::getRank() == 'student') {
        $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
        $class_combination = $class->fetchClass(Auth::getUser_id(), $year);
        return true;
    } else {
        return false;
    }


    //show($stu_classes); die;
    $user_class = [];     // array to store any student class found
    if(is_array($class_combination)) {
        foreach($class_combination as $key => $value) {
            // getting class data
            $user_class = $class->where('class_id', $value->class_id);
        }
    }

    // collect class ID's
    $class_ids = [];
    foreach($user_class as $key => $class_row) {
        $class_ids[] = $class_row->class_id; 
    }

    $id_str = "'" . implode("','", $class_ids) . "'";
    $test_model = new TestM();
    $tests = $test_model->fetch_stu_tests($id_str);
    
    $my_tests = array_column($tests, 'test_id');
    //show($user_class);
    if(in_array($test_id, $my_tests)) {
        return true;
    }
    //show($my_tests); die;
    return false;
}



function get_answer(string $question_id, array $saved_answers): string {
    if(!empty($saved_answers)) {
        foreach($saved_answers as $saved_answer) {
            if($saved_answer->question_id == $question_id) {
                return $saved_answer->answer;
            }
        }
    }
    return '';
}


function get_marked_answer(string $question_id, array $saved_answers): string {
    if(!empty($saved_answers)) {
        foreach($saved_answers as $saved_answer) {
            if($saved_answer->question_id == $question_id) {
                return $saved_answer->answer_mark;
            }
        }
    }
    return '';
}



// function get_answered_percentage(array $questions, array $saved_answers):float {
//     $total_count_answer = 0;

//     // looping through question IDs and getting answers
//     if(!empty($questions)) {
//         foreach($questions as $quest) {
//             $answer = get_answer($quest->id, $saved_answers);
//             if(trim($answer) != '') {
//                 $total_count_answer++;
//             }
//         }
//     }

//     // Getting percantage of questions
//     if($total_count_answer > 0) {
//         return round((($total_count_answer / count($questions)) * 100), 2);
//     }

//     return 0;
// }



function get_answered_percentage(string $test_id, string $user_id):float {

    $quest_model = new QuestionsM();
    $questions = $quest_model->get_all_quest($test_id);


    $ans_model = new AnswersM();
    $saved_answers = $ans_model->row_exist(['user_id' => $user_id, 'test_id' => $test_id]);
    $saved_answers = is_array($saved_answers) ? $saved_answers : [];

    $total_count_answer = 0;
    // looping through question IDs and getting answers
    if(!empty($questions)) {
        foreach($questions as $quest) {
            $answer = get_answer($quest->id, $saved_answers);
            if(trim($answer) != '') {
                $total_count_answer++;
            }
        }
    }

    // Getting percantage of questions
    if($total_count_answer > 0) {
        return round(($total_count_answer / count($questions)) * 100);
    }

    return 0;
}



function get_marked_percentage(string $test_id, string $user_id):float {

    $quest_model = new QuestionsM();
    $questions = $quest_model->get_all_quest($test_id);

    
    $ans_model = new AnswersM();
    $saved_answers = $ans_model->row_exist(['user_id' => $user_id, 'test_id' => $test_id]);
    $saved_answers = is_array($saved_answers) ? $saved_answers : [];

    $total_count_answer = 0;
    // looping through question IDs and getting answers
    if(!empty($questions)) {
        foreach($questions as $quest) {
            $answer = get_marked_answer($quest->id, $saved_answers);
            if(trim($answer) > 0) {
                $total_count_answer++;
            }
        }
    }

    // Getting percantage of questions
    if($total_count_answer > 0) {
        return round(($total_count_answer / count($questions) * 100));
    }

    return 0;
}


function get_score_percentage(string $test_id, string $user_id):float {
 
    $quest_model = new QuestionsM();
    $questions = $quest_model->get_all_quest($test_id);
    
    $ans_model = new AnswersM();
    $saved_answers = $ans_model->row_exist(['user_id' => $user_id, 'test_id' => $test_id]);
    $saved_answers = is_array($saved_answers) ? $saved_answers : [];

    $total_count_answer = 0;
    // looping through question IDs and getting answers
    if(!empty($questions)) {
        foreach($questions as $quest) {
            $answer = get_marked_answer($quest->id, $saved_answers);
            if(trim($answer) == 1) {
                $total_count_answer++;
            }
        }
    }

    // Getting percantage of questions
    if($total_count_answer > 0) {
        return round(($total_count_answer / count($questions)) * 100);
    }

    return 0;
}

function get_years() {
    $arr = [];
    $db = new Database();
    $db->query("SELECT date from classes order by id asc limit 1");
    $db->execute();
    $row = $db->resultSet();

    if($row) {
        $year = date("Y", strtotime($row[0]->date));
        $arr[] = $year;

        $cur_year =  date('Y', time());
        while($year < $cur_year) {
            $year += 1;
            $arr[] = $year;
        }
    } else {
        $arr[] = date('Y', time());
    }
    rsort($arr);
    return $arr;
}


switch_year();
function switch_year() {

    if(!isset($_SESSION['SCHOOL_YEAR'])) {
        $_SESSION['SCHOOL_YEAR'] = (object) [];
        $_SESSION['SCHOOL_YEAR']->year = date("Y", time());
    }

    if(!empty($_GET['year'])) {
        $year = (int) $_GET['year'];
        $_SESSION['SCHOOL_YEAR']->year = $year;
    }
}


