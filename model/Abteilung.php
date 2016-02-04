<?php

/**
 * Description of Abteilung
 *
 * @author Teilnehmer
 */
class Abteilung implements Aenderbar, JsonSerializable {

    private $id;
    private $name;

    public static function getNames() {
        return ['Abteilung'];
    }

    public function __construct($name, $id = NULL) {
        $this->name = $name;
        $this->id = $id;
    }

    public function jsonSerialize() {
        return['id' => $this->id,
            'name' => $this->name];
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public static function getAll() {
        $pdo = DbConnect::connect();
        $sql = "SELECT * from abteilung";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $abteilung = [];

        foreach ($rows as $row) {
            $abteilung[$row['id']] = new Abteilung($row['name'], $row['id']);
        }
        return $abteilung;
    }

    public static function getById($id) {
        $pdo = DbConnect::connect();
        $sql = "SELECT * from abteilung WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return new Abteilung($rows[0]['name'], $rows[0]['id']);
    }

    public static function update($obj) {
        $pdo = DbConnect::connect();
        $sql = "UPDATE abteilung SET name =:name WHERE id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':name' => $obj->getName(), ':id' => $obj->getId()]);
    }

    public static function insert($id) {
        $pdo = DbConnect::connect();
        $sql = "INSERT INTO abteilung(name) VALUES (:name)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':name' => $id->getName()]);
    }

    public static function delete($id) {
        $pdo = DbConnect::connect();
        $sql = "DELETE from abteilung WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

}
