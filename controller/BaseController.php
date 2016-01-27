<?php

/**
 * Description of BaseController
 *
 * @author Teilnehmer
 */
class BaseController {

    public static function load($action, &$view, $area, $id) {
        //$controller ist erster Buchstabe von Area wird groß geschrieben + Controller
        $controller = ucfirst($area . 'Controller');
        return $controller::doAction($action, $view, $id);
    }

}
