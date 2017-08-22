<?php 

/**
* 
*/
class Api_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function login($username, $password)
	{
		// cek nama
		$query = $this->db->get_where('mahasiswa', ['username' => $username]);
		$data = $query->row();
		if (count($data) != 1) {
			return false;
		} else {
			if (password_verify($password, $data->password) == false) {
				return false;
			} else {
				return $data;
			}
		}
	}

	public function register_mahasiswa($data)
	{
		$this->db->insert('mahasiswa', $data);
	}

	public function get_where($table, $where)
	{
		$query = $this->db->get_where($table, $where);
		$data = $query->result_object();
		if (count($data) < 1) {
			return false;
		} else {
			return $data;
		}
	}

}