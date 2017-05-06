<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Tag_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get tag by ID_TAG
     */
    function get_tag($ID_TAG)
    {
        return $this->db->get_where('TAG',array('ID_TAG'=>$ID_TAG))->row_array();
    }
    
    /*
     * Get all tag
     */
    function get_all_tag()
    {
        return $this->db->get('TAG')->result_array();
    }
    
    /*
     * function to add new tag
     */
    function add_tag($params)
    {
        $this->db->insert('TAG',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update tag
     */
    function update_tag($ID_TAG,$params)
    {
        $this->db->where('ID_TAG',$ID_TAG);
        $response = $this->db->update('TAG',$params);
        if($response)
        {
            return "tag updated successfully";
        }
        else
        {
            return "Error occuring while updating tag";
        }
    }
    
    /*
     * function to delete tag
     */
    function delete_tag($ID_TAG)
    {
        $response = $this->db->delete('TAG',array('ID_TAG'=>$ID_TAG));
        if($response)
        {
            return "tag deleted successfully";
        }
        else
        {
            return "Error occuring while deleting tag";
        }
    }
}
