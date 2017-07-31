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
    private $notifikasiModel;
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Pelanggan_model","",true);
        $this->pelangganModel = new Pelanggan_model();
        $this->load->model("Notifikasi_model","",true);
        $this->notifikasiModel = new Notifikasi_model();
        $this->load->database();
    }

    function addPelanggan(){
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPembeli=$obj['idPembeli'];
        $idDagangan=$obj['idDagangan'];
        $dtime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $waktuBerlangganan = $dtime->format("Y-m-d H:i:s");

        $this->pelangganModel->addPelanggan($idPembeli,$idDagangan,$waktuBerlangganan);
        $this->notifikasiModel->addNotifikasi($idPembeli,$idDagangan);
    }

    function removePelanggan(){
        $obj= json_decode(file_get_contents('php://input'),true);

        $idPembeli=$obj['idPembeli'];
        $idDagangan=$obj['idDagangan'];

        $this->pelangganModel->deletePelanggan($idPembeli,$idDagangan);
        $this->notifikasiModel->deleteNotifikasi($idPembeli,$idDagangan);
    }
}