<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:11 PM
 */
class Produk_controller extends CI_Controller
{
    private $produkModel;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model','',True);
        $this->produkModel = new Produk_model();
    }

    function editProdukDagangan(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $idProduk=$obj['idProduk'];
        $namaProduk=$obj['namaProduk'];
        $deskripsiProduk=$obj['deskripsiProduk'];
        $fotoProduk=$obj['fotoProduk'];
        $hargaProduk=$obj['hargaProduk'];
        $satuanProduk=$obj['satuanProduk'];

        $this->produkModel->updateProduk($idProduk, $namaProduk, $deskripsiProduk, $hargaProduk, $satuanProduk, $fotoProduk);
    }

    function addProduk(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $namaProduk=$obj['namaProduk'];
        $deskripsiProduk=$obj['deskripsiProduk'];
        $fotoProduk=$obj['fotoProduk'];
        $hargaProduk=$obj['hargaProduk'];
        $satuanProduk=$obj['satuanProduk'];


    }

    function getProduk(){
        $obj= json_decode(file_get_contents('php://input'),true);
        $idDagangan=$obj['idDagangan'];
        $arr = array();
        $i=0;
        foreach ($this->produkModel->selectProdukDagangan($idDagangan) as $item){
            $arr[i]=array('idProduk'=>$item['idProduk'],
                            'namaProduk'=>$item['namaProduk'],
                            'deskripsiProduk'=>$item['deskripsiProduk'],
                            'fotoProduk'=>$item['fotoProduk']);
            $i++;
        }

        header('Content-Type: application/json');
        echo json_encode($arr);
    }
}