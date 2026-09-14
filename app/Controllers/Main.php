<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Bundesland;
use App\Models\Data;
use App\Models\Station;

class Main extends BaseController
{
    protected $bundesland;
    protected $data;
    protected $station;

    public function __construct() //konstruktor
    {
        $this->bundesland = new Bundesland();
        $this->station = new Station();
        $this->data = new Data();
    }
    public function index()
    {
        $index = $this->bundesland->findAll();
        $data["bundesland"] = $index; 
        echo view("index", $data); ;
    }

    public function zeme($id){
       $zeme = $this->bundesland->find($id);
       $stanice = $this->station->where('bundesland', $id)->findAll();
       $data["stanice"] = $stanice; //vypíše informace na stanice
       $data["bundesland"] = $zeme; //název země
       echo view("zeme", $data);
    } 

    public function data($id){
        
       $zeme = $this->station->find($id);
        $pocasi_data = $this->data->where("Stations_ID", $id)->findAll();
        $data["zeme"] = $zeme;
        $data["udaje"] = $pocasi_data;
        echo view("data", $data);
    }

    public function info($id){
        $zeme = $this->bundesland->find($id);
        $data["zeme"] = $zeme;
        echo view("info", $data);
    }

    public function prehled(){
        $info = $this->bundesland->join("station", "bundesland.id = station.bundesland", "inner")->orderBy("place")->paginate(25);
        $pager = $this->bundesland->pager;
        $data["pager"] = $pager;
        $data["prehled"] = $info;
        echo view("prehled", $data);

    }

    public function smazatForm($id){
        $stanice = $this->station->find($id);
        if ($stanice === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // měsíce, pro které má stanice ještě nesmazaná data
        $mesice = $this->data
            ->select("YEAR(date) AS rok, MONTH(date) AS mesic, COUNT(*) AS pocet")
            ->where("Stations_ID", $id)
            ->groupBy("rok, mesic")
            ->orderBy("rok, mesic")
            ->findAll();

        $data["stanice"] = $stanice;
        $data["mesice"] = $mesice;
        echo view("smazat", $data);
    }

    public function smazat($id){
        $stanice = $this->station->find($id);
        if ($stanice === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $obdobi = (string) $this->request->getPost("obdobi"); // formát RRRR-MM
        if (!preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', $obdobi)) {
            return redirect()->to("smazat/".$id)->with("chyba", "Neplatný měsíc.");
        }

        $od = $obdobi."-01";
        $do = date("Y-m-d", strtotime($od." +1 month"));

        // soft delete – nastaví deleted_at, záznamy v tabulce zůstanou
        $this->data
            ->where("Stations_ID", $id)
            ->where("date >=", $od)
            ->where("date <", $do)
            ->delete();
        $pocet = $this->data->db->affectedRows();

        return redirect()->to("smazat/".$id)
            ->with("zprava", "Smazáno záznamů: ".$pocet." (".date("n/Y", strtotime($od)).").");
    }
}

