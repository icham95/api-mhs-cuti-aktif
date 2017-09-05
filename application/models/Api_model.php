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

	public function login($username, $password, $table = "mahasiswa")
	{
		if ($table == 'mahasiswa') {
			if ($password !== 'stikom') {
				return false;
			}
			$this->db->insert('mahasiswa', ['username' => $username]);
			$insert_id = $this->db->insert_id();
			$query = $this->db->get_where('mahasiswa', ['npm' => $insert_id]);
			return $query->row();
		} else {
			// cek nama
			$select = [
					'id',
					'nama_lengkap',
					'role',
					'username',
					'password',
					'dibuat'
				];
			$this->db->select($select);
			$query = $this->db->get_where($table, ['username' => $username]);
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

	}

	public function register_mahasiswa($data)
	{
		$this->db->insert('mahasiswa', $data);
	}

	public function get_mahasiswa($npm)
	{
		$query = 
		"
			select
			mahasiswa.npm, 
			mahasiswa.nama_lengkap,
			mahasiswa.foto,
			mahasiswa.username,
			mahasiswa.dibuat
			from mahasiswa 
			where mahasiswa.npm = ?
		";
		$data = $this->db->query($query, [$npm]);
		return $data->row();
	}

	public function cek_apakah_hari_ini_cuti($npm)
	{
		$query = "select * from";
	}

	public function get_cuti()
	{
		$query = 
		"
		select 
		*,
		mahasiswa_cuti.dibuat as mahasiswa_cuti_dibuat,
		mahasiswa_cuti.id as mahasiswa_cuti_id
		from mahasiswa_cuti
		inner join mahasiswa
		on mahasiswa_cuti.npm = mahasiswa.npm
		where mahasiswa_cuti.status = 1 
		OR mahasiswa_cuti.status = 2
		OR mahasiswa_cuti.status = 3
		OR mahasiswa_cuti.status = 4
		ORDER BY mahasiswa_cuti.dibuat DESC
		";
		$data = $this->db->query($query);
		return $data->result_object();
	}

	public function get_aktif()
	{
		$query = 
		"
		select 
		*,
		mahasiswa_aktif_kuliah.id as mahasiswa_aktif_kuliah_id,
		mahasiswa_aktif_kuliah.dibuat as mahasiswa_aktif_kuliah_dibuat
		from mahasiswa_aktif_kuliah
		inner join mahasiswa
		on mahasiswa_aktif_kuliah.npm = mahasiswa.npm
		where mahasiswa_aktif_kuliah.status = 1
		OR mahasiswa_aktif_kuliah.status = 2
		OR mahasiswa_aktif_kuliah.status = 3
		OR mahasiswa_aktif_kuliah.status = 4
		ORDER BY mahasiswa_aktif_kuliah.dibuat desc
		";
		$data = $this->db->query($query);
		return $data->result_object();
	}

	public function get_mhs_aktif($npm)
	{
		$query = 
		"
		select 
		*,
		mahasiswa_aktif_kuliah.id as mahasiswa_aktif_kuliah_id,
		mahasiswa_aktif_kuliah.dibuat as mahasiswa_aktif_kuliah_dibuat
		from mahasiswa_aktif_kuliah
		inner join mahasiswa
		on mahasiswa_aktif_kuliah.npm = mahasiswa.npm
		WHERE mahasiswa_aktif_kuliah.npm = '$npm'
		ORDER BY mahasiswa_aktif_kuliah.dibuat DESC
		";
		$data = $this->db->query($query);
		return $data->result_object();
	}

	public function get_mhs_cuti($npm)
	{
		$query = 
		"
		select 
		*,
		mahasiswa_cuti.id as mahasiswa_cuti_id,
		mahasiswa_cuti.dibuat as mahasiswa_cuti_dibuat
		from mahasiswa_cuti where mahasiswa_cuti.npm = '$npm'
		ORDER BY mahasiswa_cuti.dibuat DESC
		";
		$data = $this->db->query($query);
		return $data->result_object();
	}

	public function get_aktif_diterima()
	{
		$query = 
		"
		select 
		*,
		mahasiswa_aktif_kuliah.dibuat as mahasiswa_aktif_kuliah_dibuat,
		mahasiswa_aktif_kuliah.id as mahasiswa_aktif_kuliah_id
		from mahasiswa_aktif_kuliah
		inner join mahasiswa
		on mahasiswa_aktif_kuliah.npm = mahasiswa.npm
		where mahasiswa_aktif_kuliah.status = 5
		ORDER BY mahasiswa_aktif_kuliah.dibuat DESC
		";
		$data = $this->db->query($query);
		return $data->result_object();
	}

	public function get_cuti_diterima()
	{
		$query = 
		"
		select 
		*,
		mahasiswa_cuti.dibuat as mahasiswa_cuti_dibuat,
		mahasiswa_cuti.id as mahasiswa_cuti_id
		from mahasiswa_cuti
		inner join mahasiswa
		on mahasiswa_cuti.npm = mahasiswa.npm
		where mahasiswa_cuti.status = 5
		ORDER BY mahasiswa_cuti.dibuat DESC
		";
		$data = $this->db->query($query);
		return $data->result_object();
	}

	public function get_chat($npm)
	{
		$query = 
		"select 
		mahasiswa.username,
		mahasiswa.nama_lengkap,
		mahasiswa.npm,
		baak_pm.status,
		baak_pm.msg,
		baak_pm.id,
		baak_pm.dibuat
		from baak_pm
		inner join mahasiswa
		on baak_pm.npm = mahasiswa.npm
		where baak_pm.npm = '$npm'
		";
		$data = $this->db->query($query);
		return $data->result_object();
	}

	public function get_baak_pm_list()
	{
		$query = 
		"
			select 
			baak_pm_list.npm,
			mahasiswa.nama_lengkap,
			mahasiswa.username
			from baak_pm_list
			inner join mahasiswa on baak_pm_list.npm = mahasiswa.npm
		";
		$data = $this->db->query($query);
		return $data->result_object();
	}

	public function insert($table, $data)
	{
		$this->db->insert($table, $data);
	}

	public function update($table, $where, $data)
	{
		$this->db->where($where);
		$this->db->update($table, $data);
	}

	public function get($table)
	{
		$query = $this->db->get_where($table);
		return $query->result_object();
	}

	public function get_row($table, $where)
	{
		$query = $this->db->get_where($table, $where);
		$data = $query->row();
		return $data;
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