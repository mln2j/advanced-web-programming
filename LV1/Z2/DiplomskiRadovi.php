<?php

interface iRadovi {
    public function create($data);
    public static function read();
    public function save();
}

class DiplomskiRadovi implements iRadovi {
    private $naziv_rada = NULL;
    private $tekst_rada = NULL;
    private $link_rada = NULL;
    private $oib_tvrtke = NULL;

    private static $pdo = null;

    function __construct($data = []) {
        if (!empty($data)) {
            $this->create($data);
        }
        self::initDb();
    }

    private static function initDb() {
        if (self::$pdo === null) {
            $host = 'localhost';
            $db   = 'radovi';
            $user = 'root';
            $pass = '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
    }

    public function create($data) {
        if (isset($data['naziv_rada'])) {
            $this->naziv_rada = $data['naziv_rada'];
        }
        if (isset($data['tekst_rada'])) {
            $this->tekst_rada = $data['tekst_rada'];
        }
        if (isset($data['link_rada'])) {
            $this->link_rada = $data['link_rada'];
        }
        if (isset($data['oib_tvrtke'])) {
            $this->oib_tvrtke = $data['oib_tvrtke'];
        }
    }

    public function save() {
        $sql = "INSERT INTO diplomski_radovi (naziv_rada, tekst_rada, link_rada, oib_tvrtke)
                VALUES (:naziv_rada, :tekst_rada, :link_rada, :oib_tvrtke)";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([
            ':naziv_rada' => $this->naziv_rada,
            ':tekst_rada' => $this->tekst_rada,
            ':link_rada' => $this->link_rada,
            ':oib_tvrtke' => $this->oib_tvrtke
        ]);
    }

    public static function read() {
        self::initDb();
        $sql = "SELECT * FROM diplomski_radovi";
        $stmt = self::$pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
