<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(FCPATH . 'vendor/autoload.php');
use \Firebase\JWT\JWT;
use Carbon\Carbon;

class Welcome extends CI_Controller {

	public function index()
	{
		// $this->load->view('welcome_message');
		printf("Now: %s", Carbon::now());
	}
}
