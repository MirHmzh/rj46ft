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
		tbody tr{
			font-weight: normal;
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
				<div class="col-4">
					<div id="accordion">
					  	<div class="card">
					    	<div class="card-header" id="headingOne">
					      		<h5 class="mb-0">
					        		<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					          			RW 04
					        		</button>
					      		</h5>
					    	</div>
					    	<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
					      		<div class="card-body pointer" onClick="getDir('<?= RT10_PATH ?>')">
					      			RT10
					      		</div>
					      		<div class="card-body pointer" onClick="getDir('<?= RT11_PATH ?>')">
					        		RT11
					      		</div>
					      		<div class="card-body pointer" onClick="getDir('<?= RT12_PATH ?>')">
					        		RT12
					      		</div>
					    	</div>
					  	</div>
					</div>
				</div>
				<div class="col-8">
					<table class="table table-hover">
					  	<thead>
					    	<tr>
					      		<th scope="col">File Name</th>
					    	</tr>
					  	</thead>
					  	<tbody id="list_dir">
					  		<tr class="no-data">
					            <td colspan="4">No data</td>
					        </tr>
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
		getDir('<?= RT10_PATH ?>');
	});
	function getDir(cur_dir) {
		$('#list_dir').html('').append(`
				<tr class="no-data">
		            <td colspan="1">Retrieving data</td>
		        </tr>
			`);
		$.ajax({
			url: "<?= base_url('main/list_ftp/') ?>",
			type: 'POST',
			data: {dir: cur_dir},
			dataType: 'json',
			success: (data) => {
				$('#list_dir').html('');
				let no_data = `
					<tr class="no-data">
			            <td colspan="1">No data</td>
			        </tr>
				`;
				let files = data.files,
					dirs = data.dirs,
					doc_files = '',
					doc_dirs = '';
				files.forEach(function(el, i) {
					doc_files += `
						<tr class="pointer" onClick="downloadFile('${data.dir+'/'+el}')">
				      		<td scope="row">${el}</td>
				    	</tr>
					`;
				});
				if (dirs.length == 0 && files.length == 0) {
					$('#list_dir').append(no_data);
				}
				$('#list_dir').append(doc_files);
			},
		});
	}
	function downloadFile(file_name) {
		var request = $.ajax({
			url: "<?= base_url('main/download_ftp') ?>",
			type: 'POST',
			dataType: "json",
			data: {file_path : file_name},
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