<?php

/**
 * Created by PhpStorm.
 * User: Risa Aprilia
 * Date: 5/20/2017
 * Time: 7:53 AM
 */
class c_pembeli extends CI_Controller
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
        $this->load->database();
    }

    /*
     * start of pengaturan akun
     */

    /*
     * Display akun pembeli, menampilkan akun pembeli yang berisikan
     * 1. Nama pembeli
     * 2. Email Pembeli
     * 3. No Hp Pembeli
     * 4. Password Pembeli
     * 5. Alamat Pembeli
     */
    function display_akun_pembeli()
    {
        $obj=json_decode(file_get_contents('php://input'),true);
        //$obj=json_decode($json);
        $id_pembeli=$obj['id_pembeli']; // meminta array
        //$id_pembeli=$obj->{'id_pembeli'};

        $pembeli = $this->Pembeli_model->get_pembeli($id_pembeli);

        $arr = array(
            'nama_pembeli'=> $pembeli['NAMA_PEMBELI'],
            'email_pembeli'=> $pembeli['EMAIL_PEMBELI'],
            'nohp_pembeli' => $pembeli['NOHP_PEMBELI'],
            'password_pembeli'=> $pembeli['PASSWORD_PEMBELI'],
            'alamat_pembeli'=> $pembeli['ALAMAT_PEMBELI'],
        );
        header('Content-Type: application/json');
        echo json_encode($arr);

    }
    // edit no 1. Nama pembeli

    function edit_nama_pembeli()
    {
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'),true);

        /*
         * memisahkan atribut
         */
        $id_pembeli=$obj['id_pembeli'];
        $nama_pembeli=$obj['nama_pembeli'];

        $arr = array(
            'NAMA_PEMBELI'=>$nama_pembeli
        );
        $this->Pembeli_model->update_pembeli($id_pembeli,$arr);
    }
    //edit no 2. Email pembeli
    function edit_email_pembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);
        $id_pembeli=$obj['id_pembeli'];
        $email_pembeli=$obj['email_pembeli'];

        $arr = array(
            'EMAIL_PEMBELI'=>$email_pembeli
        );
        $this->Pembeli_model->update_pembeli($id_pembeli,$arr);
    }
    //edit no 3. No HP pembeli
    function edit_nohp_pembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);
        $id_pembeli=$obj['id_pembeli'];
        $nohp_pembeli=$obj['nohp_pembeli'];

        $arr = array(
            'NOHP_PEMBELI'=>$nohp_pembeli
        );
        $this->Pembeli_model->update_pembeli($id_pembeli,$arr);
    }
    // edit no 4. Password pembeli
    function edit_password_pembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);
        $id_pembeli=$obj['id_pembeli'];
        $password_pembeli=$obj['password_pembeli'];

        $arr = array(
            'PASSWORD_PEMBELI'=>$password_pembeli
        );
        $this->Pembeli_model->update_pembeli($id_pembeli,$arr);
    }
    // edit no 5. Alamat Pembeli
    function edit_alamat_pembeli()
    {
        $obj= json_decode(file_get_contents('php://input'),true);
        $id_pembeli=$obj['id_pembeli'];
        $alamat_pembeli=$obj['alamat_pembeli'];

        $arr = array(
            'ALAMAT_PEMBELI'=>$alamat_pembeli
        );
        $this->Pembeli_model->update_pembeli($id_pembeli,$arr);
    }
    /*
     * End of pengaturan akun
     */

    /*
     * Start of obrolan
     */

    /*
     * Display Obrolan antara pembeli dan pkl, yang ditampilkan
     * 1. Nama Dagangan
     * 2. Foto Dagangan
     * 3. Obrolan ( text Terakhir)
     * 4. Waktu Submit/ waktu update text
     */
    function display_obrolan()
    {
        $obj=json_decode(file_get_contents('php://input'),true);
        $id_pembeli=$obj['id_pembeli'];
        $i=0;
        foreach($this-> Obrolan_model->get_obrolan_by_id_pembeli($id_pembeli) as $item){

            foreach($this->Dagangan_model->get_dagangan_by_id_dagangan($item['ID_DAGANGAN']) as $item2 ){
                $arr[$i]= array(
                    'nama_dagangan' => $item2['NAMA_DAGANGAN'],
                    'foto_dagangan' => $item2['FOTO_DAGANGAN'],
//                    'kategori_dagangan'=> $item2['KATEGORI_DAGANGAN'],
                );
                $arr[$i]=array(
                    'text'=> $item['TEXT'],
                    'waktu_pengiriman' => $item['WAKTU_PENGIRIMAN'],
                );
                $i++;
            }
            header('Content-Type: application/json');
            echo json_encode($arr);
            // nampilin 2 aray bener kaya gini engga? hehe
        }
//        return json_encode($arr);

    }
    /*
     * end of obrolan
     */

    /*
     *Start of Berlangganan
     */

    /*
     * Display halaman berlangganan antara pembeli dan pkl, yang berisikan
     * 1. Nama Dagangan
     * 2. Foto Dagangan
     * 3. Tipe Dagangan ( ini perlu engga ? )
     * 4. Status berjualan ( untuk menandakan aktif atau tidak)
     */
    function display_berlangganan()
    {
        $obj=json_decode(file_get_contents('php://input'),true);
        $id_pembeli=$obj['id_pembeli'];
        $i=0;
        foreach($this-> Pelanggan_model->get_pelanggan_by_pembeli($id_pembeli) as $item){

            foreach($this->Dagangan_model->get_dagangan_by_id_dagangan($item['ID_DAGANGAN']) as $item2 ){
            $arr[$i]= array(
                    'nama_dagangan' => $item2['NAMA_DAGANGAN'],
                    'tipe_dagangan' => $item2['TIPE_DAGANGAN'],
                    'foto_dagangan' => $item2['FOTO_DAGANGAN'],
                    'status_berjualan' => $item2['STATUS_BERJUAAN'],
//                    'kategori_dagangan'=> $item2['KATEGORI_DAGANGAN'],
                );
                $i++;
            }
            header('Content-Type: application/json');
            echo json_encode($arr);
        }
//        return json_encode($arr);

    }
    /*
     * End Of Berlangganan
     */

    /*
     * start of pemberitahuan
     */
    function set_pemberitahuan()
    {
        $obj= json_decode(file_get_contents('php://input'),true);
        $id_pembeli=$obj['id_pembeli'];
        $id_dagangan=$obj['id_dagangan'];
        $id_notifikasi=$id_pembeli . "_" . $id_dagangan; // id notifikasi gabungan dari id pembeli dan id pedagang, sehingga tidak ada duplikat id_notifikasi
        $status_notifikasi=$obj['status_notifikasi'];
        $ulangi_otomatis=$obj['ulangi_otomatis'];
        $jarak=$obj['jarak'];

        $arr= array(
            'ID_NOTIFIKASI'=>$id_notifikasi,
            'ID_PEMBELI'=>$id_pembeli,
            'ID_DAGANGAN'=>$id_dagangan,
            'STATUS_NOTIFIKASI'=>$status_notifikasi,
            'ULANGI_OTOMATIS'=>$ulangi_otomatis,
            'JARAK'=>$jarak,
        );
        $this->Notifikasi_model->add_notifikasi($arr);
    }

    function update_set_pemberitahuan()
    {
        $obj = json_decode(file_get_contents('php://input'),true);
        $id_pembeli =$obj['id_pembeli'];
        $id_dagangan = $obj['id_dagangan'];
        $id_notifikasi=$id_pembeli . "_" . $id_dagangan;
        $status_notifikasi=$obj['status_notifikasi'];

        $arr=array(
            "STATUS_NOTIFIKASI" => $status_notifikasi,
        );
        $this->Notifikasi_model->update_notifikasi($id_notifikasi,$arr);
    }

    /*
     * End of pemberitahuan
     */

    /*
     * Start of menilai dagangan
     */

    function display_penilaian_produk()
    {
        $obj = json_decode(file_get_contents('php://input'),true);
        $id_pembeli = $obj['id_pembeli'];
        $id_produk = $obj['id_produk'];
        $id_penilaian = $id_pembeli . "_" . $id_produk;
        $i=0;
        foreach($this-> Penilaian_model->get_penilaian_by_id_penilaian($id_penilaian) as $item){

            $produk = $this->Produk_model->get_produk_by_id_produk($item['ID_PRODUK']);
                $arr[$i]= array(
                    'nama_produk' => $produk['NAMA_PRODUK'],
                    'foto_produk' => $produk['FOTO_PRODUK'],
                    'nilai_produk' => $item['NILAI_PRODUK'],
                );

                $i++;

            header('Content-Type: application/json');
            echo json_encode($arr);
        }

    }

    function add_penilaian_produk()
    {
        $obj = json_decode(file_get_contents('php://input'),true);
        $id_pembeli = $obj['id_pembeli'];
        $id_produk = $obj['id_produk'];
        $id_penilaian = $id_pembeli . "_" . $id_produk;
        $nilai_produk = $obj['nilai_produk'];
        $deskripsi_penilaian_produk = $obj['deskripsi_penilaian_produk'];

        $arr = array(
            "ID_PEMBELI" => $id_pembeli,
            "ID_PRODUK" => $id_produk,
            "ID_PENILAIAN" => $id_penilaian,
            "NILAI_PRODUK" => $nilai_produk,
            "DESKRIPSI_PENILAIAN_PRODUK" => $deskripsi_penilaian_produk,
        );
        $this->Penilaian_model->add_penilaian($arr);


    }
    /*
     * End Of menilai dagangan
     */

    //yang kurang berdasarkan mockup
    // isi dari chatting obrolan, ngeklik obrolan(mockup 13-18)
    // pindai qr code (mockup 5)

}