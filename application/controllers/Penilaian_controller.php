<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:12 PM
 */
class Penilaian_controller extends CI_Controller
{
    private $penilaianModel;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Penilaian_model','',True);
        $this->penilaianModel = new Penilaian_model();
        $this->load->database();
    }

    //merata-ratakan semua penilaian dari pembeli
    //inputan berupa id dagangan
    //outputan berupa nilai rata-rata
    //terakhir update: 06/05/2017(Ade)
    function meanPenilaian($idDagangan){
        $sum=0;
        $i=0;
        foreach ($this->penilaianModel->selectPenilaian($idDagangan) as $item){
                $value=$item['nilaiPenilaian'];
                $sum = $sum + $value;
                $i++;
        }
        if ($i != 0) $mean=$sum/$i;
        else $mean=0;

        return $mean;
    }

    //mengecek apakah dagangan bersangkutan cocok untuk direkomendasikan
    //inputan berupa id dagangan
    //outputan berupa boolean true atau false
    //terakhir update: 06/05/2017(Ade)
    function checkRecommendation($idDagangan){
        $responden=0;
        $star5=0;
        $star4=0;
        $star3=0;
        $star2=0;
        $star1=0;
        foreach ($this->penilaianModel->selectPenilaian($idDagangan) as $item) {
            $value = $item['nilaiPenilaian'];
            if($value==5)
                $star5++;
            elseif($value==4)
                $star4++;
            elseif($value==3)
                $star3++;
            elseif($value==2)
                $star2++;
            else
                $star1++;
            $responden++;
        }
        $sum = ($star5*5)+($star4*4)+($star3*3)+($star2*2)+($star1);

        if($responden!=0){
            $percent = $sum/($responden*5)*100;
            if($percent>=80)
                return true;
            else
                return false;
        }else return false;
    }

    //merata-ratakan penilaian produk dari pembeli
    //inputan berupa id produk
    //outputan berupa nilai rata-rata per produk
    //terakhir update: 13/05/2017(Ade)
    function meanPenilaianProduk($idProduk){
        $sum=0;
        $i=0;
        foreach ($this->penilaianModel->selectPenilaianProduk($idProduk) as $item) {
            $value = $item['nilaiProduk'];
            $sum = $sum + $value;
            $i++;
        }
        if ($i != 0) $mean=$sum/$i;
        else $mean=0;

        return $mean;
    }

    //menghitung jumlah penilaian produk dari pembeli
    //inputan berupa id produk
    //outputan berupa jumlah penilaian per produk
    //terakhir update: 13/05/2017(Ade)
    function count_all_penilaian_produk($id_produk){
        $count=$this->Penilaian_model->get_count_penilaian($id_produk);

        return $count;
    }

    function penilaianProduk(){
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $idPembeli=$obj['idPembeli'];
        $idProduk=$obj['idProduk'];
        $nilaiPenilaian=$obj['nilaiPenilaian'];
        $deskripsiPenilaian=$obj['deskripsiPenilaian'];

        $this->penilaianModel->insertPenilaian($idPembeli, $idProduk, $nilaiPenilaian, $deskripsiPenilaian);
    }
}