<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
</head>
<style type="text/css">
	* {
		margin: 0;
		padding: 0;
	}
	.header {
		width:100%;
		background-color:dodgerblue;
		color:white;
		font-size: 24px;
		padding:10px;
	}
	.nav-left {
		width:20%;
		float:left;
		background-color:#555555;
		color:white;
		padding:10px;
	}
	.content {
		width: 70%;
		float: left;
		padding:10px;
	}
	.link {
		color:white;
		display: block;
		padding:10px 0 10px 0;
		text-align: center;
	}
	.link:hover {
		background-color:rgba(0,0,0,0.2);
	}
</style>
<body>
	<div class="header">
		Admin
	</div>
	
	<div class="nav-left">
		<a class="link" href="<?= base_url('admin/pendaftaran_mahasiswa') ?>"> Blog </a>
	</div>

	<div class="content">
		aaa
	</div>
</body>
</html>