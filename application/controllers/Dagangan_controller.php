<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 6/14/2017
 * Time: 10:07 PM
 */

include 'Penilaian_controller.php';
class Dagangan_controller extends CI_Controller
{
    private $daganganModel;
    private $penilaianModel;
    private $produkModel;
    private $pedagangModel;
    private $pelangganModel;
    private $penilaianController;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Penilaian_model','',True);
        $this->penilaianModel = new Penilaian_model();
        $this->load->model('Dagangan_model','',True);
        $this->daganganModel = new Dagangan_model();
        $this->load->model('Produk_model','',True);
        $this->produkModel = new Produk_model();
        $this->load->model('Pedagang_model','',True);
        $this->pedagangModel = new Pedagang_model();
        $this->load->model('Pelanggan_model','',True);
        $this->pelangganModel = new Pelanggan_model();
        $this->penilaianController = new Penilaian_controller();
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
    function getAllDaganganLocation(){
        $i=0;
        $arr=array();
        foreach($this->daganganModel->selectAllDagangan() as $item) {
            $arr[$i] = array(
                'idDagangan' => $item['idDagangan'],
                'namaDagangan' => $item['namaDagangan'],
                'fotoDagangan' => $item['fotoDagangan'],
                'latDagangan' => $item['latDagangan'],
                'lngDagangan' => $item['lngDagangan'],
                'meanPenilaianDagangan' => $this->penilaianController->meanPenilaian($item['idDagangan']),
                'countPenilaianDagangan' => $this->penilaianModel->countPenilaian($item['idDagangan']),
                'statusRecommendation' => $this->penilaianController->checkRecommendation($item['idDagangan']),
                'statusBerjualan' => filter_var($item['statusBerjualan'], FILTER_VALIDATE_BOOLEAN),
                'tipeDagangan' => filter_var($item['tipeDagangan'], FILTER_VALIDATE_BOOLEAN)
            );
            $i++;
        }

        header('Content-Type: application/json');
        echo json_encode($arr);
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
    function getDetailDagangan(){

        $obj=json_decode(file_get_contents('php://input'), true);
        $idDagangan=$obj['idDagangan'];
        $idPembeli=$obj['idPembeli'];
        $arr=array();
        $itemDagangan = $this->daganganModel->selectDagangan($idDagangan);
        $itemPedagang = $this->pedagangModel->selectPedagang($itemDagangan['idPedagang']);
        $i=0;
        $arr_produk=array();
        foreach($this->produkModel->selectProdukDagangan($idDagangan) as $item) {
            $arr_produk[$i]=array(
                'idProduk' => $item['idProduk'],
                'namaProduk' => $item['namaProduk'],
                'deskripsiProduk' => $item['deskripsiProduk'],
                'fotoProduk' => $item['fotoProduk'],
                'hargaProduk' => $item['hargaProduk'],
                'satuanProduk' => $item['satuanProduk'],
                'meanPenilaianProduk' => $this->penilaianController->meanPenilaianProduk($item['idProduk']),
                'countPenilaianProduk' => $this->penilaianModel->countPenilaianProduk($item['idProduk']),
            );
            $i++;
        }
        $arr=array(
            'idDagangan' => $itemDagangan['idDagangan'],
            'namaDagangan' => $itemDagangan['namaDagangan'],
            'fotoDagangan' => $itemDagangan['fotoDagangan'],
            'latDagangan' => $itemDagangan['latDagangan'],
            'lngDagangan' => $itemDagangan['lngDagangan'],
            'tipeDagangan' => $itemDagangan['tipeDagangan'],
            'statusBerjualan' => $itemDagangan['statusBerjualan'],
            'countPelanggan' => $this->pelangganModel->countPelanggan($idDagangan),
            'statusBerlangganan' => $this->pelangganModel->isBerlangganan($idPembeli, $idDagangan),
            'namaPedagang' => $itemPedagang['namaPedagang'],
            'emailPedagang' => $itemPedagang['emailPedagang'],
            'noPonselPedagang' => $itemPedagang['noPonselPedagang'],
            'alamatPedagang' => $itemPedagang['alamatPedagang'],
            'fotoPedagang' => $itemPedagang['fotoPedagang'],
            'produk' => $arr_produk
        );

        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    function changeStatusBerjualan(){
        $obj=json_decode(file_get_contents('php://input'), true);
        $idDagangan=$obj['idDagangan'];


        $this->daganganModel->updateStatusBerjualanDagangan($idDagangan);
    }

    function checkDagangan(){
        $obj=json_decode(file_get_contents('php://input'), true);
        $idDagangan=$obj['idDagangan'];

        $arr=array('statusValidDagangan'=>$this->daganganModel->isValidDagangan($idDagangan));
        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    function addDagangan(){
        $obj=json_decode(file_get_contents('php://input'), true);
        $idPedagang=$obj['idPedagang'];
        $namaDagangan=$obj['namaDagangan'];
        $deskripsiDagangan=$obj['deskripsiDagangan'];
        $fotoDagangan=$obj['fotoDagangan'];
        $tipeDagangan=$obj['tipeDagangan'];

        $this->daganganModel->insertDagangan($idPedagang,$namaDagangan,$deskripsiDagangan,$fotoDagangan,$tipeDagangan);
    }

    function setLokasiBerdagang(){
        $obj=json_decode(file_get_contents('php://input'), true);
        $idDagangan=$obj['idDagangan'];
        $latDagangan=$obj['latDagangan'];
        $lngDagangan=$obj['lngDagangan'];

        $this->daganganModel->updateLokasiDagangan($idDagangan, $latDagangan, $lngDagangan);
    }

    function randomLokasiDagangan(){
        foreach ($this->daganganModel->selectAllDagangan() as $item){
            $latMin=-5.885270;
            $lngMin=106.106868;

            $latMax=-7.644141;
            $lngMax=108.752211;

            $lat = $latMin + ($latMax - $latMin) * (mt_rand() / mt_getrandmax());
            $lng = $lngMin + ($lngMax - $lngMin) * (mt_rand() / mt_getrandmax());
            $this->daganganModel->updateLokasiDagangan($item['idDagangan'],$lat,$lng);
        }
    }
}