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
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Pedagang_model','',True);
        $this->pedagangModel = new Pedagang_model();
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

        $this->PedagangModel->updateEmailPedagang($idPedagang,$emailPedagang);
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

        $this->PedagangModel->updatePasswordPedagang($idPedagang,$passwordPedagang);
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

        $this->PedagangModel->updateAlamatPedagang($idPedagang,$alamatPedagang);
    }

    function editNoPonselPedagang()
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
        $noPonselPedagang=$obj['noPonselPedagang'];

        $this->PedagangModel->updateNoPonselPedagang($idPedagang,$noPonselPedagang);
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

        $this->pedagangModel->insertPedagang($noPonselPedagang, $passwordPedagang);
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