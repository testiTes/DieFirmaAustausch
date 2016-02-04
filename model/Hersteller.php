<?php

/**
 * Description of Hersteller
 *
 * @author Teilnehmer
 */
class Hersteller implements Aenderbar, JsonSerializable {

    private $id;
    private $name;

    public static function getNames() {
        return ['Hersteller'];
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
        $sql = "SELECT * from hersteller ORDER BY id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $hersteller = [];

        foreach ($rows as $row) {
            $hersteller[$row['id']] = new Hersteller($row['name'], $row['id']);
        }
        return $hersteller;
    }

    public static function getById($id) {
        $pdo = DbConnect::connect();
        $sql = "SELECT * from hersteller WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return new Hersteller($rows[0]['name'], $rows[0]['id']);
    }

    public static function update($obj) {
        $pdo = DbConnect::connect();
        $sql = "UPDATE hersteller SET name =:name WHERE id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':name' => $obj->getName(), ':id' => $obj->getId()]);
    }

    public static function insert($id) {
        $pdo = DbConnect::connect();
        $sql = "INSERT INTO hersteller(name) VALUES (:name)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':name' => $id->getName()]);
    }

    public static function delete($id) {
        $pdo = DbConnect::connect();
        $sql = "DELETE from hersteller WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

}
