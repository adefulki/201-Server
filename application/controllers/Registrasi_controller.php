<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:13 PM
 */
class Registrasi_controller extends CI_Controller
{
    private $verifikasiModel;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Verifikasi_model','',True);
        $this->verifikasiModel = new Verifikasi_model();
    }




}