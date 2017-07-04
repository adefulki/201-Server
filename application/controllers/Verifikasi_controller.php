<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/28/2017
 * Time: 6:49 PM
 */
class Verifikasi_controller extends CI_Controller
{
    private $verifikasiModel;
    private $pembeliModel;
    private $pedagangModel;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Verifikasi_model','',True);
        $this->verifikasiModel = new Verifikasi_model();
        $this->load->model('Pembeli_model','',True);
        $this->pembeliModel = new Pembeli_model();
        $this->load->model('Pedagang_model','',True);
        $this->pedagangModel = new Pedagang_model();
    }

    function createKodeAkses(){
        return substr(md5(microtime()),rand(0,26),4);
    }

    function createWaktuKadaluarsa(){
        $dtime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $dtime->modify("+5 hour");
        return $dtime->format("Y-m-d H:i:s");
    }

    function checkKodeAksesPedagang(){
        $obj=json_decode(file_get_contents('php://input'), true);
        $idPedagang= $obj['idPedagang'];
        $kodeAkses= $obj['kodeAkses'];

        $statusValid = $this->verifikasiModel->isValidVerifikasi($idPedagang,"",$kodeAkses);

        if ($statusValid == true){
            $this->pedagangModel->updateStatusVerifikasiPedagang($idPedagang);
            $this->verifikasiModel->deleteVerifikasi(null,$idPedagang);
        }

        $arr=array('statusValid'=>$statusValid);

        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    function checkKodeAksesPembeli(){
        $obj=json_decode(file_get_contents('php://input'), true);
        $idPembeli= $obj['idPembeli'];
        $kodeAkses= $obj['kodeAkses'];

        $statusValid = $this->verifikasiModel->isValidVerifikasi("",$idPembeli,$kodeAkses);

        if ($statusValid == true){
            $this->pembeliModel->updateStatusVerifikasiPembeli($idPembeli);
            $this->verifikasiModel->deleteVerifikasi($idPembeli,null);
        }
        $arr=array('statusValid'=>$statusValid);

        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    function sendVerifikasi($noPonsel, $kodeAkses, $waktuKadaluarsa){
        $userkey = "adefulki"; //userkey lihat di zenziva
        $passkey = "Kam1selalu1"; // set passkey di zenziva
        $message = "Kode Akses anda adalah ".$kodeAkses." . Berlaku hingga ".$waktuKadaluarsa;
        $url = "https://reguler.zenziva.net/apps/smsapi.php";
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        url_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$noPonsel.'&pesan='.urlencode($message));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $results = curl_exec($curlHandle);
        curl_close($curlHandle);

        $XMLdata = new SimpleXMLElement($results);
        $status = $XMLdata->message[0]->text;
        echo $status;
    }

    function checkWaktuKadaluarsa(){
        foreach ($this->verifikasiModel->selectAllVerifikasi() as $item){
            $dtime = new DateTime($item['waktuKadaluarsa']);
            $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
            if ($dtime->getTimestamp() < $now->getTimestamp()){
                $this->verifikasiModel->deleteVerifikasi($item['idPembeli'],$item['idPedagang']);
            }
        }
    }
}