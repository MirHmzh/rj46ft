<!DOCTYPE html>
<html>
<head>
	<title>RJ46 Mikrotik FTP</title>
	<style type="text/css" media="screen">
		@font-face {
		  	font-family: Montserrat;
		  	src: url('<?= base_url('assets/font/Montserrat-Light.ttf') ?>');
		  	font-weight: normal;
  			font-style: normal;
		}
		@font-face {
		  	font-family: Montserrat;
		  	src: url('<?= base_url('assets/font/Montserrat-LightItalic.ttf') ?>');
		  	font-weight: normal;
  			font-style: italic;
		}
		@font-face {
		  	font-family: Montserrat;
		  	src: url('<?= base_url('assets/font/Montserrat-Bold.ttf') ?>');
		  	font-weight: bold;
  			font-style: normal;
		}
		@font-face {
		  	font-family: Montserrat;
		  	src: url('<?= base_url('assets/font/Montserrat-BoldItalic.ttf') ?>');
		  	font-weight: bold;
  			font-style: italic;
		}
		*{
			font-family: "Montserrat";
		}
		.pointer{
			cursor: pointer;
		}
		.wrapper{
			margin-top: 1em;
		}
		#droparea{
			border: 5px dotted black;
			height: 5em;
		}
		#droparea div{
			text-align: center;
			margin: auto;
			margin-top: 1em;
		}
		.caption{
			border: 1px solid #d6d6d6;
			border-radius: 0.3em;
			padding: 0.5em;
		}
		.nav-right-item{
			font-size: 1.3em;
			margin: 0.2em;
		}
	</style>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/dropzone.min.css') ?>">
	<script src="<?= base_url('assets/js/jquery.min.js') ?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?= base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?= base_url('assets/lib/fontawesome/js/all.min.js') ?>" type="text/javascript" charset="utf-8"></script>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="<?= base_url('main') ?>">RJ46</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
	    	<ul class="navbar-nav mr-auto"></ul>
	    	<form class="form-inline my-2 my-lg-0">
	      		<a class="nav-right-item" href="<?= base_url('main/upload_info') ?>" title="">
	      			<i class="fas fa-file"></i>
	      		</a>
	    	</form>
	  	</div>
	</nav>
	<div class="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="caption">
						<ol>
							<li>Rule 1</li>
							<li>Rule 2</li>
						</ol>
					</div>
					<br>
					<form action="" method="POST" role="form">
						<div class="form-group">
							<div class="col-sm-12">
								<select name="upload_path" id="input" class="form-control" required="required">
									<option value="<?= RT10_PATH ?>">RT 10</option>
									<option value="<?= RT11_PATH ?>">RT 11</option>
									<option value="<?= RT12_PATH ?>">RT 12</option>
								</select>
							</div>
						</div>
						<input type="file" name="file" id="file_upload" hidden>
					</form>
					<div id="droparea" class="pointer">
						<div>
							Click here to upload/Drop file
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		let dropArea = document.getElementById('droparea');
		['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		 	dropArea.addEventListener(eventName, preventDefaults, false)
		})
		dropArea.addEventListener('drop', handleDrop, false)
		$('#droparea').click(() => {
			$('input[type="file"]').click();
		});
		$('input[type="file"]').change(() => {
			let files = $('input[type="file"]').prop('files'),
				form_data = new FormData();
			form_data.append('file', files[0]);
			form_data.append('cur_dir', $('select[name="upload_path"]').val());
			$.ajax({
				url: "<?= base_url('main/upload_ftp') ?>",
				type: 'POST',
				dataType: 'json',
				processData: false,
				contentType: false,
				data: form_data,
				success: (data) => {
					if(data.ftp_status == true){
						alert('Upload success!');
					}
				},
				error: () => {
					alert('Upload failed');
				}
			});
		});
	});
	function handleDrop(e) {
		let dt = e.dataTransfer,
			files = dt.files,
			form_data = new FormData();
		form_data.append('file', files[0]);
		form_data.append('cur_dir', $('select[name="upload_path"]').val());
		$.ajax({
			url: "<?= base_url('main/upload_ftp') ?>",
			type: 'POST',
			dataType: 'json',
			processData: false,
			contentType: false,
			data: form_data,
			success: (data) => {
				if(data.ftp_status == true){
					alert('Upload success!');
				}
			},
			error: () => {
				alert('Upload failed');
			}
		});
	}
	function preventDefaults (e) {
	  e.preventDefault()
	  e.stopPropagation()
	}
</script>
</html>