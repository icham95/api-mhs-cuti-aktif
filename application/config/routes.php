<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['api/mhs/login']['POST'] = 'api/login';
$route['api/mhs/register_mahasiswa']['POST'] = 'api/register_mahasiswa';

$route['api/mhs/cuti']['POST'] = 'api/cuti';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
