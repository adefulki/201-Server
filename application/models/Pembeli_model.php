<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Pembeli_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get pembeli by ID_PEMBELI
     */
    function get_pembeli($ID_PEMBELI)
    {
        return $this->db->get_where('PEMBELI',array('ID_PEMBELI'=>$ID_PEMBELI))->row_array();
    }
    
    /*
     * Get all pembeli
     */
    function get_all_pembeli()
    {
        return $this->db->get('PEMBELI')->result_array();
    }

    function get_count_nohp_pemebli($NOHP_PEMBELI)
    {
        return $this->db->get_where('PEMBELI',array('NOHP_PEMBELI'=>$NOHP_PEMBELI))->num_rows();
    }

    /*
     * function to add new pembeli
     */
    function add_pembeli($params)
    {
        $this->db->insert('PEMBELI',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update pembeli
     */
    function update_pembeli($ID_PEMBELI,$params)
    {
        $this->db->where('ID_PEMBELI',$ID_PEMBELI);
        $response = $this->db->update('PEMBELI',$params);
        if($response)
        {
            return "pembeli updated successfully";
        }
        else
        {
            return "Error occuring while updating pembeli";
        }
    }
    
    /*
     * function to delete pembeli
     */
    function delete_pembeli($ID_PEMBELI)
    {
        $response = $this->db->delete('PEMBELI',array('ID_PEMBELI'=>$ID_PEMBELI));
        if($response)
        {
            return "pembeli deleted successfully";
        }
        else
        {
            return "Error occuring while deleting pembeli";
        }
    }
}
