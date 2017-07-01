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
    function __construct()
    {
        parent::__construct();
        $this->load->model('Verifikasi_model','',True);
        $this->verifikasiModel = new Verifikasi_model();
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

        $arr=array('statusValid'=>$statusValid);

        header('Content-Type: application/json');
        echo json_encode($arr);
    }
}