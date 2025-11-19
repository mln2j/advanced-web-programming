<?php
interface iRadovi {
    public function create($data);
    public function read();
    public function save();
}
class DiplomskiRadovi implements iRadovi {
    private $naziv_rada = NULL;
    private $tekst_rada = NULL;
    private $link_rada = NULL;
    private $oib_tvrtke = NULL;

    function __construct($data = []) {
        if (!empty($data)) {
            $this->create($data);
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

    public function read() {
        return [
            'naziv_rada' => $this->naziv_rada,
            'tekst_rada' => $this->tekst_rada,
            'link_rada' => $this->link_rada,
            'oib_tvrtke' => $this->oib_tvrtke,
        ];
    }

    public function save() {
        echo "Spremanje diplomskog rada: " . $this->naziv_rada . " za tvrtku s OIB-om " . $this->oib_tvrtke . "<br>";
    }

}

?>


