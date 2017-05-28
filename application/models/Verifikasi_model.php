<?php

/**
 * Created by PhpStorm.
 * User: AdeFulki
 * Date: 5/20/2017
 * Time: 12:43 PM
 */
class Verifikasi_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /*
     * Get verifikasi by ID_VERIFIKASI
     */
    function get_verifikasi($ID_VERIFIKASI)
    {
        return $this->db->get_where('VERIFIKASI',array('ID_VERIFIKASI'=>$ID_VERIFIKASI))->row_array();
    }

    /*
     * Get all verifikasi
     */
    function get_all_verifikasi()
    {
        return $this->db->get('VERIFIKASI')->result_array();
    }

    /*
     * function to add new verifikasi
     */
    function add_verifikasi($params)
    {
        $this->db->insert('VERIFIKASI',$params);
        return $this->db->insert_id();
    }

    /*
     * function to update verifikasi
     */
    function update_verifikasi($ID_VERIFIKASI,$params)
    {
        $this->db->where('ID_VERIFIKASI',$ID_VERIFIKASI);
        $response = $this->db->update('VERIFIKASI',$params);
        if($response)
        {
            return "verifikasi updated successfully";
        }
        else
        {
            return "Error occuring while updating verifikasi";
        }
    }

    /*
     * function to delete verifikasi
     */
    function delete_verifikasi($ID_VERIFIKASI)
    {
        $response = $this->db->delete('VERIFIKASI',array('ID_VERIFIKASI'=>$ID_VERIFIKASI));
        if($response)
        {
            return "verifikasi deleted successfully";
        }
        else
        {
            return "Error occuring while deleting verifikasi";
        }
    }
}