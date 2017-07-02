<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:07 PM
 */
class Pedagang_controller extends CI_Controller
{
    private $pedagangModel;
    private $verifikasiModel;
    private $verifikasiController;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Pedagang_model','',True);
        $this->pedagangModel = new Pedagang_model();
        $this->load->model('Verifikasi_model','',True);
        $this->verifikasiModel = new Verifikasi_model();
        $this->verifikasiController = new Verifikasi_controller();
    }

    function editNamaPedagang()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPedagang=$obj['idPedagang'];
        $namaPedagang=$obj['namaPedagang'];

        $this->pedagangModel->updateNamaPedagang($idPedagang,$namaPedagang);
    }

    function editEmailPedagang()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPedagang=$obj['idPedagang'];
        $emailPedagang=$obj['emailPedagang'];

        $this->pedagangModel->updateEmailPedagang($idPedagang,$emailPedagang);
    }

    function editPasswordPedagang()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPedagang=$obj['idPedagang'];
        $passwordPedagang=$obj['passwordPedagang'];

        $this->pedagangModel->updatePasswordPedagang($idPedagang,$passwordPedagang);
    }

    function editAlamatPedagang()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPedagang=$obj['idPedagang'];
        $alamatPedagang=$obj['alamatPedagang'];

        $this->pedagangModel->updateAlamatPedagang($idPedagang,$alamatPedagang);
    }

    function editNoPonselPedagang()
    {
        $arr = (Object) array();
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPedagang=$obj['idPedagang'];
        $noPonselPedagang=$obj['noPonselPedagang'];

        $kodeAkses = $this->verifikasiController->createKodeAkses();
        $waktuKadaluarsa = $this->verifikasiController->createWaktuKadaluarsa();

        $this->pedagangModel->updateNoPonselPedagang($idPedagang,$noPonselPedagang);
        $this->pedagangModel->updateStatusVerifikasiPedagang($idPedagang);
        $this->verifikasiModel->insertVerifikasiPedagang($idPedagang,$kodeAkses,$waktuKadaluarsa);

        $this->verifikasiController->sendVerifyAccount()
    }

    function editFotoPedagang()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPedagang=$obj['idPedagang'];
        $fotoPedagang=$obj['fotoPedagang'];

        $this->PedagangModel->updateFotoPedagang($idPedagang,$fotoPedagang);
    }

    function addPedagang(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $noPonselPedagang=$obj['noPonselPedagang'];
        $passwordPedagang=$obj['passwordPedagang'];

        $kodeAkses = $this->verifikasiController->createKodeAkses();
        $waktuKadaluarsa = $this->verifikasiController->createWaktuKadaluarsa();

        $this->pedagangModel->insertPedagang($noPonselPedagang, $passwordPedagang);
        $pedagang = $this->pedagangModel->selectIdPedagangByNoPonselPedagang($noPonselPedagang);
        $this->verifikasiModel->insertVerifikasiPedagang($pedagang['idPedagang'],$kodeAkses,$waktuKadaluarsa);

        $this->verifikasiController->sendVerifikasi
    }

    function checkPasswordPedagang(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $idPedagang=$obj['idPedagang'];
        $passwordPedagang=$obj['passwordPedagang'];
        $statusValidPassword = $this->pedagangModel->isValidPasswordPedagang($idPedagang,$passwordPedagang);

        $arr = array('statusValidPassword'<=$statusValidPassword);

        header('Content-Type: application/json');
        echo json_encode($arr);
    }
}