<?php

interface Aenderbar
{
    public static function insert($id);
    public static function delete($id);
    public static function update($obj);
    public static function getById($id);
    public static function getAll();
}

?>

