<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 7/8/2017
 * Time: 1:04 AM
 */
class Autentikasi_controller extends CI_Controller
{
    private $pedagangModel;
    private $pembeliModel;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Pedagang_model','',True);
        $this->pedagangModel = new Pedagang_model();
        $this->load->model('Pembeli_model','',True);
        $this->pembeliModel = new Pembeli_model();
    }

    function login(){
        $statusValid = false;
        $role = null;

        $obj=json_decode(file_get_contents('php://input'), true);
        $noPonsel=$obj['noPonsel'];
        $password=$obj['password'];

        if($this->pedagangModel->isValidAccount($noPonsel,$password) == true) {
            $statusValid = true;
            $role = 1;
        }elseif($this->pembeliModel->isValidAccount($noPonsel,$password) == true){
            $statusValid = true;
            $role = 2;
        }

        $arr = array("statusValid" => $statusValid,
                    "role" => $role);

        $arr2 = array("login" => $arr);
        header('Content-Type: application/json');
        echo json_encode($arr2);
    }

}