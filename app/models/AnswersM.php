<?php
/*
** Answers Model
*
*/
declare(strict_types=1);

use app\core\Model;

class AnswersM extends Model {

    protected $table = 'answers';

    protected $allowedColumns = [
        'user_id',
        'test_id',
        'question_id',
        'answer', 
        'answer_mark', 
        'answer_comment',
        'date',
    ];

    protected $beforeInsert = [];

    protected $afterSelect = [];

    public function validate($data): bool {
        //show($data); die;
        // print_r($_SESSION['USER']);
        // die();
        $this->errors = [];

        // Checking if errors are empty
        if(empty($this->errors)) {
            return true;
        }

        return false;
    }


    public function update_submitted_test(array $data, array $update) {
        // show($data);
        // show($update); die;
        $condition = ' ';
        foreach($data as $key => $val) {
            $condition .= $key . '=:' . $key . '&&';
        }

        $condition = trim($condition, '&&');

        $value = '';
        foreach($update as $column => $val1) {
            $value .= $column . '='  ." ' ". (string) $val1 ." ' ".  ',';
        }

        $value = trim($value, ',');

        //echo $value; echo '<br/>'; echo $condition; die; 

        $this->query("UPDATE submited_test SET $value WHERE $condition ");
        return $this->execute($data);
    }


    public function get_question_type(string $test_id): array {
        $this->query("SELECT id, correct_answer FROM test_questions WHERE test_id = :test_id && (question_type = 'objective' || question_type = 'multiple')");
        $this->bind(':test_id', $test_id);
        $this->executeBind();
        return $this->resultSet();
    }

}