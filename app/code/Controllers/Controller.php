<?php


namespace Controllers;

use Models;

class Controller
{
    public function model($model) {
        if (file_exists(__ROOT__ . "app/code/Models/" . $model . ".php")) {
            // require model file
            require_once __ROOT__ . "app/code/Models/" . $model . ".php";
            // instantiate model
            $className = 'Models\\' . $model;
            return new $className();
        } else {
            die('Model does not exist');
        }
    }

    // load views
    public function view($view, $data = []) {
        ob_start();
        // check for view file
        if (file_exists(__ROOT__ . "app/frontend/" . $view . ".phtml")) {
            require_once __ROOT__ . "app/frontend/" . $view . ".phtml";
        } else {
            // view does not exist
            die("View does not exist");
        }
        return ob_get_clean();
    }
}