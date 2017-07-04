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
        $this->load->database();
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
        $userId=$obj['userId'];

        foreach ($this->notifikasiModel->selectNotifikasiPembeli($idPembeli) as $item){
            $jarakHaversine = $this->checkHaversineFormula($latPembeli,$lngPembeli,$item['latDagangan'],$item['lngDagangan']);
            $statusInRange = $this->checkDistance($jarakHaversine, $item['jarakNotifikasi']);
            if ($statusInRange == true){
                $text='belummmm';
                $this->obrolanModel->insertObrolan($item['latDagangan'],$idPembeli,$text,$idPembeli);
                $this->sendNotifikasi($userId, $text);
            }
        }
    }

    function sendNotifikasi($userId,$header,$text){
            $content = array(
                "en" => $text
            );

            $headings = array(
                "en" => $header
            );

            $fields = array(
                'app_id' => "8b0d0429-41a3-40fe-836d-86658cce9744",
                'include_player_ids' => array($userId),
                'data' => array("foo" => "bar"),
                'contents' => $content,
                'headings' => $headings
            );

            $fields = json_encode($fields);
            print("\nJSON sent:\n");
            print($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                'Authorization: Basic ZTdhZmUwYWMtZWU0MC00YWI3LTk4ODMtYWJjN2M1NTE4YjYz'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
    }


}