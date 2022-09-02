<?php
/*
** Master Controller
*
*/

declare(strict_types=1);
namespace app\core;

class Controller {

    public function view(string $view, array $data = []): void {
        extract($data);

        if(file_exists("../app/views/" . $view . ".view.php")) {
            require ("../app/views/" . $view . ".view.php");
        } else {
            require ("../app/views/404.view.php");
        }
    }

    public function load_model($model) {

        if(file_exists("../app/models/" . ucwords($model) . ".php")) {
            require ("../app/models/" . ucwords($model) . ".php");
            return $model = new $model();
        }
        return false;
    }

    public function redirect($link) {
        header('Location: '. URLROOT . "/" . trim($link, "/"));
        die();
    } 

    public function controller_name() {
        return get_class($this);
    }
}