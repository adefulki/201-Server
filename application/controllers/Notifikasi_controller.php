<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:12 PM
 */
class Notifikasi_controller extends CI_Controller
{
    private $notifikasiModel;
    private $obrolanModel;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Notifikasi_model','',True);
        $this->notifikasiModel = new Notifikasi_model();
        $this->load->model('Obrolan_model','',True);
        $this->obrolanModel = new Obrolan_model();
    }

    function changeStatusNotifikasi(){
        $obj=json_decode(file_get_contents('php://input'), true);
        $idNotifikasi=$obj['idNotifikasi'];
        $jarakNotifikasi=$obj['jarakNotifikasi'];
        $statusNotifikasi=$obj['statusNotifikasi'];

        $this->notifikasiModel->updateNotifikasi($idNotifikasi, $jarakNotifikasi, $statusNotifikasi);
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

    function checkDistance($jarakHaversine, $jarakNotifikasi){
        if($jarakHaversine <= $jarakNotifikasi)
            return true;
        else return false;
    }

    function checkNotifikasi(){
        $obj=json_decode(file_get_contents('php://input'), true);
        $idPembeli=$obj['idPembeli'];
        $latPembeli=$obj['latPembeli'];
        $lngPembeli=$obj['lngPembeli'];

        foreach ($this->notifikasiModel->selectNotifikasiPembeli($idPembeli) as $item){
            $jarakHaversine = $this->checkHaversineFormula($latPembeli,$lngPembeli,$item['latDagangan'],$item['lngDagangan']);
            $statusInRange = $this->checkDistance($jarakHaversine, $item['jarakNotifikasi']);
            if ($statusInRange == true){
                $text=$this->createText()
                $this->obrolanModel->insertObrolan($item['latDagangan'],$idPembeli,$text,$idPembeli);
                $this->sendNotifikasi()
            }
        }
    }


}