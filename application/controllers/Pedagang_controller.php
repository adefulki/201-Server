<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:07 PM
 */
include 'Verifikasi_controller.php';
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
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPedagang=$obj['idPedagang'];
        $namaPedagang=$obj['namaPedagang'];

        $this->pedagangModel->updateNamaPedagang($idPedagang,$namaPedagang);
    }

    function editEmailPedagang()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPedagang=$obj['idPedagang'];
        $emailPedagang=$obj['emailPedagang'];

        $this->pedagangModel->updateEmailPedagang($idPedagang,$emailPedagang);
    }

    function editPasswordPedagang()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPedagang=$obj['idPedagang'];
        $passwordPedagang=$obj['passwordPedagang'];

        $this->pedagangModel->updatePasswordPedagang($idPedagang,$passwordPedagang);
    }

    function editAlamatPedagang()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPedagang=$obj['idPedagang'];
        $alamatPedagang=$obj['alamatPedagang'];

        $this->pedagangModel->updateAlamatPedagang($idPedagang,$alamatPedagang);
    }

    function editNoPonselPedagang()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPedagang=$obj['idPedagang'];
        $noPonselPedagang=$obj['noPonselPedagang'];

        $kodeAkses = $this->verifikasiController->createKodeAkses();
        $waktuKadaluarsa = $this->verifikasiController->createWaktuKadaluarsa();

        $this->pedagangModel->updateNoPonselPedagang($idPedagang,$noPonselPedagang);
        $this->pedagangModel->updateStatusVerifikasiPedagang($idPedagang);
        $this->verifikasiModel->insertVerifikasiPedagang($idPedagang,$kodeAkses,$waktuKadaluarsa);

        $this->verifikasiController->sendVerifikasi($noPonselPedagang,$kodeAkses,$waktuKadaluarsa);
    }

    function editUserIdPedagang()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPedagang=$obj['idPedagang'];
        $userIdPedagang=$obj['userIdPedagang'];

        $this->pedagangModel->updateUserIdPedagang($idPedagang,$userIdPedagang);
    }

    function editFotoPedagang()
    {

        $obj= json_decode(file_get_contents('php://input'),true);

        $idPedagang=$obj['idPedagang'];
        $fotoPedagang=$obj['fotoPedagang'];

        $this->pedagangModel->updateFotoPedagang($idPedagang,$fotoPedagang);
    }

    function addPedagang(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $noPonselPedagang= (String) $obj['noPonselPedagang'];
        $passwordPedagang=(String) $obj['passwordPedagang'];

        $kodeAkses = $this->verifikasiController->createKodeAkses();
        $waktuKadaluarsa = $this->verifikasiController->createWaktuKadaluarsa();

        $this->pedagangModel->insertPedagang($noPonselPedagang, $passwordPedagang);
        $pedagang = $this->pedagangModel->selectIdPedagangByNoPonselPedagang($noPonselPedagang);
        $this->verifikasiModel->insertVerifikasiPedagang($pedagang['idPedagang'],$kodeAkses,$waktuKadaluarsa);

        $this->verifikasiController->sendVerifikasi($noPonselPedagang,$kodeAkses,$waktuKadaluarsa);
    }

    function checkPasswordPedagang(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $idPedagang=$obj['idPedagang'];
        $passwordPedagang=$obj['passwordPedagang'];
        $statusValidPassword = $this->pedagangModel->isValidPasswordPedagang($idPedagang,$passwordPedagang);

        $arr = array('statusValidPassword'=>$statusValidPassword);

        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    function getIdPedagangByNoPonselPedagang()
    {
        $obj = json_decode(file_get_contents('php://input'), true);
        $noPonselPedagang = $obj['noPonselPedagang'];

        $pedagang = $this->pedagangModel->selectIdPedagangByNoPonselPedagang($noPonselPedagang);

        $arr = array('idPedagang'=>$pedagang['idPedagang']);

        header('Content-Type: application/json');
        echo json_encode($arr);
    }
}