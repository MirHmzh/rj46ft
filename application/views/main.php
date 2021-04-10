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
				<div class="row">
					<form action="<?= base_url('main/upload_ftp') ?>" class="dropzone" id="my-awesome-dropzone">
						<input type="text" name="cur_dir" value="/flash">
						<input type="file" name="file_upload" />
					</form>

				</div>
			</div>
			<div class="col-12">
				<div class="row">
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
	$(document).ready(function() {
		getDir();
		downloadFile()
	});
	function getDir(dir = null) {
		$.ajax({
			url: dir == null ? "<?= base_url('main/list_ftp') ?>" : "<?= base_url('main/list_ftp/') ?>"+dir,
			type: 'GET',
			dataType: 'json',
			success: (data) => {
				$('#list_dir').html('');
				let files = data.files,
					dirs = data.dirs,
					doc_files = '',
					doc_dirs = '';
				console.log(files);
				files.forEach(function(el, i) {
					doc_files += `
						<tr>
				      		<th scope="row">${el}</th>
				    	</tr>
					`;
				});
				dirs.forEach(function(el, i) {
					doc_dirs += `
						<tr>
				      		<th scope="row">/${el}</th>
				    	</tr>
					`;
				});
				$('#list_dir').append(doc_dirs);
				$('#list_dir').append(doc_files);
			},
		});
	}

	function downloadFile() {
		var request = $.ajax({
			url: "<?= base_url('main/download_ftp') ?>",
			type: 'POST',
			// xhrFields : {
			// 	responseType : 'blob',
			// 	onprogress: function (e) {
			// 		console.log(((e.loaded*100)/e.total));
			// 	},
			// },
			dataType: "json",
			data: {file_path : 'template.docx'},
			success : function (data) {
				var a = document.createElement('a');
	            // var url = window.URL.createObjectURL(data.data);
	            a.href = data.data;
	            a.download = 'template.docx';
	            a.click();
	            	// window.URL.revokeObjectURL(url);
		            // $(buttonEl).html('<i class="fa fa-check" style="display:none"></i><i class="fa fa-download" style="display:none"></i>');
		            // $(buttonEl).find('.fa-check').fadeIn();
		            // setTimeout(function (argument) {
		            // 	$(buttonEl).find('.fa-check').fadeOut('slow', function (argument) {
		            // 		$(buttonEl).find('.fa-download').fadeIn();
		            // 	});
		            // }, 1000)
			}
		});

	}
</script>
</html>