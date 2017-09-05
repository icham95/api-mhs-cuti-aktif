<?php 

/**
* Admin model
*/
class Admin_model extends CI_Model
{
	public function insert($table, $data)
	{
		$this->db->insert($table, $data);
	}
}