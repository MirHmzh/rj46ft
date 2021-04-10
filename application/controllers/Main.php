<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('ftp');
		$config['hostname'] = '192.168.2.1';
		$config['username'] = 'admin';
		$config['password'] = 'sasasa12';
		$config['debug']	= 'true';
		$this->ftp->connect($config);
	}

	public function index()
	{
		$this->load->view('main');
	}

	public function list_ftp($dir = null)
	{
		$dir = $this->input->post('dir');
		$dir = (($dir == null || $dir == '') ? '/' : $dir);
		$list = $this->ftp->list_files($dir);
		$files = [];
		$dirs = [];
		foreach ($list as $k => $v) {
			if ($v !== "." && $v !== "..") {
				if (array_key_exists('extension', pathinfo($v))) {
					$files[] = $v;
				}else{
					$dirs[] = $v;
				}
			}
		}
		echo json_encode([
			'dir' => $dir,
			'dirs' => $dirs,
			'files' => $files,
		]);
	}

	public function upload_ftp()
	{
		$cur_dir = $this->input->post('cur_dir');
		$config['upload_path'] = './ftp_upload';
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file')){
			$error = array('error' => $this->upload->display_errors());
			echo json_encode($error);
		}
		else{
			$data = $this->upload->data();
			// print_r($data);
			// $ftp = $this->ftp->upload($data['full_path'], $cur_dir.'/'.$data['file_name'], 'ascii');
			$ftp = $this->ftp->upload($data['full_path'], $cur_dir.'/'.$data['file_name'], 'ascii');
			echo json_encode([
				'upload_status' => $data,
				'ftp_status' => [
					'status' => $ftp,
					'path' => $cur_dir == '/' ? $data['file_name'] : $cur_dir.'/'.$data['file_name']
				]
			]);
		}
	}

	public function download_ftp()
	{
		$file_path = $this->input->post('file_path');
		$file_path_arr = explode('/',$file_path);
		$file_name = $file_path_arr[count($file_path_arr)-1];
		header('Content-Disposition: attachment; filename="'.$file_name.'"');
		$ftp = $this->ftp->download($file_path, "ftp_download/$file_name");
		print_r($ftp);
		echo json_encode(['data' => base_url('ftp_download/'.$file_name) ]);
	}

}

/* End of file Main.php */
/* Location: ./application/controllers/Main.php */