<?php

class StandardController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {

            default:
                $out = "hallo";
                break;
        }
        return $out;
    }

}
?>