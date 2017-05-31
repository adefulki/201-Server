<?php

/**
 * Created by PhpStorm.
 * User: Syam
 * Date: 29/05/2017
 * Time: 23.27
 */
class C_pedagang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Dagangan_model', '', True);
        $this->load->model('Notifikasi_model', '', True);
        $this->load->model('Obrolan_model', '', True);
        $this->load->model('Pedagang_model', '', True);
        $this->load->model('Pelanggan_model', '', True);
        $this->load->model('Pembeli_model', '', True);
        $this->load->model('Pengunjung_model', '', True);
        $this->load->model('Penilaian_model', '', True);
        $this->load->model('Produk_model', '', True);
        $this->load->database();
    }
    

    /*
  * start of akun pedagang
  */

    /*
     * Display akun pedagang, menampilkan akun pedagang yang berisikan
     * 1. Nama pedagang
     * 2. Nama Dagangan
     * 3. Email pedagang
     * 4. Foto Pedagang
     */
    function display_akun_pedagang()
    {
        $obj=json_decode(file_get_contents('php://input'), true);
        $id_dagangan=$obj['id_dagangan'];
        print($id_dagangan);
        $pedagang = $this->Pedagang_model->get_Pedagang($id_dagangan);
        $arr = array(
            'id_dagangan'=> $pedagang['ID_DAGANGAN'],
            'email_pedagang' => $pedagang['EMAIL_PEDAGANG'],
            'foto_pedagang'=> $pedagang['FOTO_PEDAGANG'],

        );
       header('Content-Type: application/json');
        echo json_encode($arr);
    }
    /*
    * End of pengaturan akun
    */

    /*
 * start of QRCode saya
 */
    /*
     * Display Qrcode saya, menampilkan Qrcode saya yang berisikan
     * 1. informasi dagangan
     * 2. informasi pedagang
     */
    function display_qrcode(){
        $i=0;
        foreach ($this->Pedagang_model->get_all_pedagang() as $item) {
            $arr[$i] = array(
                'id_pedagang'=> $item['ID_DAGANGAN'],
                'nama_pembeli' => $item['NAMA_PEDAGANG'],
                'id_pedagang'=> $item['ID_PEDAGANG'],
                'email_pedagang'=> $item['EMAIL_PEDAGANG'],
                'nohp_pedagang' => $item['NOHP_PEDAGANG'],
                'foto_pedagang' => $item['FOTO_PEDAGANG']);
            $i++;
        }
        print json_encode($arr);
    }
    /*
    * End of pengaturan akun
    */

    /*
* start of pelanggan
*/
    /*
     * Display Qrcode saya, menampilkan Qrcode saya yang berisikan
     * 1. nama pembeli
     * 2. email pembeli
     * 3. foto pembeli
     */

    function display_pelanggan(){
        $i=0;
        foreach ($this->Pelanggan_model->get_all_pelanggan() as $item) {
            $arr[$i] = array(
                'id_pedagang'=> $item['ID_PELANGGAN'],
                'id_pembeli'=> $item['ID_PEMBELI'],
                'foto_pelanggan' => $item['FOTO_PEMBELI']);
            $i++;
        }
        header('Content-Type: application/json');
        echo json_encode($arr);
    }
    /*
    * End of pengaturan akun
    */

    /*
* start of pengaturan akun
*/
    /*
     * Display Qrcode saya, menampilkan Qrcode saya yang berisikan
     * 1. foto pedagang
     * 2. nama pedagang
     * 3. email pedagang
     * 4. no hp Pedagang
     * 5. Password
     * 6. alamat pedagang
     */

    // edit no 1. Foto pedagang
    function edit_foto_pedagang()
    {
        $obj= json_decode(file_get_contents('php://input'), true);
        $id_pedagang=$obj['id_pedagang'];
        $foto_pedagang=$obj['foto_pedagang'];

        $arr = array(
            'FOTO_PEDAGANG'=>$foto_pedagang
        );
        $this->Pedagang_model->update_pedagang($id_pedagang,$arr);
    }

    // edit no 2. Nama pedagang
    function edit_nama_pedagang()
    {
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode(file_get_contents('php://input'), true);

        /*
         * memisahkan atribut
         */
        $id_pedagang=$obj['id_pedagang'];
        $nama_pedagang=$obj['nama_pedagang'];

        $arr = array(
            'NAMA_PEDAGANG'=>$nama_pedagang
        );
        $this->Pedagang_model->update_pembeli($id_pedagang,$arr);
    }

    //edit no 3. Email pedagang
    function edit_email_pedagang()
    {
        $obj= json_decode(file_get_contents('php://input'), true);
        $id_pedagang=$obj['id_pedagang'];
        $email_pedagang=$obj['email_pedagang'];

        $arr = array(
            'EMAIL_PEDAGANG'=>$email_pedagang
        );
        $this->Pedagang_model->update_pedagang($id_pedagang,$arr);
    }

    //edit no 4. No HP pedagang
    function edit_nohp_pedagang()
    {
        $obj= json_decode(file_get_contents('php://input'), true);
        $id_pedagang=$obj['id_pedagang'];
        $nohp_pedagang=$obj['nohp_pedagang'];

        $arr = array(
            'NOHP_PEDAGANG'=>$nohp_pedagang
        );
        $this->Pedagang_model->update_pedagang($id_pedagang,$arr);
    }

    // edit no 4. Password pedagang
    function edit_password_pedagang()
    {
        $obj= json_decode(file_get_contents('php://input'), true);
        $id_pedagang=$obj['id_pedagang'];
        $password_pedagang=$obj['password_pedagang'];

        $arr = array(
            'PASSWORD_PEDAGANG'=>$password_pedagang
        );
        $this->Pedagang_model->update_pedagang($id_pedagang,$arr);
    }

    // edit no 5. Alamat pedagang
    function edit_alamat_pedagang()
    {
        $obj= json_decode(file_get_contents('php://input'), true);
        $id_pedagang=$obj['id_pedagang'];
        $alamat_pedagang=$obj['alamat_pedagang'];

        $arr = array(
            'ALAMAT_PEDAGANG'=>$alamat_pedagang
        );
        $this->Pedagang_model->update_pembeli($id_pedagang,$arr);
    }
    /*
     * End of pengaturan akun
     */

    /*
* start of penilaian
*/
    /*
     * Display penilaian, menampilkan penialian yang berisikan
     * 1. status berjualan
     * 2. Foto dagangan
     * 3. nama dagangan
     * 3. deskripsi
     * 4. menampilkan lokasi berjualan
     * 5. statistik
     * 6. peringkat
     */

    function penilaian_pedagang($json){

    }
}
