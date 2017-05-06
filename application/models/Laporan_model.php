<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Laporan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get laporan by ID_LAPORAN
     */
    function get_laporan($ID_LAPORAN)
    {
        return $this->db->get_where('LAPORAN',array('ID_LAPORAN'=>$ID_LAPORAN))->row_array();
    }
    
    /*
     * Get all laporan
     */
    function get_all_laporan()
    {
        return $this->db->get('LAPORAN')->result_array();
    }
    
    /*
     * function to add new laporan
     */
    function add_laporan($params)
    {
        $this->db->insert('LAPORAN',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update laporan
     */
    function update_laporan($ID_LAPORAN,$params)
    {
        $this->db->where('ID_LAPORAN',$ID_LAPORAN);
        $response = $this->db->update('LAPORAN',$params);
        if($response)
        {
            return "laporan updated successfully";
        }
        else
        {
            return "Error occuring while updating laporan";
        }
    }
    
    /*
     * function to delete laporan
     */
    function delete_laporan($ID_LAPORAN)
    {
        $response = $this->db->delete('LAPORAN',array('ID_LAPORAN'=>$ID_LAPORAN));
        if($response)
        {
            return "laporan deleted successfully";
        }
        else
        {
            return "Error occuring while deleting laporan";
        }
    }
}
