<?php

/**
 * Description of ProjektMitarbeiter
 *
 * @author Teilnehmer
 */
class ProjektMitarbeiter implements Aenderbar, Zeitmessbar, JsonSerializable {

    private $id;
    private $projekt;
    private $mitarbeiter;
    private $von;
    private $bis;

    public static function getNames() {
        return ['Projekt', 'Mitarbeiter', 'Von Tag', 'Von Uhrzeit', 'Bis Tag', 'Bis Uhrzeit'];
    }

    public function __construct(Projekt $projekt, Mitarbeiter $mitarbeiter, $von, $bis, $id = NULL) {
        $this->projekt = $projekt;
        $this->mitarbeiter = $mitarbeiter;
        $this->von = $von;
        $this->bis = $bis;
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getProjekt() {
        return $this->projekt;
    }

    public function getMitarbeiter() {
        return $this->mitarbeiter;
    }

    public function getVon() {
        return $this->von;
    }

    public function getBis() {
        return $this->bis;
    }

    public function jsonSerialize() {
        return['id' => $this->id,
            'projekt' => $this->projekt,
            'mitarbeiter' => $this->mitarbeiter,
            'von' => $this->von,
            'bis' => $this->bis];
    }

    public static function delete($id) {
        
    }

    public static function getAll() {
        $pdo = DbConnect::connect();
        $sql = "SELECT * from projektmitarbeiter";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $projektmitarbeiter = [];

        foreach ($rows as $row) {
            $projektmitarbeiter[$row['id']] = new ProjektMitarbeiter(Projekt::getById($row['projekt_id']), Mitarbeiter::getById($row['mitarbeiter_id']), $row['von'], $row['bis'], $row['id']);
        }
        return $projektmitarbeiter;
    }

    public static function getById($id) {
        $pdo = DbConnect::connect();
        $sql = "SELECT * from projektmitarbeiter WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return new ProjektMitarbeiter(Projekt::getById($rows[0]['projekt_id']), Mitarbeiter::getById($rows[0]['mitarbeiter_id']), $rows[0]['von'], $rows[0]['bis'], $rows[0]['id']);
    }

    public static function insert($id) {
        
    }

    public static function update($obj) {
//        $pdo = DbConnect::connect();
//        $sql = "UPDATE projektmitarbeiter SET projekt_id =:pid, mitarbeiter_id =:mid, von =:von, bis =:bis WHERE id =:id";
//        $stmt = $pdo->prepare($sql);
//        $stmt->execute([':pid' => Projekt::update($obj),':mid' => Mitarbeiter::update($obj),':von' => ProjektMitarbeiter::update($obj),':bis' => ProjektMitarbeiter::update($obj),':id' => ProjektMitarbeiter::getById($id)]);
//        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        return new ProjektMitarbeiter(Projekt::getById($rows[0]['projekt_id']), Mitarbeiter::getById($rows[0]['mitarbeiter_id']), $rows[0]['von'], $rows[0]['bis'], $rows[0]['id']);
    }

    public function getDauer() {
        
    }

}
