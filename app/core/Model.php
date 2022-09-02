<?php
/*
** Master Model
*
*/
declare(strict_types=1);
namespace app\core;
use app\core\Database;

class Model extends Database {

    public array $errors = [];

    function __construct() {
        // Adding property exist to check for table propery of any model instantiated
        
        //echo get_class($this);
        //var_dump(property_exists($this, 'table'));

        if(!property_exists($this, 'table')) {
            $this->table = strtolower(get_class($this)) . "s";
        } 
    }

    public function where(string $column, string $value, string $order_by = 'DESC', int $limit = 100, int $offset = 0) {
        // check if class name is corect else get default table      

        $column = addslashes($column);
        $this->query("SELECT * FROM $this->table WHERE $column = :value ORDER BY id $order_by limit $limit offset $offset");

        $this->bind(':value', $value);
        $data = $this->resultSet();
        
        // Run function after finding where
        if(is_array($data)) {
            
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $data = $this->$func($data);
                }
            }
            
            return $data;
        } 
        return $data;
    }

    

    public function findAll(string $order_by = 'DESC', $limit = 100, $offset = 0) {
        
        // check if class name is corect else get default table
       $this->query("SELECT * FROM $this->table ORDER BY id $order_by limit $limit offset $offset");
       $data = $this->resultSet();  // Array of objects
       
        // Run function after Select
        if(is_array($data)) {
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $data = $this->$func($data);
                }
            }
            //show($data); die;
            return $data;
        } 

    }


    public function insert(array $data, $table = null) {
        //show($data); echo $table; die;
        // Remove unwanted columns
        if(property_exists($this, 'allowedColumns')) {
            foreach($data as $key => $column) {
                if(!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        } 


        // Run function before insert
        if(property_exists($this, 'beforeInsert')) {
            foreach($this->beforeInsert as $func) {
                $data = $this->$func($data);
            }
        } 
        

        $keys = array_keys($data);
        $columns = implode(',' , $keys);
        $values = implode(',:' , $keys);

        // check if class name is corect else get default table
        $db_table = $this->table;
        if(!$table == null) {
            $db_table = $table;
        }  

        //show($data); die;
        $this->query("INSERT INTO $db_table ($columns) VALUES(:$values)"); 
        return $this->execute($data);
    }


    public function update($id, array $data, $table = NULL) {
        // Remove unwanted columns
        if(property_exists($this, 'allowedColumns')) {
            foreach($data as $key => $column) {
                if(!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        } 

      
        // Run function before insert
        if(property_exists($this, 'beforeUpdate')) {
            foreach($this->beforeUpdate as $func) {
                $data = $this->$func($data);
            }
        } 

       //show($data); die;
       

        $data['id'] = $id;
        $str = '';

        foreach($data as $key => $value) {
            $str .= $key . "=:" . $key . ",";
        }

        $str = trim($str, ','); 

        

        // check if class name is corect else get default table
        $db_table = is_null($table) ? $this->table : $table;

        //show($data); die;

        // show($data);
        // echo $str; echo $db_table; die;

        $this->query("UPDATE $this->table SET $str WHERE id = :id");
        return $this->execute($data);
    }


    public function delete($id) {
       
        $this->query("DELETE FROM $this->table WHERE id = :id");    
        $data['id'] = $id;
        return $this->execute($data);
    }


    // checking if row exist
    public function row_exist(array $data, string $table = NULL, string $result = null) {
        //show($data); 
        //show($data);  
        //checking if date column exist and answer column exist
        unset($data['answer']);
        unset($data['date']);
        
        $str = ' ';
        foreach($data as $key => $value) {
            $str .= $key . '=:' . $key . '&&';
        }

        $str = trim($str, '&&');
       
       // echo $this->table;
        // check if class name is corect else get default table
        $db_table = is_null($table) ? $this->table : $table;

        //show($data); echo $str;         echo $db_table;  

        $this->query("SELECT * FROM $db_table WHERE $str ORDER by id DESC");
        $this->execute($data); 

        if($this->rowCount() > 0) {
           $result = is_null($result) ? $this->resultSet() : $this->$result();
           //show($result); die;

           // Run function after Select
            if(is_array($result)) {
                if(property_exists($this, 'afterSelect')) {
                    foreach($this->afterSelect as $func) {
                        $result = $this->$func($result);
                    }
                }
                //show($data); die;
                return $result;
            } 
            return $result;
        } 

        return false;
    }

    
}