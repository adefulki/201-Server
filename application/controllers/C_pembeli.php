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

    //contoh json
    function test(){
        $arr=array(
            'id_pembeli' => "01",
        );
        $this->display_obrolan(json_encode($arr));
    }

    /*
     * start of pengaturan akun
     */
    function display_akun_pembeli($json)
    {
        $obj=json_decode($json);
        $id_pembeli=$obj->{'id_pembeli'};

        $pembeli = $this->Pembeli_model->get_pembeli($id_pembeli);

        $arr = array(
            'nama_pembeli'=> $pembeli['NAMA_PEMBELI'],
            'email_pembeli'=> $pembeli['EMAIL_PEMBELI'],
            'nohp_pembeli' => $pembeli['NOHP_PEMBELI'],
            'alamat_pembeli'=> $pembeli['ALAMAT_PEMBELI'],
        );
        print json_encode($arr);

    }

    function edit_nama_pembeli($json)
    {
        /*
         * mendecode json kedalam variabel obj
         */
        $obj= json_decode($json);

        /*
         * memisahkan atribut
         */
        $id_pembeli=$obj->{'id_pembeli'};
        $nama_pembeli=$obj->{'nama_pembeli'};

        $arr = array(
            'NAMA_PEMBELI'=>$nama_pembeli
        );
        $this->Pembeli_model->update_pembeli($id_pembeli,$arr);
    }

    function edit_email_pembeli($json)
    {
        $obj= json_decode($json);
        $id_pembeli=$obj->{'id_pembeli'};
        $email_pembeli=$obj->{'email_pembeli'};

        $arr = array(
            'EMAIL_PEMBELI'=>$email_pembeli
        );
        $this->Pembeli_model->update_pembeli($id_pembeli,$arr);
    }

    function edit_nohp_pembeli($json)
    {
        $obj= json_decode($json);
        $id_pembeli=$obj->{'id_pembeli'};
        $nohp_pembeli=$obj->{'nohp_pembeli'};

        $arr = array(
            'NOHP_PEMBELI'=>$nohp_pembeli
        );
        $this->Pembeli_model->update_pembeli($id_pembeli,$arr);
    }

    function edit_password_pembeli($json)
    {
        $obj= json_decode($json);
        $id_pembeli=$obj->{'id_pembeli'};
        $password_pembeli=$obj->{'password_pembeli'};

        $arr = array(
            'PASSWORD_PEMBELI'=>$password_pembeli
        );
        $this->Pembeli_model->update_pembeli($id_pembeli,$arr);
    }

    function edit_alamat_pembeli($json)
    {
        $obj= json_decode($json);
        $id_pembeli=$obj->{'id_pembeli'};
        $alamat_pembeli=$obj->{'alamat_pembeli'};

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
    function display_obrolan($json)
    {
        $obj=json_decode($json);
        $id_pembeli=$obj->{'id_pembeli'};
        $i=0;
        foreach($this-> Obrolan_model->get_obrolan_by_id_pembeli($id_pembeli) as $item){

            foreach($this->Dagangan_model->get_dagangan_by_id_dagangan($item['ID_DAGANGAN']) as $item2 ){
                $arr[$i]= array(
                    'nama_dagangan' => $item2['NAMA_DAGANGAN'],
                    'foto_dagangan' => $item2['FOTO_DAGANGAN'],
//                    'kategori_dagangan'=> $item2['KATEGORI_DAGANGAN'],
                );
                $arr2[$i]=array(
                    'text'=> $item['TEXT'],
                    'waktu_pengiriman' => $item['WAKTU_PENGIRIMAN'],
                );
                $i++;
            }

            print json_encode($arr);
            print json_encode($arr2);
        }
        return json_encode($arr);

    }
    /*
     * end of obrolan
     */

    /*
     *Start of Berlangganan
     */
    function display_berlangganan($json)
    {
        $obj=json_decode($json);
        $id_pembeli=$obj->{'id_pembeli'};
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
            print json_encode($arr);
        }
        return json_encode($arr);

    }
    /*
     * End Of Berlangganan
     */

    /*
     * start of
     */
//header( string: 'Content-Type: application/json');
//return json_encode($arr);
}