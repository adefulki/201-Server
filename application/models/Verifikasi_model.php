<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 5/20/2017
 * Time: 12:43 PM
 */
class Verifikasi_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function selectAllProdukByKataKunci($kataKunci){
        return $this->db->query("SELECT * FROM PRODUK WHERE PRODUK.namaProduk = '$kataKunci'")->result_array();
    }

    function isValidVerifikasi($idPedagang, $idPembeli, $kodeAkses){
        if($this->db->query("SELECT * FROM `VERIFIKASI` WHERE `kodeAkses` = '$kodeAkses' AND (`idPedagang` = '$idPedagang' OR `idPembeli` = '$idPembeli')")->num_rows() > 0)
            return true;
        else return false;
    }
}