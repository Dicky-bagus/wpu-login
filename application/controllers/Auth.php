<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('email');
	}

	public function index()
	{
		if ($this->session->userdata('email')) {
			if ($this->session->userdata('role_id') == 1) {
				redirect('admin');
			} else if ($this->session->userdata('role_id') == 2) {
				redirect('user');
			}
		}
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		//TODO : valid_email agar user mengisi email yang valid
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == false) {
			$data['title'] = "WPU ";
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/login');
			$this->load->view('templates/auth_footer');
		} else {
			//TODO : Jika validasi sukses
			$this->_login();
		}
	}

	//TODO : method _login, dikasih underscore sebelum kalimat login untuk memberi tahu kalau fungsi ini private
	private function _login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$user = $this->db->get_where('user', ['email' => $email])->row_array();

		//TODO : jika usernya ada
		if ($user) {
			//TODO : jika usernya aktif
			if ($user['is_active'] == 1) {
				// TODO : cek password
				if (password_verify($password, $user['password'])) {
					// TODO : jika password benar
					$data = [
						'id_user' => $user['id'],
						'email' => $user['email'],
						'role_id' => $user['role_id']
					];

					$this->session->set_userdata($data);
					//TODO : jika role_id == 1 maka user masuk sebagai admin
					if ($user['role_id'] == 1) {
						redirect('admin');
					} else {
						//TODO : tapi jika role_id != 1 maka user masuk sebagai member
						redirect('user');
					}
				} else {
					// tapi jika password tidak ada maka tampilkan alert
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Password salah!</div>');
					redirect('auth');
				}
			} else {
				// jika usernya tidak aktif
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akunmu belum aktif!,periksa email untuk aktivasi!</div>');
				redirect('auth');
			}
		} else {
			// jika usernya tidak ada
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email belum terdaftar!</div>');
			redirect('auth');
		}
	}

	public function registration()
	{
		if ($this->session->userdata('email')) {
			if ($this->session->userdata('role_id') == 1) {
				redirect('admin');
			} else if ($this->session->userdata('role_id') == 2) {
				redirect('user');
			}
		}
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		//TODO : 'required : inputannya tidak boleh kosong, trim : tidak boleh ada spasi di belakang kata
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
			'required' => 'Email tidak boleh kosong!',
			'valid_email' => 'Email tidak valid!',
			'is_unique' => 'Email telah terdaftar!'
			//TODO :is_unique : untuk mengecek email di database,apakah ada email yang sama(terdaftar) di database
		]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
			'matches' => 'Kata sandi tidak sama!', //TODO :matches : untuk menyamakan pada input password 2
			'min_length' => 'Kata sandi terlalu pendek!', //TODO : minimal kata pada kata sandi
			'required' => 'Kata sandi tidak boleh kosong!',
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

		if ($this->form_validation->run() == false) {
			$data['title'] = "WPU User Registration";
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/registration');
			$this->load->view('templates/auth_footer');
		} else {
			$email = $this->input->post('email', true);
			$data = [

				'name' => htmlspecialchars($this->input->post('name', true)),
				'email' => htmlspecialchars($email),
				'image' => 'default.jpg',
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'role_id' => 2,
				'is_active' => 0,
				'date_created' => time(),

			];

			//TODO: siapkan token
			$token = base64_encode(random_bytes(32));
			$user_token = [
				'email' => $email,
				'token' => $token,
				'date_created' => time()
			];


			$this->db->insert('user', $data);
			$this->db->insert('user_token', $user_token);

			//TODO : config kirim email
			$this->_sendEmail($token, 'verify');

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Selamat!,akunmu telah terdaftar.Cek email dan Aktivasi!</div>');
			redirect('auth');
		}
	}

	private function _sendEmail($token, $type)
	{
		$from = 'suratonline993@gmail.com';
		$config = [
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => $from,
			'smtp_pass' => 'otaku993',
			'charset' => 'iso-8859-1',
		];


		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->set_mailtype('html');

		$this->email->from($from, 'Surat Online');
		$this->email->to($this->input->post('email'));

		if ($type == 'verify') {
			$this->email->subject('Account Verification');
			$this->email->message('Click this link to activated your account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
		} else if ($type == 'forgot') {
			$this->email->subject('Reset Password');
			$this->email->message('Click this link to reset your password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
		}

		if ($this->email->send()) {
			return true;
		} else {
			echo $this->email->print_debugger();
			die;
		}
	}

	public function verify()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		if ($user) {
			$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
			if ($user_token) {
				if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
					$this->db->set('is_active', 1);
					$this->db->where('email', $email);
					$this->db->update('user');

					$this->db->delete('user_token', ['email' => $email]);
					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $email . ' telah diaktivasi!,mohon login.</div>');
					redirect('auth');
				} else {
					$this->db->delete('user', ['email' => $email]);
					$this->db->delete('user_token', ['email' => $email]);
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Aktivasi akun gagal,token kadaluarsa.</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Aktivasi akun gagal,token salah.</div>');
				redirect('auth');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Aktivasi akun gagal, email belum terdaftar.</div>');
			redirect('auth');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('id_user');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Anda berhasil logout!</div>');
		redirect('auth');
	}
	public function blocked()
	{
		$data['title'] = "Blocked Page";
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('auth/blocked', $data);
		$this->load->view('templates/footer');
	}

	public function forgotPassword()
	{
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		if ($this->form_validation->run() == false) {

			$data['title'] = 'Forgot Password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/forgot-password');
			$this->load->view('templates/auth_footer');
		} else {
			$email = $this->input->post('email');
			$user = $this->db->get_where('user', ['email' => $email])->row_array();
			if ($user) {
				if ($user['is_active'] == 1) {
					$token = base64_encode(random_bytes(32));
					$user_token = [
						'email' => $email,
						'token' => $token,
						'date_created' => time()
					];

					$this->db->insert('user_token', $user_token);
					$this->_sendEmail($token, 'forgot');

					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Mohon cek email anda untuk mereset password.</div>');
					redirect('auth/forgotPassword');
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email belum diaktivasi, aktivasi melalui email anda.</div>');
					redirect('auth/forgotPassword');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email belum terdaftar.</div>');
				redirect('auth/forgotPassword');
			}
		}
	}

	public function resetPassword()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');
		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		if ($user) {
			$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
			if ($user_token) {
				$this->session->set_userdata('reset_email', $email); //TODO : memasukkan $email ke dalam session 
				$this->changePassword(); //TODO: method change password 
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Token anda salah.</div>');
				redirect('auth/forgotPassword');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email anda salah.</div>');
			redirect('auth/forgotPassword');
		}
	}

	public function changePassword()
	{
		if (!$this->session->userdata('reset_email')) {
			redirect('auth');
		}
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]');
		$this->form_validation->set_rules('password2', 'Repeat password', 'required|trim|min_length[3]|matches[password1]');


		if ($this->form_validation->run() == false) {
			$data['title'] = 'Change Password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/change-password');
			$this->load->view('templates/auth_footer');
		} else {
			$password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
			$password_verify = $this->input->post('password1');
			$email = $this->session->userdata('reset_email');
			$user = $this->db->get_where('user', ['email' => $email])->row_array();
			if (!password_verify($password_verify, $user['password'])) {
				$this->db->set('password', $password);
				$this->db->where('email', $email);
				$this->db->update('user');

				$this->db->delete('user_token', ['email' => $email]);
				$this->session->unset_userdata('reset_email');
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password berhasil diubah! Please login.</div>');
				redirect('auth');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password baru tidak boleh sama dengan password lama.</div>');
				redirect('auth/changePassword');
			}
		}
	}
}
