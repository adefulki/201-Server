<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Pelanggan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get pelanggan by ID_PELANGGAN
     */
    function get_pelanggan($ID_PELANGGAN)
    {
        return $this->db->get_where('PELANGGAN',array('ID_PELANGGAN'=>$ID_PELANGGAN))->row_array();
    }

    function get_count_pelanggan($ID_DAGANGAN){
        return $this->db->get_where('PELANGGAN',array('ID_DAGANGAN'=>$ID_DAGANGAN))->num_rows();
    }
    
    /*
     * Get all pelanggan
     */
    function get_all_pelanggan()
    {
        return $this->db->get('PELANGGAN')->result_array();
    }

    function get_count_pelanggan_by_id_pembeli_id_dagangan($ID_PEMBELI, $ID_DAGANGAN){
        return $this->db->get_where('PELANGGAN',array('ID_DAGANGAN'=>$ID_DAGANGAN, 'ID_PEMBELI'=>$ID_PEMBELI))->num_rows();
    }
    /*
     * function to add new pelanggan
     */
    function add_pelanggan($params)
    {
        $this->db->insert('PELANGGAN',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update pelanggan
     */
    function update_pelanggan($ID_PELANGGAN,$params)
    {
        $this->db->where('ID_PELANGGAN',$ID_PELANGGAN);
        $response = $this->db->update('PELANGGAN',$params);
        if($response)
        {
            return "pelanggan updated successfully";
        }
        else
        {
            return "Error occuring while updating pelanggan";
        }
    }
    
    /*
     * function to delete pelanggan
     */
    function delete_pelanggan($ID_PELANGGAN)
    {
        $response = $this->db->delete('PELANGGAN',array('ID_PELANGGAN'=>$ID_PELANGGAN));
        if($response)
        {
            return "pelanggan deleted successfully";
        }
        else
        {
            return "Error occuring while deleting pelanggan";
        }
    }
}
