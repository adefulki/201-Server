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
        $this->load->model('Notifikasi_model','',True);
        $this->load->model('Obrolan_model','',True);
        $this->load->model('Pedagang_model','',True);
        $this->load->model('Pelanggan_model','',True);
        $this->load->model('Pembeli_model','',True);
        $this->load->model('Pengunjung_model','',True);
        $this->load->model('Penilaian_model','',True);
        $this->load->model('Produk_model','',True);
        $this->load->model('Verifikasi_model','',True);
        $this->load->database();
    }

    //mengirim informasi seluruh dagangan untuk ditampilkan pada map
    //outputan berupa json dengan struktur
    /*
        [{"id_dagangan":id dari dagangan,"nama_dagangan":nama dagangan,
        "foto_dagangan":string address foto,"lat_dagangan":latitude dagangan,
        "lng_dagangan":longitude dagangan,"mean_penilaian_dagangan":rata-rata penilaian,
        "count_penilaian_dagangan":menghitung jumlah penilaian,
        "status_recommendation":status cocok untuk direkomendasikan}]
    */
    //terakhir update: 06/05/2017(Ade)
    function display_dagangan_location(){
        $i=0;
        $arr=array();
        foreach($this->Dagangan_model->get_all_dagangan() as $item) {
            $arr[$i] = array(
                'id_dagangan' => $item['ID_DAGANGAN'],
                'nama_dagangan' => $item['NAMA_DAGANGAN'],
                'foto_dagangan' => $item['FOTO_DAGANGAN'],
                'lat_dagangan' => $item['LAT_DAGANGAN'],
                'lng_dagangan' => $item['LNG_DAGANGAN'],
                'mean_penilaian_dagangan' => $this->mean_all_penilaian_dagangan($item['ID_DAGANGAN']),
                'count_penilaian_dagangan' => $this->count_all_penilaian_dagangan($item['ID_DAGANGAN']),
                'status_recommendation' => $this->check_recommendation($item['ID_DAGANGAN']),
                'status_berjualan' => filter_var($item['STATUS_BERJUALAN'], FILTER_VALIDATE_BOOLEAN),
                'tipe_dagangan' => filter_var($item['TIPE_DAGANGAN'], FILTER_VALIDATE_BOOLEAN)
            );
            $i++;
        }
        $arr2 = array('result_dagangan_location'=>$arr);

        header('Content-Type: application/json');
        echo json_encode($arr2);
    }

    //menghitung jumlah penilaian dari pembeli
    //inputan berupa id dagangan
    //outputan berupa jumlah penilaian
    //terakhir update: 06/05/2017(Ade)
    function count_all_penilaian_dagangan($id_dagangan){
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
    function mean_all_penilaian_dagangan($id_dagangan){
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

    //pencarian dagangan
    //inputan berupa json dengan struktur
    /*
        {"input":sesuatu yang diinputkan untuk mencari pedagang,
        "lat":latitude pembeli,"lng":longitude pembeli}
    */
    //outputan berupa json informasi dagangan
    /*
        [{"id_dagangan":id dari dagangan,"nama_dagangan":nama dagangan,
        "foto_dagangan":string address foto,"distance":jarak antara pedagang dan pembeli}]
    */
    //terakhir update: 12/05/2017(Ade)
    function search_dagangan(){
        $i=0;
        $obj=json_decode(file_get_contents('php://input'), true);
        $input=$obj["input"];
        $lat=$obj["lat"];
        $lng=$obj["lng"];
        $arr=array();
        foreach($this->Dagangan_model->get_dagangan_by_input($input) as $item){

            $arr[$i]=array(
                'id' => $item['ID_DAGANGAN'],
                'nama' => $item['NAMA_DAGANGAN'],
                'foto' => $item['FOTO_DAGANGAN'],
                'jarak' => $this->haversine_formula($lat,$lng,$item['LAT_DAGANGAN'],$item['LNG_DAGANGAN'])
            );
            $i++;
        }
        foreach($this->Produk_model->get_produk_by_input($input) as $item){
            foreach($this->Dagangan_model->get_dagangan_by_id_dagangan($item['ID_DAGANGAN']) as $item2) {
                $lat_dagangan = $item2['LAT_DAGANGAN'];
                $lng_dagangan = $item2['LNG_DAGANGAN'];
            }
            $arr[$i]=array(
                'id' => $item['ID_DAGANGAN'],
                'nama' => $item['NAMA_PRODUK'],
                'foto' => $item['FOTO_PRODUK'],
                'jarak' => $this->haversine_formula($lat,$lng,$lat_dagangan,$lng_dagangan)
            );
            $i++;
        }
        foreach($this->Pedagang_model->get_pedagang_by_input($input) as $item){
            foreach($this->Dagangan_model->get_dagangan_by_id_dagangan($item['ID_DAGANGAN']) as $item2) {
                $lat_dagangan = $item2['LAT_DAGANGAN'];
                $lng_dagangan = $item2['LNG_DAGANGAN'];
            }
            $arr[$i]=array(
                'id' => $item['ID_DAGANGAN'],
                'nama' => $item['NAMA_PEDAGANG'],
                'foto' => $item['FOTO_PEDAGANG'],
                'jarak' => $this->haversine_formula($lat,$lng,$lat_dagangan,$lng_dagangan)
            );
            $i++;
        }
        $arr_sort = $this->array_msort($arr, array('jarak'=>SORT_ASC));
        $arr2 = array('result_search' =>   $arr);

        header('Content-Type: application/json');
        echo json_encode($arr2);
    }

    //formula haversine untuk mengetahui jarak
    //inputan berupa latitude dan longitude awal dan tujuan
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

    function array_msort($array, $cols){
        $colarr = array(); //initial array
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\''.$col.'\'],'.$order.',';
        }
        $eval = substr($eval,0,-1).');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k,1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;

    }

    //menampilkan keseluruhan detail dagangan
    //inputan berupa json dengan struktur
    /*
        {"id_dagangan":id dari pedagang, "id_pembeli":id dari pembeli}
    */
    //outputan berupa json informasi dagangan
    /*
        {"id_dagangan": id dari dagangan, "nama_dagangan": nama dari dagangan,
         "foto_dagangan": string address foto dagangan, "lat_dagangan":latitude dagangan,
         "lng_dagangan":longitude dagangan, "tipe_dagangan": tipe dagangan berupa boolean
          jika 0 diam atau 1 berkeliling,"status_berjualan": status apakah pedagang sedang
          berjualan atau tidak, "count_pelanggan": menjumlah pelanggan dari dagangan,
         "status_berlangganan": status dari alien ,"nama_pedagang" : nama dari pedagang,
         "email_pedagang": email dari pedagang,"nohp_pedagang": nomor ponsel pedagang,
         "alamat_pedagang": alamat dari pedagang,"foto_pedagang": string address foto pedagang,
         "produk":[{"id_produk": id dari produk,"nama_produk": nama dari produk,
         "deskripsi_produk": deskripsi dari produk, "foto_produk": foto dari produk,
         "harga_produk": harga dari produk, "satuan_produk": satuan dari produk,
         "mean_penilaian_produk": nilai rata-rata penilaian produk,
         "count_penilaian_produk": jumlah penilaian produk}]}
    */
    //terakhir update: 13/05/2017(Ade)
    function display_detail_dagangan(){
        $i=0;
        $obj=json_decode(file_get_contents('php://input'), true);
        $id_dagangan=$obj['id_dagangan'];
        $id_pembeli=$obj['id_pembeli'];
        $arr=array();
        foreach($this->Dagangan_model->get_dagangan_by_id_dagangan($id_dagangan) as $item) {
            $arr_pedagang = $this->Pedagang_model->get_pedagang($id_dagangan);
            $j=0;
            $arr_produk=array();
            foreach($this->Produk_model->get_produk_by_id_dagangan($id_dagangan) as $item2) {
                $arr_produk[$j]=array(
                    'id_produk' => $item2['ID_PRODUK'],
                    'nama_produk' => $item2['NAMA_PRODUK'],
                    'deskripsi_produk' => $item2['DESKRIPSI_PRODUK'],
                    'foto_produk' => $item2['FOTO_PRODUK'],
                    'harga_produk' => $item2['HARGA_PRODUK'],
                    'satuan_produk' => $item2['SATUAN_PRODUK'],
                    'mean_penilaian_produk' => $this->mean_all_penilaian_produk($item2['ID_PRODUK']),
                    'count_penilaian_produk' => $this->count_all_penilaian_produk($item2['ID_PRODUK']),
                );
                $j++;
            }
            $arr=array(
                'id_dagangan' => $item['ID_DAGANGAN'],
                'nama_dagangan' => $item['NAMA_DAGANGAN'],
                'foto_dagangan' => $item['FOTO_DAGANGAN'],
                'lat_dagangan' => $item['LAT_DAGANGAN'],
                'lng_dagangan' => $item['LNG_DAGANGAN'],
                'tipe_dagangan' => $item['TIPE_DAGANGAN'],
                'status_berjualan' => $item['STATUS_BERJUALAN'],
                'count_pelanggan' => $this->count_pelanggan($item['ID_DAGANGAN']),
                'status_berlangganan' => $this->check_berlangganan($id_pembeli, $item['ID_DAGANGAN']),
                'nama_pedagang' => $arr_pedagang['NAMA_PEDAGANG'],
                'email_pedagang' => $arr_pedagang['EMAIL_PEDAGANG'],
                'nohp_pedagang' => $arr_pedagang['NOHP_PEDAGANG'],
                'alamat_pedagang' => $arr_pedagang['ALAMAT_PEDAGANG'],
                'foto_pedagang' => $arr_pedagang['FOTO_PEDAGANG'],
                'produk' => $arr_produk
            );
        }
        $arr2 = array('result_detail_dagangan'=>$arr);

        header('Content-Type: application/json');
        echo json_encode($arr2);
    }

    //merata-ratakan penilaian produk dari pembeli
    //inputan berupa id produk
    //outputan berupa nilai rata-rata per produk
    //terakhir update: 13/05/2017(Ade)
    function mean_all_penilaian_produk($id_produk){
        $sum=0;
        $i=0;
        foreach ($this->Penilaian_model->get_penilaian_by_id_produk($id_produk) as $item) {
            $value = $item['NILAI_PRODUK'];
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

    //menghitung jumlah pelanggan pedagang
    //inputan berupa id dagangan
    //outputan berupa jumlah pelanggan
    //terakhir update: 13/05/2017(Ade)
    function count_pelanggan($id_dagangan){
        $count=$this->Pelanggan_model->get_count_pelanggan($id_dagangan);

        return $count;
    }

    //mengecek berlangganan
    //inputan berupa id pembeli dan id dagangan
    //outputan berupa boolean true atau false
    //terakhir update: 13/05/2017(Ade)
    function check_berlangganan($id_pembeli, $id_dagangan){
        $count=$this->Pelanggan_model->get_count_pelanggan_by_id_pembeli_id_dagangan($id_pembeli, $id_dagangan);

        if($count!=0) return true;
        else return false;
    }

    //mengecek no ponsel
    //inputan berupa no ponsel
    //outputan berupa boolean true atau false
    //terakhir update: 13/05/2017(Ade)
    function check_nohp(){
        $count=0;
        $obj=json_decode(file_get_contents('php://input'), true);
        $no_ponsel=$obj['no_ponsel'];
        $count=$count+$this->Pedagang_model->get_count_nohp_pedagang($no_ponsel);
        $count=$count+$this->Pembeli_model->get_count_nohp_pembeli($no_ponsel);
        if($count!=0) return false;
        return true;
    }

    function create_kode_akses(){
        $obj=json_decode(file_get_contents('php://input'), true);
        $no_ponsel=$obj['no_ponsel'];
        $password=$obj['password'];
        $kode_akses = uniqid();
        $exp_time = date_timestamp_set();
        $role=$obj->{'role'};
        if($role == 0){
            $id_pedagang = $this->create_dagangan($no_ponsel, $password);
            $arr = array(
                'ID_PEDAGANG' => $id_pedagang,
                'KODE_AKSES'=> $kode_akses,
                'WAKTU_KADALUARSA' => $exp_time
            );
            $this->Verifikasi_model->add_verifikasi($arr);
        }else if ($role == 1){
            $id_pembeli = $this->create_pembeli($no_ponsel, $password);
            $arr = array(
                'ID_PEMBELI' => $id_pembeli,
                'KODE_AKSES'=> $kode_akses,
                'WAKTU_KADALUARSA' => $exp_time
            );
            $this->Verifikasi_model->add_verifikasi($arr);
        }

        $this->send_verify_account($no_ponsel, $kode_akses);
    }

    function create_dagangan($nohp_pedagang, $password_pedagang){
        $id_dagangan = uniqid();
        $arr = array(
            'ID_DAGANGAN' => $id_dagangan
        );
        $this->Dagangan_model->add_dagangan($arr);

        $id_pedagang = uniqid();
        $arr2 = array(
            'ID_PEDAGANG' => $id_pedagang,
            'NOHP_PEDAGANG'=> $nohp_pedagang,
            'PASSWORD_PEDAGANG' => $password_pedagang,
            'STATUS_VERIFIKASI_PEDAGANG' => false
        );
        $this->Pedagang_model->add_pedagang($arr2);

        return $id_pedagang;
    }

    function create_pembeli($nohp_pembeli, $password_pembeli){
        $id_pembeli = uniqid();
        $arr = array(
            'ID_PEMBELI' => $id_pembeli,
            'NOHP_PEMBELI'=> $nohp_pembeli,
            'PASSWORD_PEMBELI' => $password_pembeli,
            'STATUS_VERIFIKASI_PEMBELI' => false
        );
        $this->Pembeli_model->add_pembeli($arr);

        return $id_pembeli;
    }

    //mengirim pesan verifikasi akun
    //inputan berupa no ponsel
    //terakhir update: 18/05/2017(Ade)
    function send_verify_account($no_ponsel, $kode_akses){
        // Textlocal account details
        $username = 'ade.fulki@gmail.com';
        $hash = '0aa35bc3e41889c2a762d3e6f9d2e648c99e909ca59522791a4f12a7e6baff47';

        // Message details
        $numbers = array($no_ponsel);
        $sender = urlencode('PKL Tracer');
        $message = rawurlencode('Kode akses Anda : '.$kode_akses);

        $numbers = implode(',', $numbers);

        // Prepare data for POST request
        $data = array('username' => $username, 'hash' => $hash, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

        // Send the POST request with cURL
        $ch = curl_init('http://api.txtlocal.com/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}