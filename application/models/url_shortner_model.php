<?php

class url_shortner_model extends CI_Model {

    function add_url( $url )
    {
        // build up data array
        $data = array(
            'long_url'       => (string) $url,
            'short_url'     => (string) uniqid(), // creates a random key
            'created'   => date('Y-m-d H:i:s'),
        );
         
        // inserts the data into database
        $this->db->insert('urls', $data);
        
        // return this ID of the new inserted record
        return $this->db->insert_id();
    }
    
    
    public function get_url_by_id( $id )
    {
        $this->db->select('*');
        $this->db->from('urls');
        $this->db->where('id', (int) $id);
        
        //$result = $this->db->get()->row_object();
        $result= $this->db->get()->result_array();
//echo "<pre>"; print_r($data);exit;

        // check if the requested record was found
        if (count($result) > 0) {
            return $result;
        } else {
            return FALSE;
        }
    }
    
    
    public function get_url( $alias )
    {
        $this->db->select('*');
        $this->db->from('urls');
        $this->db->where('short_url', (string) $alias);
        
        //$result = $this->db->get()->row_object();
        $result= $this->db->get()->result_array();
        // check if the requested record was found
        if (count($result) > 0) {
            return $result;
        } else {
            return FALSE;
        }
    }

}

