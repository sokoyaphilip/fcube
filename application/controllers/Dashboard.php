<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if( $this->session->userdata('logged_in') ){
            if( $this->session->userdata('is_admin') == 1 ){
                redirect('admin');
            }
        }else{
            redirect('login');
        }
    }

	public function index()
	{
		$page_data['pg_title'] = "Welcome to your Dashboard";
		$page_data['pg_name'] = "dashboard";
		$id = $this->session->userdata('logged_id');
        $page_data['products'] = $this->site->get_result('products');
        $query = "SELECT * FROM transactions WHERE user_id = {$id} ORDER BY id DESC ";
        $start = $end = $transaction ='';
        if( $this->input->post() ){
            // start empty
            $start = $this->input->post('start');
            if( empty( $start) || !isset($start) ){
                $start = $_POST['start'] = date('Y-m-d', strtotime('first day of this year'));
            }else{
                $start = date('Y-m-d', strtotime($start));
            }
            $end = $this->input->post('end');
            if( empty( $end) || !isset($end) ){
                $end = $_POST['end'] = date('Y-m-d', strtotime('tomorrow'));
            }else{
                $end = date('Y-m-d', strtotime($end));
            }

            $query = "SELECT * FROM transactions WHERE date_initiated BETWEEN '{$start}' AND '{$end}' AND user_id ='{$id}'";

            if( $this->input->post('transaction_type') ){
                $transaction = $this->input->post('transaction_type');
                $query .= " AND product_id = '{$transaction}' ORDER BY id DESC";
            }
        }
        $page_data['transactions'] = $this->site->run_sql( $query )->result();
		$this->load->view('user/dashboard', $page_data);
	}


	public function my_wallet()
	{
		$page_data['pg_title'] = "My Wallet";
		$page_data['pg_name'] = "wallet";
		$id = $this->session->userdata('logged_id');
        $page_data['user'] = $this->get_profile( $id );
        $page_data['fundings'] = $this->site->get_result('transactions', '*' , " user_id = {$id}");
        $page_data['transactions'] = $this->site->run_sql("SELECT id, trans_id, amount, description, date_initiated,payment_method, product_id, status FROM transactions WHERE (product_id = 6 or product_id =7) AND user_id = {$id} ORDER BY id DESC LIMIT 10")->result();
		$this->load->view('user/my_wallet', $page_data);
	}
	public function tv_subscription()
	{
        $page_data['pg_name'] = "tv";
        $page_data['pg_title'] = 'Subscribe your GoTV, DSTV, Startimes ... decoder';
        $id = $this->session->userdata('logged_id');
        $page_data['user'] = $this->get_profile($id);
        $page_data['networks'] = $this->site->run_sql("SELECT p.slug, s.id, s.title, network_name, discount FROM products p LEFT JOIN services s ON (p.id = s.product_id) WHERE p.slug ='tv-subscription' ")->result();
        $page_data['transactions'] = $this->site->run_sql("SELECT id, trans_id, amount, description, date_initiated,payment_method, product_id, status FROM transactions WHERE product_id = 3 AND user_id = {$id} ORDER BY id DESC LIMIT 10")->result();
        $this->load->view('user/tv_subscription', $page_data);
	}
	public function data_recharge()
	{
		$page_data['pg_name'] = "data";
        $page_data['pg_title'] = 'Buy Mtn, Glo, 9mobile, Airtel Data Subscription, works for all smartphones...';
        $id = $this->session->userdata('logged_id');
        $page_data['user'] = $this->get_profile($id);
        $page_data['networks'] = $this->site->run_sql("SELECT p.slug, s.id, s.title, s.network_name, discount FROM products p LEFT JOIN services s ON (p.id = s.product_id) WHERE p.title ='data' ")->result();
        $page_data['transactions'] = $this->site->run_sql("SELECT id, trans_id, amount, description, date_initiated,payment_method, product_id, status FROM transactions WHERE product_id = 1 AND user_id = {$id} ORDER BY date_initiated DESC LIMIT 10")->result();
		$this->load->view('user/data_recharge', $page_data);
	}

	public function airtime_recharge()
	{
		$page_data['pg_title'] = "Airtime Recharge";
		$page_data['pg_name'] = "airtime";
        $page_data['title'] = 'Buy Mtn, Glo, 9mobile, Airtel Airtime';
        $id = $this->session->userdata('logged_id');
        $page_data['user'] = $this->get_profile($id);
        $page_data['networks'] = $this->site->run_sql("SELECT p.slug, s.id, s.title, network_name, discount FROM products p LEFT JOIN services s ON (p.id = s.product_id) WHERE p.title ='airtime' ")->result();
        $page_data['transactions'] = $this->site->run_sql("SELECT id, trans_id, amount, description, date_initiated,payment_method, product_id, status FROM transactions WHERE product_id = 2 AND user_id = {$id} ORDER BY id DESC LIMIT 10")->result();
		$this->load->view('user/airtime_recharge', $page_data);
	}

	public function electric_bill()
	{
		$page_data['pg_name'] = "electric";
        $page_data['pg_title'] = 'Pay your electricity bill';
        $id = $this->session->userdata('logged_id');
        $page_data['user'] = $this->get_profile($id);
        $page_data['plans'] = $this->site->run_sql("SELECT s.id service_id, network_name, discount, pl.id, pl.name, api.variation_name
        FROM products p 
        LEFT JOIN services s ON (p.id = s.product_id) 
        JOIN plans pl ON (pl.sid = s.id)
        LEFT JOIN api_variation api ON (api.plan_id = pl.id)
        WHERE p.slug ='electricity-bill' ")->result();
        $page_data['transactions'] = $this->site->run_sql("SELECT id, trans_id, amount, description, date_initiated,payment_method, product_id, status FROM transactions WHERE product_id = 4 AND user_id = {$id} ORDER BY id DESC LIMIT 10")->result();
		$this->load->view('user/electric_bill', $page_data);
	}


    // Profile
    public function profile(){
        $id = $this->session->userdata('logged_id');
        $page_data['page'] = 'profile';
        $page_data['title'] = "Proile Setting";
        $page_data['user'] = $this->site->run_sql("SELECT name, phone, email,user_code,wallet, account_name, account_type, bank_name FROM users WHERE id = {$id}")->row();
        $this->load->view('app/users/profile', $page_data);
    }

    function profile_setting(){
        $action_type = $this->input->post('post_type');
        $uid = $this->session->userdata('logged_id');
        switch ( $action_type ){
            case 'account':
                $this->form_validation->set_rules('name', 'Full name','trim|required|xss_clean|min_length[3]|max_length[50]');
                $this->form_validation->set_rules('account_name', 'Account name','trim|required|xss_clean|max_length[50]');
                $this->form_validation->set_rules('account_type', 'Account type','trim|required|xss_clean');
                $this->form_validation->set_rules('bank_name', 'Bank name','trim|required|xss_clean');
                if( $this->form_validation->run() == false ){
                    $this->session->set_flashdata('error_msg', validation_errors());
                    redirect($_SERVER['HTTP_REFERER']);
                }else{
                    $password = cleanit($_POST['confirm_password']);
                    if(!$this->user->cur_pass_match($password, $uid, 'users')){
                        $this->session->set_flashdata('error_msg', "Oops! The password does not match your current password.");
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                    $data = array(
                        'name' => cleanit($_POST['name']),
                        'account_name' => cleanit($_POST['account_name']),
                        'account_type' => cleanit($_POST['account_type']),
                        'bank_name' => cleanit($_POST['bank_name']),
                    );

                    if( $this->site->update('users', $data, "(id = {$uid})")){
                        $this->session->set_flashdata('success_msg', "Profile updated successfully.");
                    }else{
                        $this->session->set_flashdata('error_msg', "There was an error updating your profile.");
                    }
                    redirect($_SERVER['HTTP_REFERER']);
                }
                break;
            case 'password_change':

                $this->form_validation->set_rules('current_password', 'Password','trim|required|xss_clean');
                $this->form_validation->set_rules('new_password', 'New Password','trim|required|xss_clean');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password','trim|required|xss_clean|min_length[6]|max_length[15]|matches[new_password]');

                $password = cleanit($_POST['current_password']);
                if(!$this->user->cur_pass_match($password, $uid, 'users')){
                    $this->session->set_flashdata('error_msg', "Oops! The password does not match your current password. ");
                    redirect($_SERVER['HTTP_REFERER']);
                }
                $new_password = cleanit( $_POST['new_password'] );
                if( $this->user->change_password( $new_password, $uid, 'users')){
                    $this->session->set_flashdata('success_msg', "Password changed successfully.");
                }else{
                    $this->session->set_flashdata('error_msg', "There was an error updating your password.");
                }
                redirect($_SERVER['HTTP_REFERER']);
        }

    }

    public function payment_made(){
        $tid = cleanit( $this->input->get('tid', true));
        if( !$tid ) redirect( $_SERVER['HTTP_REFERER']);

        $row = $this->site->run_sql("SELECT amount, trans_id, description FROM transactions WHERE trans_id = {$tid}")->row();
        if( !$row ){
            $this->session->set_flashdata('error_msg', "The transaction does not exist.");
            redirect( 'dashboard/wallet/');
        }else{
            $page_data['row'] = $row;
            $page_data['page'] = 'payment_made';
            $page_data['title'] = "Payment Made";
            $page_data['user'] = $this->get_profile($this->session->userdata('logged_id'));
            $this->load->view('app/users/payment_made', $page_data);
        }
    }

    function payment_made_process(){
        if( $this->input->post()){
            $data = array(
                'tid' => cleanit( $_POST['tid']),
                'bank_name' => cleanit($_POST['bank_name']),
                'amount_paid'   => cleanit($_POST['amount_paid']),
                'deposit_type'  => cleanit($_POST['deposit_type']),
                'remark'        => cleanit($_POST['remark']),
                'date_paid'     => date('Y/m/d', strtotime(cleanit($_POST['date_paid'])))
            );

            $tid = cleanit($_POST['tid']);
            $check = $this->site->run_sql("SELECT id FROM transaction_status WHERE tid = {$tid}")->row();
            if($check){
                $this->session->set_flashdata('error_msg', "We already receive your message.");
                redirect( $_SERVER['HTTP_REFERER']);
            }
            if( $this->site->insert_data('transaction_status', $data)){
                $amount = $this->input->post('amount_paid');
                $array['message'] = 'A user just claimed to pay N'.$amount .' Go to dashboard to confirm.';
                $this->callSMSAPI($array);
                $this->session->set_flashdata('success_msg', "Your request has been received, we'll treat it as soon as possible.");
                redirect('dashboard/wallet/');
            }else{
                $this->session->set_flashdata('error_msg', "There was an error processing your request.");
                redirect( $_SERVER['HTTP_REFERER']);
            }
        }
    }
    private function _submitGet( $data ){
        $post_url = $data['url'];
        unset($data['url']);
        $ponmo = http_build_query($data);
        $url = $post_url .'?'. $ponmo; // json
        $headers = array(
            "GET /HTTP/1.1",
            "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1",
            "Accept: */* ",
            "Accept-Language: en-us,en;q=0.5",
            "Keep-Alive: 300",
            "Connection: keep-alive"
        );
        if( ini_get('allow_url_fopen') ) {
            $response = file_get_contents($url);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POST, false);
            $response = curl_exec($ch);
            $response = json_decode($response, TRUE);
            curl_close($ch);
        }
        return $response;
    }
    public function callSMSAPI( $data ){
        $getResponse = $this->_submitGet(
            array(
                'url'   => "https://www.nellobytesystems.com/APIBuyBulkSMS.asp",
                'UserID' => CK_USER_ID,
                'APIKey' => CK_KEY,
                'Sender' => 'Gecharl',
                'Recipient' => '08066795128',
                'Message' => $data['message']
//                08151148607
            )
        );
        return json_decode($getResponse, true);
    }


    function get_profile($id){
        return $this->site->run_sql("SELECT phone, email, name, user_code, wallet, account_type FROM users where id = {$id}")->row();
    }
}
