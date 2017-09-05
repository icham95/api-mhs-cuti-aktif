<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['api/mhs/login']['POST'] = 'api/login';
$route['api/mhs/register_mahasiswa']['POST'] = 'api/register_mahasiswa';

$route['api/baak/login']['POST'] = 'api/baak_login';
$route['api/baak/register']['POST'] = 'api/register_baak';

$route['api/baak/(:num)']['OPTIONS'] = 'api/baak_get_user/$1';
$route['api/baak/(:num)']['GET'] 	 = 'api/baak_get_user/$1';

$route['api/baak/profile']['OPTIONS'] = 'api/baak_ganti_profile';
$route['api/baak/profile']['POST']    = 'api/baak_ganti_profile';

$route['api/baak/cuti']['OPTIONS'] = 'api/baak_get_cuti';
$route['api/baak/cuti']['GET'] = 'api/baak_get_cuti';

$route['api/baak/aktif']['OPTIONS'] = 'api/baak_get_aktif';
$route['api/baak/aktif']['GET'] = 'api/baak_get_aktif';

$route['api/baak/aktif_diterima']['OPTIONS'] = 'api/baak_get_aktif_diterima';
$route['api/baak/aktif_diterima']['GET'] = 'api/baak_get_aktif_diterima';

$router['api/baak/ganti_baak_cuti_status']['OPTIONS'] = 'api/ganti_baak_cuti_status';
$router['api/baak/ganti_baak_cuti_status']['POST'] = 'api/ganti_baak_cuti_status';

$router['api/baak/ganti_baak_aktif_status']['OPTIONS'] = 'api/ganti_baak_aktif_status';
$router['api/baak/ganti_baak_aktif_status']['POST'] = 'api/ganti_baak_aktif_status';

// $route['api/baak/terima_pembayaran_administrasi_cuti']['OPTIONS'] = 'api/terima_pembayaran_administrasi_cuti';
// $route['api/baak/terima_pembayaran_administrasi_cuti']['POST'] = 'api/terima_pembayaran_administrasi_cuti';

// $route['api/baak/terima_pembayaran_administrasi_aktif']['OPTIONS'] = 'api/terima_pembayaran_administrasi_aktif';
// $route['api/baak/terima_pembayaran_administrasi_aktif']['POST'] = 'api/terima_pembayaran_administrasi_aktif';

// $route['api/baak/cancel_pembayaran_administrasi_aktif']['OPTIONS'] = 'api/cancel_pembayaran_administrasi_aktif';
// $route['api/baak/cancel_pembayaran_administrasi_aktif']['POST'] = 'api/cancel_pembayaran_administrasi_aktif';

// $route['api/baak/cancel_pembayaran_administrasi_cuti']['OPTIONS'] = 'api/cancel_pembayaran_administrasi_cuti';
// $route['api/baak/cancel_pembayaran_administrasi_cuti']['POST'] = 'api/cancel_pembayaran_administrasi_cuti';


$route['api/mhs/cuti/create']['POST'] = 'api/cuti';

$route['api/mhs/cuti/(:num)']['GET'] = 'api/get_cuti/$1';
$route['api/mhs/cuti/(:num)']['OPTIONS'] = 'api/get_cuti/$1';

$route['api/baak/cuti_diterima']['OPTIONS'] = 'api/baak_get_cuti_diterima';
$route['api/baak/cuti_diterima']['GET'] = 'api/baak_get_cuti_diterima';

$route['api/mhs/cuti/pembayaran_status_tiga']['OPTIONS'] = 'api/mhs_pembayaran_status_tiga';
$route['api/mhs/cuti/pembayaran_status_tiga']['POST'] = 'api/mhs_pembayaran_status_tiga';

$route['api/mhs/aktif/pembayaran_status_tiga']['OPTIONS'] = 'api/mhs_pembayaran_status_tiga_aktif';
$route['api/mhs/aktif/pembayaran_status_tiga']['POST'] = 'api/mhs_pembayaran_status_tiga_aktif';

$route['api/mhs/cuti/ganti_mhs_cuti_status']['OPTIONS'] = 'api/ganti_mhs_cuti_status';
$route['api/mhs/cuti/ganti_mhs_cuti_status']['POST'] = 'api/ganti_mhs_cuti_status';

$route['api/mhs/aktif/create']['OPTIONS'] = 'api/aktif';
$route['api/mhs/aktif/create']['POST'] = 'api/aktif';

$route['api/mhs/aktif/(:num)']['GET'] = 'api/get_aktif/$1';
$route['api/mhs/aktif/(:num)']['OPTIONS'] = 'api/get_aktif/$1';

$route['api/mhs/aktif/upload_pic']['OPTIONS'] = 'api/aktif_upload';
$route['api/mhs/aktif/upload_pic']['POST'] = 'api/aktif_upload';

$route['api/mhs/cuti/upload_pic']['OPTIONS'] = 'api/cuti_upload';
$route['api/mhs/cuti/upload_pic']['POST'] = 'api/cuti_upload';

$route['api/mhs/cuti/insert_cuti']['OPTIONS'] = 'api/insert_cuti';
$route['api/mhs/cuti/insert_cuti']['POST'] = 'api/insert_cuti';

$route['api/mhs/aktif/insert_aktif']['OPTIONS'] = 'api/insert_aktif';
$route['api/mhs/aktif/insert_aktif']['POST'] = 'api/insert_aktif';

$route['api/mhs']['GET'] = 'api/mahasiswa';

$route['api/mhs/(:num)']['OPTIONS'] = 'api/mahasiswa/$1';
$route['api/mhs/(:num)']['GET'] = 'api/mahasiswa/$1';

$route['api/mhs/ganti']['OPTIONS'] = 'api/ganti_mahasiswa/$1';
$route['api/mhs/ganti']['POST'] = 'api/ganti_mahasiswa/$1';

$route['api/chat/mhs']['OPTIONS'] = 'api/chat_save_mhs';
$route['api/chat/mhs']['POST'] = 'api/chat_save_mhs';

$route['api/chat/baak']['OPTIONS'] = 'api/chat_save_baak';
$route['api/chat/baak']['POST'] = 'api/chat_save_baak';

$route['api/chat/get/(:num)']['OPTIONS'] = 'api/chat_get/$1';
$route['api/chat/get/(:num)']['GET'] = 'api/chat_get/$1';

$route['api/chat/list']['OPTIONS'] = 'api/chat_get_list';
$route['api/chat/list']['GET'] = 'api/chat_get_list';

$route['test'] = 'api/test';

$route['admin'] = 'admin/index';
$route['admin/pendaftaran'] = 'admin/pendaftaran';

$route['blogs']['GET'] = 'api/blog';
$route['admin/blog'] = 'api/admin_blog';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
