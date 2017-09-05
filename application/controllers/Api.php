<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(FCPATH . 'vendor/autoload.php');
use \Firebase\JWT\JWT;

class Api extends CI_Controller {

	private $key = 'ngopi';

	public function __construct()
	{
		// cors enable
		// header('Access-Control-Allow-Origin: *');
	 //    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    header('Access-Control-Allow-Origin: *');
	 	header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, Authorization');

	    parent::__construct();
	    $this->load->model('Api_model');
	}

	public function test()
	{
		// $this->render([
		// 		'oke' => $this->input->post('pic')
		// 	]);
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
				$unix = time() + (30 * 24 * 60 * 60);
				// $unix = time() + 60;
				$token = array(
				    "iat" => $now,
				    "exp" => $unix,
				    "data" => $data
				);
				$jwt = JWT::encode($token, $this->key);

				$this->render([
						'success' => true,
						'token' => $jwt,
						'user' => $data
					]);

			}
		}
	}

	public function baak_login()
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
			$data = $this->Api_model->login($this->input->post('username'), $this->input->post('password'), "karyawan");
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
						'token' => $jwt,
						'user' => $data
					]);

			}
		}
	}

	public function terima_pembayaran_administrasi_cuti()
	{
		$payload = $this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('npm', 'npm', 'required');
		$this->form_validation->set_rules('id', 'id', 'required');
		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'msg' => validation_errors()
				]);
		}
		$this->Api_model->update('mahasiswa_cuti', ['id' => $this->input->post('id')], ['status' => 3]);
		$this->render([
				'success' => true,
				'msg' => 'berhasil di terima pembayaran administrasi cuti'
			]);
	}

	public function terima_pembayaran_administrasi_aktif()
	{
		$payload = $this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('npm', 'npm', 'required');
		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'msg' => validation_errors()
				]);
		}
		$this->Api_model->update('mahasiswa_aktif_kuliah', ['npm' => $this->input->post('npm')], ['status' => 3]);
		$this->render([
				'success' => true,
				'msg' => 'berhasil di terima pembayaran administrasi cuti'
			]);
	}

	public function cancel_pembayaran_administrasi_aktif()
	{
		$payload = $this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('npm', 'npm', 'required');
		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'msg' => validation_errors()
				]);
		}
		$this->Api_model->update('mahasiswa_aktif_kuliah', ['npm' => $this->input->post('npm')], ['status' => 2]);
		$this->render([
				'success' => true,
				'msg' => 'berhasil cancel pembayaran administrasi aktif'
			]);	
	}

	public function cancel_pembayaran_administrasi_cuti()
	{
		$payload = $this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('npm', 'npm', 'required');
		$this->form_validation->set_rules('id', 'id', 'required');
		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'msg' => validation_errors()
				]);
		}
		$this->Api_model->update('mahasiswa_cuti', ['id' => $this->input->post('id')], ['status' => 2]);
		$this->render([
				'success' => true,
				'msg' => 'berhasil cancel pembayaran administrasi cuti'
			]);	
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

	public function register_baak()
	{
		$data = [
			'nama_lengkap' => $this->input->post('nama_lengkap'),
			'role' => $this->input->post('role'),
			'username' => $this->input->post('username'),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
		];
		$this->Api_model->insert('karyawan', $data);
	}

	public function baak_get_cuti()
	{
		$payload = $this->cek_token();
		$cutis = $this->Api_model->get_cuti();
		$rekening = $this->Api_model->get('rekening');
		$rekening_arr = [];
		foreach ($rekening as $value) {
			$rekening_arr[$value->id] = [
				'nama_rekening' => $value->nama_rekening,
				'no_rekening' => $value->no_rekening
			];
		}
		if ($cutis == false) {
			$this->render([
					'success' => false,
					'msg' => 'tidak ada permintaan'
				]);
		}
		$this->render([
				'success' => true,
				'data' => $cutis,
				'rekening' => $rekening_arr
			]);
	}

	public function baak_get_aktif()
	{
		$payload = $this->cek_token();
		$aktifs = $this->Api_model->get_aktif();
		$rekening = $this->Api_model->get('rekening');
		$rekening_arr = [];
		foreach ($rekening as $value) {
			$rekening_arr[$value->id] = [
				'nama_rekening' => $value->nama_rekening,
				'no_rekening' => $value->no_rekening
			];
		}
		if ($aktifs == false) {
			$this->render([
					'success' => false,
					'msg' => 'tidak ada permintaan'
				]);
		}
		$this->render([
				'success' => true,
				'data' => $aktifs,
				'rekening' => $rekening_arr
			]);
	}

	public function baak_get_aktif_diterima()
	{
		$payload = $this->cek_token();
		$aktifs = $this->Api_model->get_aktif_diterima();
		if ($aktifs == false) {
			$this->render([
					'success' => false,
					'msg' => 'tidak ada permintaan'
				]);
		}
		$this->render([
				'success' => true,
				'data' => $aktifs
			]);
	}

	public function baak_get_cuti_diterima()
	{
		$payload = $this->cek_token();
		$cuti = $this->Api_model->get_cuti_diterima();
		if ($cuti == false) {
			$this->render([
					'success' => false,
					'msg' => 'tidak ada permintaan'
				]);
		}
		$this->render([
				'success' => true,
				'data' => $cuti
			]);
	}

	public function mahasiswa($npm = false)
	{
		$this->cek_token();
		$mahasiswa = $this->Api_model->get_mahasiswa($npm);
		// $mahasiswa->jumlah_sks = count($mahasiswa->sks);
		// $mahasiswa->jumlah_semester = count($this->Api_model->get_where('waktu_ajaran', ['npm' => $npm]));
		// $mahasiswa->tahun_akademik = $this->Api_model->get_row('waktu_ajaran', ['npm' => $npm]);
		$this->render([
				'success' => true,
				'data' => $mahasiswa
			]);
	}

	public function aktif()
	{

		$payload = $this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('nip', 'nip', 'required');
		$this->form_validation->set_rules('nrp', 'nrp', 'required');
		$this->form_validation->set_rules('pangkat', 'pangkat', 'required');
		$this->form_validation->set_rules('golongan', 'golongan', 'required');
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('di', 'di', 'required');

		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'type' => 'validation',
					'msg' => validation_errors()
				]);
		}

		$cek = $this->syarat_aktif($payload->data->npm);
		if ($cek === true) {
			// bisa cuti
			$data = [
				'npm' => $payload->data->npm,
				'nip' => $this->input->post('nip'),
				'nrp' => $this->input->post('nrp'),
				'pangkat' => $this->input->post('pangkat'),
				'golongan' => $this->input->post('golongan'),
				'instansi' => $this->input->post('instansi'),
				'di' => $this->input->post('di'),
				'karyawan_id' => 1
			];
			$this->Api_model->insert('mahasiswa_aktif_kuliah', $data);
			$this->render([
					'success' => true
				]);
		} else {
			$this->render([
					'success' => false,
					'type' => 'syarat',
					'msg' => $cek
				]);
		}
	}

	public function get_aktif($npm)
	{
		$this->cek_token();
		// $cuti = $this->Api_model->get_where('mahasiswa_aktif_kuliah', ['npm' => $npm]);
		$cuti = $this->Api_model->get_mhs_aktif($npm);
		$rekening = $this->Api_model->get('rekening');
		if ($cuti === false) {
			$this->render([
					'success' => false,
					'msg' => 'belum punya cuti.'
				]);
		} 

		$this->render([
				'success' => true,
				'rekening' => $rekening,
				'data' => $cuti
			]);
	}

	public function aktif_upload()
	{

		$payload = $this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('id', 'id', 'required');
		$this->form_validation->set_rules('pic', 'pic', 'required');
	
		if ($this->form_validation->run() == false) {
			$this->render([
				'success' => false,
				'msg' => 'gambar belum di pilih'
			]);
		}

		$image = $this->input->post('pic');
		$where = ['id' => $this->input->post('id')];
		$data = [
			'status' => 2,
			'pic' => $image
		];
		$this->Api_model->update('mahasiswa_aktif_kuliah', $where, $data);
		$this->render([
				'success' => true,
				'msg' => 'berhasil di upload dan ganti status'
			]);

	}

	public function cuti()
	{

		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('telepon', 'telepon', 'required');
		$this->form_validation->set_rules('npm', 'npm', 'required');
		$this->form_validation->set_rules('pada_semester', 'pada_semester', 'required');
		$this->form_validation->set_rules('karena', 'karena', 'required');
		$this->form_validation->set_rules('tahun_akademik', 'tahun_akademik', 'required');
		$this->form_validation->set_rules('di', 'di', 'required');

		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'type' => 'validation',
					'msg' => validation_errors()
				]);
		}

		$cek = $this->syarat_cuti($this->input->post('npm'));
		if ($cek === true) {
			// bisa cuti
			$data = [
				'npm' => $this->input->post('npm'),
				'lama_cuti' => $this->input->post('pada_semester'),
				'catatan' => $this->input->post('karena'),
				'di' => $this->input->post('di'),
				'telepon' => $this->input->post('telepon'),
				'tahun_akademik' => $this->input->post('tahun_akademik'),
				'karyawan_id' => 1
			];
			$this->Api_model->insert('mahasiswa_cuti', $data);
			$this->render([
					'success' => true
				]);
		} else {
			$this->render([
					'success' => false,
					'type' => 'syarat',
					'msg' => $cek
				]);
		}
	}

	public function syarat_aktif($npm)
	{
		$tunggakan = $this->tunggakan($npm);
		$ipk = $this->ipk($npm);
		$sks = $this->Api_model->get_where('mahasiswa_sks', ['npm' => $npm]);
		$jumlah_semester = count($this->Api_model->get_where('waktu_ajaran', ['npm' => $npm]));
		$cek_apakah_hari_ini_cuti = $this->Api_model->cek_apakah_hari_ini_cuti($npm);

		$count_err = 0;
		$msg = [];
		if (count($tunggakan) > 0) {
			$count_err++;
			$msg[] = [
				'type' => 'tunggakan',
				'data' => $tunggakan
			];
		}

		if ($count_err > 0) {
			return $msg;
		}
		return true;
	}

	public function syarat_cuti($npm)
	{
		$tunggakan = $this->tunggakan($npm);
		$ipk = $this->ipk($npm);
		$sks = $this->Api_model->get_where('mahasiswa_sks', ['npm' => $npm]);
		$jumlah_semester = count($this->Api_model->get_where('waktu_ajaran', ['npm' => $npm]));
		$cek_apakah_hari_ini_cuti = $this->Api_model->cek_apakah_hari_ini_cuti($npm);

		$count_err = 0;
		$msg = [];
		if (count($tunggakan) > 0) {
			$count_err++;
			$msg[] = [
				'type' => 'tunggakan',
				'data' => $tunggakan
			];
		}

		if ($count_err > 0) {
			return $msg;
		}
		return true;
	}

	public function get_cuti($npm)
	{
		$this->cek_token();
		$cuti = $this->Api_model->get_mhs_cuti($npm);
		$rekening = $this->Api_model->get('rekening');
		if ($cuti === false) {
			$this->render([
					'success' => false,
					'msg' => 'belum punya cuti.'
				]);
		} 

		$this->render([
				'success' => true,
				'rekening' => $rekening,
				'data' => $cuti
			]);
	}

	public function cuti_upload()
	{

		$this->cek_token();
		$this->form_validation->set_rules('id', 'id', 'required');
		$this->form_validation->set_rules('pic', 'pic', 'required');

		if ($this->form_validation->run() == false) {
			$this->render([
				'success' => false,
				'msg' => 'gambar belum di pilih'
			]);
		}

		$image = $this->input->post('pic');
		$where = ['id' => $this->input->post('id')];
		$data = [
			'status' => 2,
			'pic' => $image
		];
		$this->Api_model->update('mahasiswa_cuti', $where, $data);
		$this->render([
				'success' => true,
				'msg' => 'berhasil di upload dan ganti status'
			]);

	}

	public function ganti_baak_cuti_status()
	{
		$this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('status', 'status', 'required');
		$this->form_validation->set_rules('id', 'id', 'required');
		if ($this->form_validation->run() == true) {
			$where = [
					'id' => $this->input->post('id')	
				];
			if ($this->input->post('status') == 2) {
				$data = [
					'status' => 2,
					'catatan_lebih' => $this->input->post('catatan_lebih')
				];
			}
			if ($this->input->post('status') == 3) {
				$data = [
					'status' => 3,
					'catatan_lebih' => $this->input->post('catatan_lebih'),
					'tunggakan' => $this->input->post('tunggakan')
				];
			}
			if ($this->input->post('status') == 5) {
				$data = [
					'status' => 5
				];
			}
			$this->Api_model->update('mahasiswa_cuti', $where, $data);	
			$this->render([
					'success' => true,
					'status_back' => $this->input->post('status')
				]);
		} else {
			$this->render([
					'success' => false,
					'status_back' => $this->input->post('status')
				]);
		}
	}

	public function ganti_baak_aktif_status()
	{
		$this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('status', 'status', 'required');
		$this->form_validation->set_rules('id', 'id', 'required');
		if ($this->form_validation->run() == true) {
			$where = [
					'id' => $this->input->post('id')	
				];
			if ($this->input->post('status') == 2) {
				$data = [
					'status' => 2,
					'catatan_lebih' => $this->input->post('catatan_lebih')
				];
			}
			if ($this->input->post('status') == 3) {
				$data = [
					'status' => 3,
					'catatan_lebih' => $this->input->post('catatan_lebih'),
					'tunggakan' => $this->input->post('tunggakan')
				];
			}
			if ($this->input->post('status') == 5) {
				$data = [
					'status' => 5
				];
			}
			$this->Api_model->update('mahasiswa_aktif_kuliah', $where, $data);	
			$this->render([
					'success' => true,
					'status_back' => $this->input->post('status')
				]);
		} else {
			$this->render([
					'success' => false,
					'status_back' => $this->input->post('status')
				]);
		}
	}

	public function mhs_pembayaran_status_tiga()
	{
		$this->cek_token();
		// id: data.id,
	  	// rekening_id: data.rekening_id,
	  	// no_rekening_mahasiswa: data.no_rekening_mahasiswa,
	  	// jumlah: data.jumlah,
	  	// catatan_lebih2: data.catatan_lebih2,
	  	// pic: data.pic
	  	$this->form_validation->set_data($_POST);
	  	$this->form_validation->set_rules('id', 'id', 'required');
	  	if ($this->form_validation->run() == false) {
	  		$this->render([
	  				'success' => false
	  			]);
	  	}
	  	$table = 'mahasiswa_cuti';
	  	$where = ['id' => $this->input->post('id')];
	  	$data = [
	  		'rekening_id' => $this->input->post('rekening_id'),
	  		'catatan_lebih2' => $this->input->post('catatan_lebih2'),
	  		'jumlah' => $this->input->post('jumlah'),
	  		'no_rekening_mahasiswa' => $this->input->post('no_rekening_mahasiswa'),
	  		'pic' => $this->input->post('pic'),
	  		'status' => 4
	  	];
	  	$this->Api_model->update($table, $where, $data);
	  	$this->render([
	  			'success' => true
	  		]);
	}

	public function mhs_pembayaran_status_tiga_aktif()
	{
		$this->cek_token();
		// id: data.id,
	  	// rekening_id: data.rekening_id,
	  	// no_rekening_mahasiswa: data.no_rekening_mahasiswa,
	  	// jumlah: data.jumlah,
	  	// catatan_lebih2: data.catatan_lebih2,
	  	// pic: data.pic
	  	$this->form_validation->set_data($_POST);
	  	$this->form_validation->set_rules('id', 'id', 'required');
	  	if ($this->form_validation->run() == false) {
	  		$this->render([
	  				'success' => false
	  			]);
	  	}
	  	$table = 'mahasiswa_aktif_kuliah';
	  	$where = ['id' => $this->input->post('id')];
	  	$data = [
	  		'rekening_id' => $this->input->post('rekening_id'),
	  		'catatan_lebih2' => $this->input->post('catatan_lebih2'),
	  		'jumlah' => $this->input->post('jumlah'),
	  		'no_rekening_mahasiswa' => $this->input->post('no_rekening_mahasiswa'),
	  		'pic' => $this->input->post('pic'),
	  		'status' => 4
	  	];
	  	$this->Api_model->update($table, $where, $data);
	  	$this->render([
	  			'success' => true
	  		]);
	}

	public function ganti_mhs_cuti_status()
	{
		$this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('id', 'id', 'required');
		$this->form_validation->set_rules('status', 'status', 'required');
		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'msg' => validation_errors()
				]);
		}
		$this->Api_model->update('mahasiswa_cuti', ['id' => $this->input->post('id')], ['status' => $this->input->post('status')]);
		$this->render([
				'success' => true,
				'status_back' => $this->input->post('status')
			]);
	}

	public function insert_cuti()
	{
		// "nama_lengkap": "hisya",
	 //    "npm": "908098",
	 //    "program_studi": "prog",
	 //    "semester_tahun_akademik": "2017",
	 //    "alamat_rumah": "asldksa;ldk",
	 //    "telepon": ";k;asdlakd",
	 //    "jumlah_semester_diselesaikan": ";sakd;skld",
	 //    "jumlah_sks_diperoleh": ";saldka;sdk",
	 //    "ipk_diperoleh": ";klsakd;akd",
	 //    "cuti_di_semester": "k;askd;akd",
	 //    "cuti_tahun_akademik": "2018",
	 //    "karena": ";laskd;akd",
	 //    "di": ";laks;d"
		$this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('nama_lengkap', 'nama lengkap', 'required');
		$this->form_validation->set_rules('npm', 'npm', 'required');
		$this->form_validation->set_rules('program_studi', 'program studi', 'required');
		$this->form_validation->set_rules('semester_tahun_akademik', 'semester tahun akademik', 'required');
		$this->form_validation->set_rules('alamat_rumah', 'alamat rumah', 'required');
		$this->form_validation->set_rules('telepon', 'telepon', 'required');
		$this->form_validation->set_rules('jumlah_semester_diselesaikan', 'jumlah_semester_diselesaikan', 'required');
		$this->form_validation->set_rules('jumlah_sks_diperoleh', 'jumlah_sks_diperoleh', 'required');
		$this->form_validation->set_rules('ipk_diperoleh', 'ipk_diperoleh', 'required');
		$this->form_validation->set_rules('cuti_di_semester', 'cuti_di_semester', 'required');
		$this->form_validation->set_rules('cuti_tahun_akademik', 'cuti_tahun_akademik', 'required');
		$this->form_validation->set_rules('karena', 'karena', 'required');
		$this->form_validation->set_rules('di', 'di', 'required');
		$this->form_validation->set_rules('npm_id', 'npm_id', 'required');
		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'msg' => validation_errors()
				]);
		}
		$data = [
			'npm' => $this->input->post('npm_id'),
			'c_nama_lengkap' => $this->input->post('nama_lengkap'),
			'c_npm' => $this->input->post('npm'),
			'c_program_studi' => $this->input->post('program_studi'),
			'c_semester_tahun_akademik' => $this->input->post('semester_tahun_akademik'),
			'c_alamat_rumah' => $this->input->post('alamat_rumah'),
			'c_telepon' => $this->input->post('telepon'),
			'c_jumlah_semester_diselesaikan' => $this->input->post('jumlah_semester_diselesaikan'),
			'c_jumlah_sks_diperoleh' => $this->input->post('jumlah_sks_diperoleh'),
			'c_ipk_diperoleh' => $this->input->post('ipk_diperoleh'),
			'c_cuti_disemester' => $this->input->post('cuti_di_semester'),
			'c_cuti_tahun_akademik' => $this->input->post('cuti_tahun_akademik'),
			'c_karena' => $this->input->post('karena'),
			'di' => $this->input->post('di'),
			'status' => 1
		];
		$this->Api_model->insert('mahasiswa_cuti', $data);
		$this->render([
				'success' => true,
				'data_back' => $_POST
			]);

	}

	public function insert_aktif()
	{
		// nama_lengkap: null,
  //         npm: null,
  //         semester: null,
  //         program_studi: null,
  //         tahun_akademik: null,
  //         nama_orang_tua_wali: null,
  //         nip_nrp: null,
  //         pangkat: null,
  //         golongan: null,
  //         instansi: null,
  //         di: null
		$this->cek_token();
		$this->form_validation->set_data($_POST);
		$this->form_validation->set_rules('nama_lengkap', 'nama lengkap', 'required');
		$this->form_validation->set_rules('npm', 'npm', 'required');
		$this->form_validation->set_rules('semester', 'semester', 'required');
		$this->form_validation->set_rules('program_studi', 'program studi', 'required');
		$this->form_validation->set_rules('tahun_akademik', 'tahun akademik', 'required');
		$this->form_validation->set_rules('nama_orang_tua_wali', 'nama orang tua/wali', 'required');
		$this->form_validation->set_rules('nip_nrp', 'nip_nrp', 'required');
		$this->form_validation->set_rules('pangkat', 'pangkat', 'required');
		$this->form_validation->set_rules('golongan', 'golongan', 'required');
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('di', 'di', 'required');
		$this->form_validation->set_rules('npm_id', 'npm_id', 'required');
		if ($this->form_validation->run() == false) {
			$this->render([
					'success' => false,
					'msg' => validation_errors()
				]);
		}
		$data = [
			'npm' => $this->input->post('npm_id'),
			'c_nama_lengkap' => $this->input->post('nama_lengkap'),
			'c_npm' => $this->input->post('npm'),
			'c_semester' => $this->input->post('semester'),
			'c_program_studi' => $this->input->post('program_studi'),
			'c_tahun_akademik' => $this->input->post('tahun_akademik'),
			'c_nama_orang_tua_wali' => $this->input->post('nama_orang_tua_wali'),
			'c_nip_nrp' => $this->input->post('nip_nrp'),
			'c_pangkat' => $this->input->post('pangkat'),
			'c_golongan' => $this->input->post('golongan'),
			'c_instansi' => $this->input->post('instansi'),
			'c_di' => $this->input->post('di'),
			'status' => 1
		];
		$this->Api_model->insert('mahasiswa_aktif_kuliah', $data);
		$this->render([
				'success' => true,
				'data_back' => $_POST
			]);

	}

	public function ganti_mahasiswa()
	{
		$payload = $this->cek_token();
		$npm = $payload->data->npm;
		$where = ['npm' => $npm];
		if (isset($_POST['nama_lengkap'])) {
			$data = ['nama_lengkap' => $this->input->post('nama_lengkap')];
			$this->Api_model->update('mahasiswa', $where, $data);
			$this->render([
					'success' => true
				]);
		}

		if (isset($_POST['username'])) {
			// cek apakah ada username
			// return false jika ga ada
			$cek_username = $this->Api_model->get_where('mahasiswa', ['username' => $this->input->post('username')]);
			if ($cek_username == false) {
				$data = ['username' => $this->input->post('username')];
				$this->Api_model->update('mahasiswa', $where, $data);
				$this->render([
						'success' => true
					]);
			}
		}

		if (isset($_POST['foto'])) {
			$data = ['foto' => $this->input->post('foto')];
			$this->Api_model->update('mahasiswa', $where, $data);
			$this->render([
					'success' => true
				]);
		}

		if (isset($_POST['password_lama']) && isset($_POST['password_baru'])) {
			// cek apakah benar password lama
			if (password_verify($this->input->post('password_lama'), $payload->data->password) == true) {
				$password_baru = password_hash($this->input->post('password_baru'), PASSWORD_DEFAULT);
				$this->Api_model->update('mahasiswa', $where, ['password' => $password_baru]);
				$this->render([
						'success' => true,
						'msg' => 'Update password berhasil'
					]);
			}
			$this->render([
				'success' => false,
				'msg' => 'password lama tidak cocok.'
			]);
		}
	}	

	public function baak_get_user($id)
	{
		$payload = $this->cek_token();
		$user = $this->Api_model->get_row('karyawan', ['id' => $id]);
		$this->render([
				'success' => true,
				'data' => $user
			]);
	}

	public function baak_ganti_profile()
	{
		$payload = $this->cek_token();
		$table = 'karyawan';
		$where = ['id' => $payload->data->id];
		if ($this->input->post('nama_lengkap') == true) {
			$data = ['nama_lengkap' => $this->input->post('nama_lengkap')];
			$this->Api_model->update($table, $where, $data);
			$this->render([
					'success' => true,
					'msg' => 'berhasil update nama lengkap'
				]);
		}

		if ($this->input->post('username') == true) {
			$data = ['username' => $this->input->post('username')];
			$this->Api_model->update($table, $where, $data);
			$this->render([
					'success' => true,
					'msg' => 'berhasil update username'
				]);
		}

		if ($this->input->post('password_lama') == true && $this->input->post('password_baru') == true) {
			// cek apakah password lama sama 
			if (password_verify($this->input->post('password_lama'), $payload->data->password) == true) {
				$data = ['password' => password_hash($this->input->post('password_baru'), PASSWORD_DEFAULT)];
				$this->Api_model->update($table, $where, $data);
				$this->render([
						'success' => true,
						'msg' => 'berhasil update password'
					]);
			}
			$this->render([
					'success' => false,
					'msg' => 'password lama tidak cocok.'
				]);
		}

		if ($this->input->post('foto') == true) {
			$data = ['foto' => $this->input->post('foto')];
			$this->Api_model->update($table, $where, $data);
			$this->render([
					'success' => true,
					'msg' => 'berhasil update foto'
				]);
		}

	}

	public function chat_save_mhs()
	{
		$this->cek_token();
		// npm, msg, 
		// status 1 = dari mahasiswa
		// status 2 = dari baak
		$data = [
			'npm' => $this->input->post('npm'),
			'msg' => $this->input->post('msg'),
			'status' => 1
		];
		// masukin ke baak_chat_pm
		$list = $this->Api_model->get_where('baak_pm_list', ['npm' => $this->input->post('npm')]);
		if (!$list) {
			$this->Api_model->insert('baak_pm_list', ['npm' => $this->input->post('npm')]);
		}
		// masukin ke baak_pm
		$this->Api_model->insert('baak_pm', $data);
		$this->render([
				'success' => true,
				'data_back' => $data
			]);
	}

	public function chat_save_baak()
	{
		$this->cek_token();
		// npm, msg, 
		// status 1 = dari mahasiswa
		// status 2 = dari baak
		$data = [
			'npm' => $this->input->post('npm'),
			'msg' => $this->input->post('msg'),
			'status' => 2
		];
		$this->Api_model->insert('baak_pm', $data);
		$this->render([
				'success' => true,
				'data_back' => $data
			]);
	}

	public function chat_get($npm)
	{
		$this->cek_token();
		$datas = $this->Api_model->get_chat($npm);
		$this->render([
				'success' => true,
				'data' => $datas
			]);
	}

	public function chat_get_list()
	{
		$this->cek_token();
		$datas = $this->Api_model->get_baak_pm_list();
		$this->render([
				'success' => true,
				'data' => $datas
			]);
	}

	public function cek_token()
	{
		$token = $this->input->get_request_header('Authorization');
		try {
			$tokenArray = JWT::decode($token, $this->key, array('HS256'));
			return $tokenArray;
		}
		catch (Exception $e) {
			$this->render([
					'success' => false,
					'msg' => 'token tidak valid!',
					'asd' => $this->input->get_request_header('token')
				]);
		}
	}

	public function tunggakan($npm)
	{
		$mhs_jumlah_semester = $this->Api_model->get_where('waktu_ajaran', 
			['npm' => $npm] );
		if ($mhs_jumlah_semester != false) {
			$pembayaran_per_semester = 500000;
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

			return $tunggakan;
		}
	}

	public function ipk($npm)
	{
		$ip = $this->Api_model->get_where('mahasiswa_ip', ['npm' => $npm]);
		$total_semester = $this->Api_model->get_where('waktu_ajaran', ['npm' => $npm]);
		$total_ip = 0;
		foreach ($ip as $value) {
			$total_ip += $value->nilai;
		}
		$ipk = $total_ip / count($total_semester);
		return $ipk;
	}

	public function blog()
	{
		$data = $this->Api_model->get('blog');
		$this->render($data);
	}
}


