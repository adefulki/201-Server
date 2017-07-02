<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:13 PM
 */
class Pencarian_controller extends CI_Controller
{
    private $pedagangModel;
    private $daganganModel;
    private $produkModel;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Pedagang_model','',True);
        $this->pedagangModel = new Pedagang_model();
        $this->load->model('Dagangan_model','',True);
        $this->daganganModel = new Dagangan_model();
        $this->load->model('Produk_model','',True);
        $this->produkModel = new Produk_model();
        $this->load->database();
    }

    //pencarian dagangan
    //inputan berupa json dengan struktur
    /*
        {"input":sesuatu yang diinputkan untuk mencari pedagang,
        "lat":latitude pembeli,"lng":longitude pembeli,"filter1":jika 0 maka pedagang 1 maka dagangan dan 2 maka produk}
    */
    //outputan berupa json informasi dagangan
    /*
        [{"id_dagangan":id dari dagangan,"nama_dagangan":nama dagangan,
        "foto_dagangan":string address foto,"distance":jarak antara pedagang dan pembeli}]
    */
    //terakhir update: 12/05/2017(Ade)
    function searchKataKunci(){
        $i=0;
        $obj=json_decode(file_get_contents('php://input'), true);
        $kataKunci=$obj["kataKunci"];
        $latPembeli= (double) $obj["latPembeli"];
        $lngPembeli= (double) $obj["lngPembeli"];
        $filter = $obj["filter"];
        $arr=array();

        switch ($filter){
            case "0" :{
                foreach ($this->pedagangModel->selectAllPedagangByKataKunci($kataKunci) as $item){
                    $arr[$i]=array(
                        'id' => $item['idPedagang'],
                        'nama' => $item['namaPedagang'],
                        'foto' => $item['fotoPedagang'],
                        'jarak' => $this->haversine_formula($latPembeli,$lngPembeli,$item['latDagangan'],$item['lngDagangan'])
                    );
                    $i++;
                }
                break;
            }
            case "1" : {
                foreach ($this->daganganModel->selectAllDaganganByKataKunci($kataKunci) as $item){
                    $arr[$i]=array(
                        'id' => $item['idDagangan'],
                        'nama' => $item['namaDagangan'],
                        'foto' => $item['fotoDagangan'],
                        'jarak' => $this->haversine_formula($latPembeli,$lngPembeli,$item['latDagangan'],$item['lngDagangan'])
                    );
                    $i++;
                }
                break;
            }
            case "2" : {
                foreach ($this->produkModel->selectAllProdukByKataKunci($kataKunci) as $item){
                    $arr[$i]=array(
                        'id' => $item['idProduk'],
                        'nama' => $item['namaProduk'],
                        'foto' => $item['fotoProduk'],
                        'jarak' => $this->checkHaversineFormula($latPembeli,$lngPembeli,$item['latDagangan'],$item['lngDagangan'])
                    );
                    $i++;
                }
                break;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    //formula haversine untuk mengetahui jarak
    //inputan berupa latitude dan longitude awal dan tujuan
    //outputan berupa jarak antara dua lokasi
    //terakhir update: 06/05/2017(Ade)
    function checkHaversineFormula($latFrom, $lngFrom, $latTo, $lngTo){
        $earthRadius = 6371000;
        $latFrom = deg2rad($latFrom);
        $lngFrom = deg2rad($lngFrom);
        $latTo = deg2rad($latTo);
        $lngTo = deg2rad($lngTo);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    function array_msort($array, $cols){
        $colarr = array(); //initial array
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\''.$col.'\'],'.$order.',';
        }
        $eval = substr($eval,0,-1).');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k,1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;

    }
}