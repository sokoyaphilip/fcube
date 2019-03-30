<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if( $this->session->userdata('logged_in') ){

            if( $this->session->userdata('is_admin') == 0 ){
                redirect('dashboard');
            }elseif ( $this->session->userdata('is_admin') == 1 ){
                redirect('admin');
            }else{
                redirect(base_url());
            }
        }
    }

	public function index(){
		redirect('auth/login/');
	}


	public function login(){
        $page_data['pg_title'] = "Login";
	    $page_data['page'] = 'login';

	    if( $_POST ){
            $this->form_validation->set_rules('username', 'Login Username','trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password','trim|required|xss_clean|min_length[6]|max_length[15]');

            if( $this->form_validation->run() == false ){
                // error
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect('auth/login/');
            }else{
                // Lets log this shit in
                $data = array(
                    'login_username' => $this->input->post('username', true),
                    'password' => $this->input->post('password', true)
                );
                $user = $this->user->login($data);
                if( !$user ) {
                    $this->session->set_flashdata('error_msg','Sorry! Incorrect login username or password');
                    redirect('auth/login/');
                }else{
                    $session_data = array('logged_in' => true, 'logged_id' => $user->id,
                        'login_username' => $this->input->post('username'),
                        'wallet' => $user->wallet,
                        'email' => $user->email,
                        'is_admin' => $user->is_admin);
                    $this->session->set_userdata($session_data);
                    $this->session->set_flashdata('success_msg', "Logged in...");
                    redirect('dashboard');
                }
            }
        }
	    $this->load->view('landing/login', $page_data);
    }

    public function create(){
        $page_data['page'] = 'login';
        $page_data['pg_title'] = "Create an account with us";

        if( $_POST ){
            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|xss_clean|valid_email|is_unique[users.email]', array('is_unique' => 'This %s has already been registered!'));
            $this->form_validation->set_rules('phone', 'Phone number','trim|required|xss_clean|is_unique[users.phone]', array('is_unique' => 'This %s has already been registered!'));
            $this->form_validation->set_rules('name', 'Full name','trim|required|xss_clean|min_length[3]|max_length[55]');
            $this->form_validation->set_rules('password', 'Password','trim|required|xss_clean|min_length[6]|max_length[15]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password','trim|required|xss_clean|min_length[6]|max_length[15]|matches[password]');

            if( $this->form_validation->run() == false ){
                // error
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect('auth/create/');
            }else{
                $salt = salt(50);
                $code = $this->user->generate_user_code();
                $data = array(
                    'email' => $this->input->post('email', true),
                    'phone' => $this->input->post('phone', true),
                    'name' => $this->input->post('name', true),
                    'salt' => $salt,
                    'password' => shaPassword($this->input->post('password'), $salt),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'date_registered' => get_now(),
                    'last_login' => get_now(),
                    'user_code' => $code
                );

                $user = $this->user->create_account($data);
                if( !is_numeric($user) ) {
                    $this->session->set_flashdata('error_msg', "Sorry! There was an error creating the account. Try again later.");
                    redirect('auth/create/');
                }else{
                    $login_data = array(
                        'login_username' => $this->input->post('email'),
                        'password'       => $this->input->post('password', true)
                    );
                    $user = $this->user->login($login_data);
                    $session_data = array('logged_in' => true,
                        'logged_id' => $user->id,
                        'email' => $this->input->post('email', true),
                        'login_username' => $this->input->post('name'),
                        'is_admin' => $user->is_admin
                    );

                    $this->session->set_userdata($session_data);
                    $this->session->set_flashdata('success_msg', "Account created successfully.");
                    redirect('auth/create/');
                }
            }
        }
        $this->load->view('landing/create', $page_data);
    }

    // forgot
    public function forgot(){
        $this->load->library('recaptcha');
        if( $this->input->post() ){
            $recaptcha = $this->input->post('g-recaptcha-response');
            $response = $this->recaptcha->verifyResponse($recaptcha);
            if (!isset($response['success']) || $response['success'] !== true) {
                $this->session->set_flashdata('error_msg', 'There was an error validating the captcha, please try again.');
                redirect('auth/forgot/');
            }
            $this->form_validation->set_rules('email', 'Email address','trim|required|xss_clean|valid_email');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors() );
                redirect('auth/forgot/');
            }else{
                // // send mail
                $email = $this->input->post('email');
                // check the email
                $row = $this->site->run_sql("SELECT id, name FROM users WHERE email = '{$email}'")->row();
                if( !$row ){
                    $this->session->set_flashdata('error_msg', "Oops sorry, we can't find your detail in our system");
                    redirect('auth/forgot/');
                }else{
                    $new_password = random_string('alnum', 10);
                    if( $this->site->change_password( $new_password , $row->id) ){
                        // send mail
                        $message = "Hi {$row->name}, \r\n\r\nYou requested to retrieve your password, please find below your new password.\r\n\r\n {$new_password}\r\nAfter login in to your account, you can change it to your preferred password.\r\n\r\nHave a great day.\r\n\r\nBest Regards,\r\n\r\nGecharl.com";

                        $this->email->clear(TRUE);
                        $this->email->set_newline("\r\n");
                        $this->email->from('hello@gecharl.com', 'Gecharl.com');
                        $this->email->to($email);
                        $this->email->subject('Password Retrieve From Gecharl.com');
                        $this->email->message($message);
                        if( $this->email->send()){
                            $this->session->set_flashdata('success_msg', "Congrats, a new password has been sent to your mail.");
                            redirect('auth/login/');
                        }else{
                            $this->session->set_flashdata('error_msg',"There was an error sending the mail...");
                            redirect('auth/forgot/');
                        }
                    }

                }
            }

        }else{
            $page_data['page'] = 'forgot';
            $this->load->view('landing/forgot', $page_data);
        }
    }

}
