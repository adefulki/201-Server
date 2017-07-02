<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Pembeli_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function updateNamaPembeli($idPembeli, $namaPembeli){
        $this->db->query("UPDATE `PEMBELI` SET `namaPembeli`='$namaPembeli' WHERE `idPembeli` = '$idPembeli'");
    }

    function updateEmailPembeli($idPembeli, $emailPembeli){
        $this->db->query("UPDATE `PEMBELI` SET `emailPembeli`='$emailPembeli' WHERE `idPembeli` = '$idPembeli'");
    }

    function updatePasswordPembeli($idPembeli, $passwordPembeli){
        $this->db->query("UPDATE `PEMBELI` SET `passwordPembeli`='$passwordPembeli' WHERE `idPembeli` = '$idPembeli'");
    }

    function updateAlamatPembeli($idPembeli, $alamatPembeli){
        $this->db->query("UPDATE `PEMBELI` SET `alamatPembeli`='$alamatPembeli' WHERE `idPembeli` = '$idPembeli'");
    }

    function updateNoPonselPembeli($idPembeli, $noPonselPembeli){
        $this->db->query("UPDATE `PEMBELI` SET `noPonselPembeli`='$noPonselPembeli' WHERE `idPembeli` = '$idPembeli'");
    }

    function updateFotoPembeli($idPembeli, $fotoPembeli){
        $this->db->query("UPDATE `PEMBELI` SET `fotoPembeli`='$fotoPembeli' WHERE `idPembeli` = '$idPembeli'");
    }

    function updateStatusVerifikasiPembeli($idPembeli){
        $result = $this->db->query("SELECT `statusVerifikasiPembeli` FROM `PEMBELI` WHERE `idPembeli` = $idPembeli")->row_array();
        $statusVerifikasiPembeli = $result['statusVerifikasiPembeli'];
        $statusVerifikasiPembeli = !$statusVerifikasiPembeli;
        $this->db->query("UPDATE `PEMBELI` SET `statusVerifikasiPembeli`='$statusVerifikasiPembeli' WHERE `idPembeli` = '$idPembeli'");
    }

    function insertPembeli($noPonselPembeli, $passwordPembeli){
        $idPembeli = uniqid();
        $this->db->query("INSERT INTO `PEMBELI`(`idPembeli`, `noPonselPembeli`, `passwordPembeli`, `statusVerifikasiPembeli`) VALUES 
                        ('$idPembeli', '$noPonselPembeli', '$passwordPembeli', False)");
    }

    function updateDetailPembeli($idPembeli, $namaPembeli, $alamatPembeli, $fotoPembeli){
        $this->db->query("UPDATE `PEMBELI` SET `namaPembeli`='$namaPembeli', `alamatPembeli`='$alamatPembeli', `fotoPembeli`='$fotoPembeli' WHERE `idPembeli` = '$idPembeli'");
    }

    function isValidPasswordPembeli($idPembeli, $passwordPembeli){
        if($this->db->query("SELECT * FROM `PEMBELI` WHERE `idPembeli` = '$idPembeli' AND `passwordPembeli` = '$passwordPembeli'")->num_rows() > 0)
            return true;
        else return false;
    }

    function selectIdPembeliByNoPonselPembeli($noPonselPembeli){
        return $this->db->query("SELECT PEMBELI.idPembeli FROM PEMBELI WHERE PEMBELI.noPonselPembeli = '$noPonselPembeli'")->row_array();
    }
}
