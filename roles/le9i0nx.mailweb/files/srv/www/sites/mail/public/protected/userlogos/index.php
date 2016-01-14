<?php
session_start();
#var_dump($_SESSION);
#var_dump($_SERVER);
$cn=strtolower($_SERVER["PHP_AUTH_USER"]);

?>
<?php if (isset($_SESSION['AUTH'])): ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<title>Зарузка фото для подписи</title>
	<script type="text/javascript" src="/protected/userlogos/plupload.full.min.js"></script>
</head>

<body style="font: 13px Verdana; background: #eee; color: #333;text-align: center;position: absolute;margin: 0;width: 100%;top:  50%;margin-top: -200px;height: 400px;">
	<p style="font-size: 25px;">Допускаются фотографии только в формате jpg. и размером не более 10Мб</p>
	<p style="font-size: 25px;">Не работает через Internet Explorer воспользуйтесь Google Chrome или Mozilla Firefox</p>
	<div id="container">
		<button id="pickfiles" href="javascript:;" style="position: relative; z-index: 1;background: #fff;outline: none;border: 1px solid #000;font-size: 20px;border-radius: 2px;padding: 10px 20px;cursor: pointer;">Выберите фотографию</button>
	</div>

	<img src=/userlogos_data/avatars/<?php echo($cn);?>.jpg style="border: 1px solid #222; margin-top: 50px;">
	<script type="text/javascript">
var uploader = new plupload.Uploader({
	runtimes : 'html5,html4',
	browse_button : 'pickfiles', // you can pass in id...
	container: document.getElementById('container'), // ... or DOM Element itself
	url : 'upload.php',
	multipart: false,
	resize : { width : 128, height : 160, quality : 100 },
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Image files", extensions : "jpg"},
		]
	},

	init: {
		FilesAdded: function(up, files) {
			if (files.length && files[0].type === 'image/jpeg'){
				uploader.start();
			}
		},
		UploadComplete: function() {
			location.reload();
		}

	}
});

uploader.init();
</script>
</body>
</html>
<?php else: ?>
<?php header('Location: /userlogos/'); ?>
<?php endif; ?>

