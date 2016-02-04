<?php

class Ausleihe implements Aenderbar, Zeitmessbar, JsonSerializable {

    private $id;
    private $auto;
    private $mitarbeiter;
    private $von;
    private $bis;

    public static function getNames() {
        return ['Fahrzeug', 'Mitarbeiter', 'Von Tag', 'Von Uhrzeit', 'Bis Tag', 'Bis Uhrzeit'];
    }

    function __construct(Auto $auto, Mitarbeiter $mitarbeiter, $von, $bis, $id = NULL) {
        $this->id = $id;
        $this->auto = $auto;
        $this->mitarbeiter = $mitarbeiter;
        $this->von = $von;
        $this->bis = $bis;
    }

    public function jsonSerialize() {
        return['id' => $this->id,
            'auto' => $this->auto,
            'mitarbeiter' => $this->mitarbeiter,
            'von' => $this->von,
            'bis' => $this->bis];
    }

    function getId() {
        return $this->id;
    }

    function getAuto() {
        return $this->auto;
    }

    function getMitarbeiter() {
        return $this->mitarbeiter;
    }

    function getVon() {
        return $this->von;
    }

    function getBis() {
        return $this->bis;
    }

    public function getDauer() {
        
    }

    public static function getAll() {
        $pdo = DbConnect::connect();
        $sql = "SELECT * from ausleihe";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ausleihe = [];

        foreach ($rows as $row) {
            $ausleihe[$row['id']] = new Ausleihe(Auto::getById($row['auto_id']), Mitarbeiter::getById($row['mitarbeiter_id']), $row['von'], $row['bis'], $row['id']);
        }
        return $ausleihe;
    }

    public static function getById($id) {
        $pdo = DbConnect::connect();
        $sql = "SELECT * from ausleihe WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return new Ausleihe(Auto::getById($rows[0]['auto_id']), Mitarbeiter::getById($rows[0]['mitarbeiter_id']), $rows[0]['von'], $rows[0]['bis'], $rows[0]['id']);
    }

    public static function update($obj) {
        $pdo = DbConnect::connect();
        $sql = "UPDATE ausleihe SET auto_id =:auto_id, mitarbeiter_id =:mitarbeiter_id, von =:von, bis =:bis  WHERE id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':auto_id' => $obj->getAuto()->getId(), ':mitarbeiter_id' => $obj->getMitarbeiter()->getId(), ':von' => $obj->getVon(), ':bis' => $obj->getBis(), ':id' => $obj->getId()]);
    }

    public static function insert($id) {
        $pdo = DbConnect::connect();
        $sql = "INSERT INTO ausleihe(auto_id,mitarbeiter_id,von,bis) VALUES (:auto_id,:mitarbeiter_id,:von,:bis)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':auto_id' => $id->getAuto()->getId(), ':mitarbeiter_id' => $id->getMitarbeiter()->getId(), ':von' => $id->getVon(), ':bis' => $id->getBis()]);
    }

    public static function delete($id) {
        $pdo = DbConnect::connect();
        $sql = "delete from ausleihe WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

}
?>

