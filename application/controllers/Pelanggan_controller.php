<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/21/2017
 * Time: 4:40 AM
 */
class Pelanggan_controller extends CI_Controller
{
    private $pelangganModel;
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Pelanggan_model","",true);
        $this->pelangganModel = new Pelanggan_model();
        $this->load->database();
    }
}