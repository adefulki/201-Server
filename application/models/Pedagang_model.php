<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Pedagang_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get pedagang by ID_PEDAGANG
     */
    function get_pedagang($ID_DAGANGAN)
    {
        return $this->db->get_where('PEDAGANG',array('ID_DAGANGAN'=>$ID_DAGANGAN))->row_array();
    }

    /*
     * Get pedagang by input
     */
    function get_pedagang_by_input($input)
    {
        return $this->db->query("SELECT * FROM PEDAGANG WHERE MATCH (NAMA_PEDAGANG)
        AGAINST ('$input' IN BOOLEAN MODE)")->result_array();
    }

    function get_count_nohp_pedagang($NOHP_PEDAGANG)
    {
        return $this->db->get_where('PEDAGANG',array('NOHP_PEDAGANG'=>$NOHP_PEDAGANG))->num_rows();
    }
    
    /*
     * Get all pedagang
     */
    function get_all_pedagang()
    {
        return $this->db->get('PEDAGANG')->result_array();
    }
    
    /*
     * function to add new pedagang
     */
    function add_pedagang($params)
    {
        $this->db->insert('PEDAGANG',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update pedagang
     */
    function update_pedagang($ID_PEDAGANG,$params)
    {
        $this->db->where('ID_PEDAGANG',$ID_PEDAGANG);
        $response = $this->db->update('PEDAGANG',$params);
        if($response)
        {
            return "pedagang updated successfully";
        }
        else
        {
            return "Error occuring while updating pedagang";
        }
    }
    
    /*
     * function to delete pedagang
     */
    function delete_pedagang($ID_PEDAGANG)
    {
        $response = $this->db->delete('PEDAGANG',array('ID_PEDAGANG'=>$ID_PEDAGANG));
        if($response)
        {
            return "pedagang deleted successfully";
        }
        else
        {
            return "Error occuring while deleting pedagang";
        }
    }
}
