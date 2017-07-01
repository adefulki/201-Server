<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:11 PM
 */
class Pembeli_controller extends CI_Controller
{
    private $pembeliModel;
    private $verifikasiModel;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Pembeli_model','',True);
        $this->pembeliModel = new Pembeli_model();
        $this->load->model('Verifikasi_model','',True);
        $this->verifikasiModel = new Verifikasi_model();
    }

    // edit no 1. Nama pembeli

    function editNamaPembeli()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPembeli=$obj['idPembeli'];
        $namaPembeli=$obj['namaPembeli'];

        $this->pembeliModel->updateNamaPembeli($idPembeli,$namaPembeli);
    }

    function editEmailPembeli()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPembeli=$obj['idPembeli'];
        $emailPembeli=$obj['emailPembeli'];

        $this->pembeliModel->updateEmailPembeli($idPembeli,$emailPembeli);
    }

    function editPasswordPembeli()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPembeli=$obj['idPembeli'];
        $passwordPembeli=$obj['passwordPembeli'];

        $this->pembeliModel->updatePasswordPembeli($idPembeli,$passwordPembeli);
    }

    function editAlamatPembeli()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPembeli=$obj['idPembeli'];
        $alamatPembeli=$obj['alamatPembeli'];

        $this->pembeliModel->updateAlamatPembeli($idPembeli,$alamatPembeli);
    }

    function editNoPonselPembeli()
    {
        $arr = (Object) array();
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPembeli=$obj['idPembeli'];
        $noPonselPembeli=$obj['noPonselPembeli'];

        $this->pembeliModel->updateNoPonselPembeli($idPembeli,$noPonselPembeli);
    }

    function editFotoPembeli()
    {
        $arr = (Object) array();
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPembeli=$obj['idPembeli'];
        $fotoPembeli=$obj['fotoPembeli'];

        $this->pembeliModel->updateFotoPembeli($idPembeli,$fotoPembeli);
    }

    function addPembeli(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $noPonselPembeli=$obj['noPonselPembeli'];
        $passwordPembeli=$obj['passwordPembeli'];

        $this->pembeliModel->insertPembeli($noPonselPembeli, $passwordPembeli);
    }

    function editDetailPembeli(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $idPembeli=$obj['idPembeli'];
        $namaPembeli=$obj['namaPembeli'];
        $alamatPembeli=$obj['alamatPembeli'];
        $fotoPembeli=$obj['fotoPembeli'];

        $this->pembeliModel->updateDetailPembeli($idPembeli, $namaPembeli, $alamatPembeli, $fotoPembeli);
    }
}