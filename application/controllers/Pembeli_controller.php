<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:11 PM
 */
include 'Verifikasi_controller.php';
class Pembeli_controller extends CI_Controller
{
    private $pembeliModel;
    private $verifikasiModel;
    private $verifikasiController;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Pembeli_model','',True);
        $this->pembeliModel = new Pembeli_model();
        $this->load->model('Verifikasi_model','',True);
        $this->verifikasiModel = new Verifikasi_model();
        $this->verifikasiController = new Verifikasi_controller();
    }

    function editNamaPembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPembeli=$obj['idPembeli'];
        $namaPembeli=$obj['namaPembeli'];

        $this->pembeliModel->updateNamaPembeli($idPembeli,$namaPembeli);
    }

    function editEmailPembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPembeli=$obj['idPembeli'];
        $emailPembeli=$obj['emailPembeli'];

        $this->pembeliModel->updateEmailPembeli($idPembeli,$emailPembeli);
    }

    function editPasswordPembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPembeli=$obj['idPembeli'];
        $passwordPembeli=$obj['passwordPembeli'];

        $this->pembeliModel->updatePasswordPembeli($idPembeli,$passwordPembeli);
    }

    function editAlamatPembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPembeli=$obj['idPembeli'];
        $alamatPembeli=$obj['alamatPembeli'];

        $this->pembeliModel->updateAlamatPembeli($idPembeli,$alamatPembeli);
    }

    function editNoPonselPembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);
        $idPembeli=$obj['idPembeli'];
        $noPonselPembeli=$obj['noPonselPembeli'];

        $kodeAkses = $this->verifikasiController->createKodeAkses();
        $waktuKadaluarsa = $this->verifikasiController->createWaktuKadaluarsa();

        $this->pembeliModel->updateNoPonselPembeli($idPembeli,$noPonselPembeli);
        $this->pembeliModel->updateStatusVerifikasiPembeli($idPembeli);
        $this->verifikasiModel->insertVerifikasiPembeli($idPembeli,$kodeAkses,$waktuKadaluarsa);

        $this->verifikasiController->sendVerifikasi($noPonselPembeli,$kodeAkses,$waktuKadaluarsa);
    }

    function editFotoPembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPembeli=$obj['idPembeli'];
        $fotoPembeli=$obj['fotoPembeli'];

        $this->pembeliModel->updateFotoPembeli($idPembeli,$fotoPembeli);
    }

    function addPembeli(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $noPonselPembeli=(string)$obj['noPonselPembeli'];
        $passwordPembeli=(string)$obj['passwordPembeli'];

        $kodeAkses = $this->verifikasiController->createKodeAkses();
        $waktuKadaluarsa = $this->verifikasiController->createWaktuKadaluarsa();

        $this->pembeliModel->insertPembeli($noPonselPembeli, $passwordPembeli);
        $pembeli = $this->pembeliModel->selectIdPembeliByNoPonselPembeli($noPonselPembeli);
        $this->verifikasiModel->insertVerifikasiPembeli($pembeli['idPembeli'],$kodeAkses,$waktuKadaluarsa);

        $this->verifikasiController->sendVerifikasi($noPonselPembeli,$kodeAkses,$waktuKadaluarsa);
    }

    function editDetailPembeli(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $idPembeli=$obj['idPembeli'];
        $namaPembeli=$obj['namaPembeli'];
        $alamatPembeli=$obj['alamatPembeli'];
        $fotoPembeli=$obj['fotoPembeli'];

        $this->pembeliModel->updateDetailPembeli($idPembeli, $namaPembeli, $alamatPembeli, $fotoPembeli);
    }

    function checkPasswordPembeli(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $idPembeli=$obj['idPembeli'];
        $passwordPembeli=$obj['passwordPembeli'];
        $statusValidPassword = $this->pembeliModel->isValidPasswordPembeli($idPembeli,$passwordPembeli);

        $arr = array('statusValidPassword'=>$statusValidPassword);

        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    function getIdPembeliByNoPonselPembeli()
    {
        $obj = json_decode(file_get_contents('php://input'), true);
        $noPonselPembeli = $obj['noPonselPembeli'];

        $pembeli = $this->pembeliModel->selectIdPembeliByNoPonselPembeli($noPonselPembeli);
        $idPembeli = $pembeli['idPembeli'];
        $arr=array('idPembeli'=>$idPembeli);

        header('Content-Type: application/json');
        echo json_encode($arr);
    }
}