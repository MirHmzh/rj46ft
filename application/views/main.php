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
	</style>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/dropzone.min.css') ?>">
	<script src="<?= base_url('assets/js/jquery.min.js') ?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?= base_url('assets/js/dropzone.min.js') ?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?= base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript" charset="utf-8"></script>
</head>
<body>
	<div class="wrapper">
		<div class="container">
			<div class="col-12">
				<div class="row" id="droparea">
					<table class="table table-borderless table-hover">
					  	<thead>
					    	<tr>
					      		<th scope="col"></th>
					    	</tr>
					  	</thead>
					  	<tbody id="list_dir">
					    	<!-- <tr>
					      		<th scope="row">raw.png</th>
					    	</tr> -->
					  </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript" charset="utf-8">
	let cur_dir = '/';
	$(document).ready(function() {
		getDir();
		// downloadFile();
		let dropArea = document.getElementById('droparea');
		['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		 	dropArea.addEventListener(eventName, preventDefaults, false)
		})
		dropArea.addEventListener('drop', handleDrop, false)
	});
	function handleDrop(e) {
		let dt = e.dataTransfer,
			files = dt.files,
			form_data = new FormData();
		form_data.append('file', files[0]);
		form_data.append('cur_dir', cur_dir);
		$.ajax({
			url: "<?= base_url('main/upload_ftp') ?>",
			type: 'POST',
			dataType: 'json',
			processData: false,
			contentType: false,
			data: form_data,
			success: (data) => {
				getDir();
			},
		});
	}
	function preventDefaults (e) {
	  e.preventDefault()
	  e.stopPropagation()
	}
	function backDir() {
		let splitted = cur_dir.split("/");
		splitted.pop();
		cur_dir = splitted.join("/");
		getDir();
	}
	function updateDir(dir) {
		if(cur_dir == '/'){
			cur_dir += dir;
		}else{
			cur_dir += '/'+dir;
		}
		getDir()
	}
	function getDir() {
		$.ajax({
			url: "<?= base_url('main/list_ftp/') ?>",
			type: 'POST',
			data: {dir: cur_dir},
			dataType: 'json',
			success: (data) => {
				$('#list_dir').html('');
				let files = data.files,
					dirs = data.dirs,
					doc_files = '',
					doc_dirs = '';
				files.forEach(function(el, i) {
					doc_files += `
						<tr class="pointer" onClick="downloadFile('${el}')">
				      		<th scope="row">${el}</th>
				    	</tr>
					`;
				});
				dirs.forEach(function(el, i) {
					doc_dirs += `
						<tr class="pointer" onClick="updateDir('${el}')">
				      		<th scope="row">/${el}</th>
				    	</tr>
					`;
				});
				cur_dir = data.dir;
				if(data.dir != '/'){
					$('#list_dir').append(`
						<tr class="pointer" onClick="backDir()">
				      		<th scope="row">/..</th>
				    	</tr>
						`);
				}

				$('#list_dir').append(doc_dirs);
				$('#list_dir').append(doc_files);
			},
		});
	}

	function downloadFile(file_name) {
		var request = $.ajax({
			url: "<?= base_url('main/download_ftp') ?>",
			type: 'POST',
			dataType: "json",
			data: {file_path : cur_dir+'/'+file_name},
			success : function (data) {
				var a = document.createElement('a');
	            a.href = data.data;
	            a.download = file_name;
	            a.click();
			}
		});
	}
</script>
</html>