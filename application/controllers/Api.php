<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(FCPATH . 'vendor/autoload.php');
use \Firebase\JWT\JWT;

class Api extends CI_Controller {

	private $key = 'ngopi';

	public function __construct()
	{
		// cors enable
		header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
	    $this->load->model('Api_model');
	}

	public function render($data, $status = 200)
	{
		$this->output
		        ->set_status_header($status)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		        ->_display();
		exit;
	}

	public function index()
	{
		
		// $this->load->view('welcome_message');
	}

	public function login()
	{
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'msg' => validation_errors()
				]);
		} else {
			$data = $this->Api_model->login($this->input->post('username'), $this->input->post('password'));
			if ($data == false) {
				$this->render([
						'success' => false,
						'msg' => 'username dan password tidak cocok.'
					]);
			} else {
				$now = time();
				// satu hari
				$unix = time() + (24 * 60 * 60);
				// $unix = time() + 60;
				$token = array(
				    "iat" => $now,
				    "exp" => $unix,
				    "data" => $data
				);
				$jwt = JWT::encode($token, $this->key);

				$this->render([
						'success' => true,
						'token' => $jwt
					]);

			}
		}
	}

	public function register_mahasiswa()
	{
		$data = [
			'npm' => $this->input->post('npm'),
			'nama_lengkap' => $this->input->post('nama_lengkap'),
			'jenis_kelamin' => $this->input->post('jenis_kelamin'),
			'telepon' => $this->input->post('telepon'),
			'username' => $this->input->post('username'),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
		];
		$this->Api_model->register_mahasiswa($data);
	}

	public function cuti()
	{
		$payload = $this->cek_token();
		$mhs_jumlah_semester = $this->Api_model->get_where('waktu_ajaran', 
			['npm' => $payload->data->npm] );
		if ($mhs_jumlah_semester != false) {
			// cek apakah ada tunggakan
			$pembayaran_per_semester = 3500000;
			$tunggakan = [];
			foreach ($mhs_jumlah_semester as $row => $value) {
				$mhs_bayar = $this->Api_model->get_where('pembayaran', 
					['waktu_ajaran_id' => $value->id]);
				if (count($mhs_bayar) > 0) {
					$total = 0;
					foreach ($mhs_bayar as $key1 => $value1) {
						$total += $value1->jumlah;
					}
					if ($total < $pembayaran_per_semester) {
						$tunggakan[$value->id] = [
							'tahun_akademik' => $value->tahun_akademik,
							'semester' => $value->semester,
							'tunggakan' => ($pembayaran_per_semester - $total)
						];
					}
				} else {
					// tunggakan
					$tunggakan[$value->id] = [
						'tahun_akademik' => $value->tahun_akademik,
						'semester' => $value->semester,
						'tunggakan' => $pembayaran_per_semester
					];
				}
			}
			print_r($tunggakan);
		}
	}

	public function cek_token()
	{
		$headers = $this->input->request_headers();
		try {
			$tokenArray = JWT::decode($headers['token'], $this->key, array('HS256'));
			return $tokenArray;
		}
		catch (Exception $e) {
			$this->render([
					'success' => false,
					'msg' => 'token tidak valid!'
				]);
		}
	}
}


