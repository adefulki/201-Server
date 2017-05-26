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
        $this->load->model('Pembeli_model','',True);
    }

    //contoh json
    function test(){
        $arr=array(
            'id_pembeli' => "02",
            'email_pembeli' => "adeh@ajah.com"
        );
        $this->edit_email_pembeli(json_encode($arr));
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


    /*
     * end of obrolan
     */

}