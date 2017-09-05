<?php 

/**
* admin controller
*/
class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
	}
	public function index()
	{
		$this->load->view('admin/index');
	}

	public function pendaftaran_mahasiswa()
	{
		$this->form_validation->set_rules('npm', 'npm', 'required');
		$this->form_validation->set_rules('nama_lengkap', 'nama_lengkap', 'required');
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		if ($this->form_validation->run() == false) {
			$output = [
				'insert' => false,
				'msg' => validation_errors()
			];
		} else {
			// jika berhasil
			$data = [
				'npm' => $this->input->post('npm'),
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'username' => $this->input->post('username'),
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
			];
			$this->Admin_model->insert('mahasiswa', $data);
			$output = [
				'insert' => true,
				'data' => $data
			];
		}
		$this->load->view('admin/pendaftaran_mahasiswa', $output);
	}

	public function pendaftaran_karyawan()
	{
		$this->form_validation->set_rules('nama_lengkap', 'nama_lengkap', 'required');
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		if ($this->form_validation->run() == false) {
			$output = [
				'insert' => false,
				'msg' => validation_errors()
			];
		} else {
			// jika berhasil
			$data = [
				'npm' => $this->input->post('npm'),
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'username' => $this->input->post('username'),
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
			];
			$this->Admin_model->insert('karyawan', $data);
			$output = [
				'insert' => true,
				'data' => $data
			];
		}
		$this->load->view('admin/pendaftaran_karyawan', $output);
	}
}