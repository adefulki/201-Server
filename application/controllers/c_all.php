<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 30/04/2017
 * Time: 6:51
 */
class c_all extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Dagangan_model','',True);
        $this->load->model('Laporan_model','',True);
        $this->load->model('Notifikasi_model','',True);
        $this->load->model('Obrolan_model','',True);
        $this->load->model('Pedagang_model','',True);
        $this->load->model('Pelanggan_model','',True);
        $this->load->model('Pembeli_model','',True);
        $this->load->model('Pengunjung_model','',True);
        $this->load->model('Penilaian_model','',True);
        $this->load->model('Produk_model','',True);
        $this->load->model('Tag_model','',True);
        $this->load->database();
    }

    function test(){
        $this->display_dagangan_location();
    }

    function display_dagangan_location(){
        $i=0;
        foreach($this->Dagangan_model->get_all_dagangan() as $item) {
            $arr[$i] = array(
                'id_dagangan' => $item['ID_DAGANGAN'],
                'nama_dagangan' => $item['NAMA_DAGANGAN'],
                'foto_dagangan' => $item['FOTO_DAGANGAN'],
                'lat_dagangan' => $item['LAT_DAGANGAN'],
                'lng_dagangan' => $item['LNG_DAGANGAN'],
                'mean_penilaian_dagangan' => $this->mean_all_penilaian($item['ID_DAGANGAN']),
                'count_penilaian_dagangan' => $this->count_all_penilaian($item['ID_DAGANGAN']),
                'status_recommendation' => $this->check_recommendation($item['ID_DAGANGAN']));
            $i++;
        }
        return json_encode($arr);
    }

    function count_all_penilaian($id_dagangan){
        $sum=0;
        foreach ($this->Produk_model->get_produk_by_id_dagangan($id_dagangan) as $item) {
            $sum=$sum+$this->Penilaian_model->get_count_penilaian($item['ID_PRODUK']);
        }
        return $sum;
    }

    function mean_all_penilaian($id_dagangan){
        $sum=0;
        $i=0;
        foreach ($this->Produk_model->get_produk_by_id_dagangan($id_dagangan) as $item) {
            foreach ($this->Penilaian_model->get_penilaian_by_id_produk($item['ID_PRODUK']) as $item2) {
                $value=$item2['NILAI_PRODUK'];
                $sum = $sum + $value;
                $i++;
            }
        }
        if ($i != 0) $mean=$sum/$i;
            else $mean=0;

        return $mean;
    }

    function check_recommendation($id_dagangan){
        $responden=0;
        $star5=0;
        $star4=0;
        $star3=0;
        $star2=0;
        $star1=0;
        foreach ($this->Produk_model->get_produk_by_id_dagangan($id_dagangan) as $item) {
            foreach ($this->Penilaian_model->get_penilaian_by_id_produk($item['ID_PRODUK']) as $item2){
                $value = $item2['NILAI_PRODUK'];
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
        }
        $sum = ($star5*5)+($star4*4)+($star3*3)+($star2*2)+($star1);
        if($responden!=0 ){
            $percent = $sum/($responden*5)*100;
            if($percent>=80)
                return true;
            else
                return false;
        }else return false;
    }
}