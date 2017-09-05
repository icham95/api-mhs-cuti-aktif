<!DOCTYPE html>
<html>
<head>
	<title>Pendafaran karyawan</title>
</head>
<body>
	<div>
		<?php 
			if ($insert === true) {
				echo 'berhasil memasukan data.';
			} else {
				echo $msg;
			}
		?>	
	</div>
	<form method="POST">
		<div>
			<label for="nama_lengkap">
				<b>nama_lengkap</b>
				<input type="text" id="nama_lengkap" name="nama_lengkap">
			</label>
		</div>

		<div>
			<label for="username">
				<b>username</b>
				<input type="text" id="username" name="username">
			</label>
		</div>

		<div>
			<label for="password">
				<b>password</b>
				<input type="text" id="password" name="password">
			</label>
		</div>

		<div>
			<button> Kirim </button>
		</div>
	</form>
</body>
</html>