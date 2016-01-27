<?php

/**
 * Description of Auto
 *
 * @author Teilnehmer
 */
class Auto implements Aenderbar, JsonSerializable {

    private $id;
    private $hersteller; // objekt
    private $name;
    private $kennzeichen;

    public static function getNames() {
        return ['Hersteller','Modell','Kennzeichen'];
    }

    public function __construct($name, Hersteller $hersteller, $kennzeichen, $id = NULL) {
        $this->hersteller = $hersteller;
        $this->name = $name;
        $this->kennzeichen = $kennzeichen;
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getHersteller() {
        return $this->hersteller;
    }

    public function getName() {
        return $this->name;
    }

    public function getKennzeichen() {
        return $this->kennzeichen;
    }

    public static function getAll() {
        $pdo = DbConnect::connect();
        $sql = "SELECT * from auto";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $autos = [];

        foreach ($rows as $row) {
            $autos[$row['id']] = new Auto($row['name'], Hersteller::getById($row['hersteller_id']), $row['kennzeichen'], $row['id']);
        }
        return $autos;
    }

    public function jsonSerialize() {
        return['id' => $this->id,
            'hersteller' => $this->hersteller,
            'name' => $this->name,
            'kennzeichen' => $this->kennzeichen];
    }

    public static function delete($id) {
        
    }

    public static function getById($id) {
        $pdo = DbConnect::connect();
        $sql = "SELECT * from auto WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return new Auto($rows[0]['name'], Hersteller::getById($rows[0]['hersteller_id']), $rows[0]['kennzeichen'], $rows[0]['id']);
    }

    public static function insert($id) {
        
    }

    public static function update($obj) {
        
    }

}
