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

    //mengirim informasi seluruh dagangan untuk ditampilkan pada map
    //outputan berupa json dengan struktur
    /*
        [{"id_dagangan":id dari dagangan,"nama_dagangan":nama dagangan,
        "foto_dagangan":string address foto,"lat_dagangan":latitude dagangan,
        "lng_dagangan":longtitude dagangan,"mean_penilaian_dagangan":rata-rata penilaian,
        "count_penilaian_dagangan":menghitung jumlah penilaian,
        "status_recommendation":status cocok untuk direkomendasikan}]
    */
    //terakhir update: 06/05/2017(Ade)
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
                'status_recommendation' => $this->check_recommendation($item['ID_DAGANGAN'])
            );
            $i++;
        }
        return json_encode($arr);
    }

    //menghitung jumlah penilaian dari pembeli
    //inputan berupa id dagangan
    //outputan berupa jumlah penilaian
    //terakhir update: 06/05/2017(Ade)
    function count_all_penilaian($id_dagangan){
        $sum=0;
        foreach ($this->Produk_model->get_produk_by_id_dagangan($id_dagangan) as $item) {
            $sum=$sum+$this->Penilaian_model->get_count_penilaian($item['ID_PRODUK']);
        }
        return $sum;
    }

    //merata-ratakan semua penilaian dari pembeli
    //inputan berupa id dagangan
    //outputan berupa nilai rata-rata
    //terakhir update: 06/05/2017(Ade)
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

    //mengecek apakah pedagang bersangkutan cocok untuk direkomendasikan
    //inputan berupa id dagangan
    //outputan berupa boolean true atau false
    //terakhir update: 06/05/2017(Ade)
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

    //pencarian dagangan berdasarkan kata yang masih diketik
    //inputan berupa json dengan struktur
    /*
        {"input":sesuatu yang diinputkan untuk mencari pedagang,
        "lat":latitude pembeli,"lng":longtitude pembeli}
    */
    //outputan berupa json informasi dagangan
    /*
        [{"id_dagangan":id dari dagangan,"nama_dagangan":nama dagangan,
        "foto_dagangan":string address foto,"distance":jarak antara pedagang dan pembeli}]
    */
    //terakhir update: 06/05/2017(Ade)
    function search_dagangan_from_typing($json){
        $i=0;
        $obj=json_decode($json);
        $input=$obj->{'input'};
        $lat=$obj->{'lat'};
        $lng=$obj->{'lng'};
        foreach($this->Dagangan_model->get_dagangan($input) as $item){
            $arr[$i]=array(
                'id_dagangan' => $item['ID_DAGANGAN'],
                'nama_dagangan' => $item['NAMA_DAGANGAN'],
                'foto_dagangan' => $item['FOTO_DAGANGAN'],
                'distance' => $this->haversine_formula($lat,$lng,$item['LAT_DAGANGAN'],$item['LNG_DAGANGAN'])
            );
            $i++;
        }
        return json_encode($arr);
    }

    //formula haversine untuk mengetahui jarak
    //inputan berupa latitude dan longtitude awal dan tujuan
    //outputan berupa jarak antara dua lokasi
    //terakhir update: 06/05/2017(Ade)
    function haversine_formula($latFrom, $lngFrom, $latTo, $lngTo){
        $earthRadius = 6371000;
        $latFrom = deg2rad($latFrom);
        $lngFrom = deg2rad($lngFrom);
        $latTo = deg2rad($latTo);
        $lngTo = deg2rad($lngTo);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}