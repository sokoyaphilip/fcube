<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function index()
	{
		$page_data['pg_title'] = "Dashboard";
		$page_data['pg_name'] = "dashboard";
        $page_data['products'] = $this->site->get_result('products');

        $query = "SELECT t.*, u.phone, u.name FROM transactions t LEFT JOIN users u ON(u.id = t.user_id) ORDER BY id DESC";
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
                $end = $_POST['end'] = date('Y-m-d', strtotime('last day of this year'));
            }else{
                $end = date('Y-m-d', strtotime($end));
            }

            $query = "SELECT t.*, u.phone FROM transactions t LEFT JOIN users u ON(u.id = t.user_id) WHERE date_initiated BETWEEN '{$start}' AND '{$end}'";

            if( $this->input->post('transaction_type') ){
                $transaction = $this->input->post('transaction_type');
                $query .= " AND product_id = {$transaction} ORDER BY id DESC";
            }
        }
        $today = date('Y-m-d', strtotime('today'));

        $page_data['today'] = $this->site->run_sql("SELECT SUM(amount) amount FROM transactions WHERE date_initiated = '$today' AND (status = 'success' OR status = 'approve') ")->row()->amount;

//        die("SELECT SUM(amount) amount FROM transactions WHERE date_initiated = '$today' AND (status = 'success' OR status = 'approve') ");
        $first_day = date('Y-m-d', strtotime('first day of the week'));
        $last_day = date('Y-m-d', strtotime('last day of the week'));
        $page_data['week'] = $this->site->run_sql("SELECT SUM(amount) amount FROM transactions 
        WHERE date_initiated BETWEEN('{$first_day}' AND '{$last_day}') AND (status = 'success' OR status = 'approved' )  ")->row()->amount;

        $first_day = date('Y-m-d', strtotime('first day of the month'));
        $last_day = date('Y-m-d', strtotime('last day of the month'));
        $page_data['month'] = $this->site->run_sql("SELECT SUM(amount) amount FROM transactions 
        WHERE date_initiated BETWEEN('{$first_day}' AND '{$last_day}') AND (status = 'success' OR status = 'approved' ) ")->row()->amount;


        $first_day = date('Y-m-d', strtotime('first day of the year'));
        $last_day = date('Y-m-d', strtotime('last day of the year'));
        $page_data['year'] = $this->site->run_sql("SELECT SUM(amount) amount FROM transactions 
        WHERE date_initiated BETWEEN ('{$first_day}' AND '{$last_day}') AND (status = 'success' OR status = 'approved' )")->row()->amount;


        $page_data['transactions'] = $this->site->run_sql( $query )->result();
		$this->load->view('admin/dashboard', $page_data);
	}

    public function manage_services(){

        if( $this->input->post() ){
            $this->form_validation->set_rules('title', 'Service Name','trim|required|xss_clean');
//            $this->form_validation->set_rules('title', 'Service Name','trim|required|xss_clean|is_unique[services.title]', array('is_unique' => 'This %s has already been registered!'));
//            $this->form_validation->set_rules('network_name', 'Network','trim|required|xss_clean');

            if( $this->form_validation->run() == FALSE ){
                $this->session->set_flashdata('error_msg', validation_errors());
                $_SERVER['HTTP_REFERER'];
            }

            $slug = urlify( $this->input->post('title') );
            $array = array(
                'title' => $this->input->post('title'),
                'product_id' => $this->input->post('product_id'),
                'message'  => $this->input->post('message'),
                'seo'      => $this->input->post('seo'),
                'discount'      => ($this->input->post('discount') == 1) ? 100 : $this->input->post('discount'),
                'discount_type'      => $this->input->post('discount_type'),
                'availability' => $this->input->post('availability'),
                'network_name' => $this->input->post('network_name')
            );

            $array['slug'] = $this->site->check_slug( $slug );

            if( is_numeric($this->site->insert_data('services', $array)) ){
                $plan = '<a href="' .base_url("admin/plans/"). '">Add Plan This Service</a>';
                $this->session->set_flashdata('success_msg', 'The service added successfully .' . $plan);
            }else{
                $this->session->set_flashdata('error_msg', 'There was an error adding the service.');
            }
            redirect( $_SERVER['HTTP_REFERER']);

        }else{

            $page_data['pg_title'] = "Manage Services";
            $page_data['pg_name'] = "services";
            $page_data['products'] = $this->site->get_result('products', 'id, title', 'user_end = 1');
            $page_data['services'] = $this->site->run_sql('SELECT s.*, p.title product_name, p.slug FROM services s LEFT JOIN products p ON (p.id = s.product_id)')->result();
            $this->load->view('admin/manage_services', $page_data);
        }
    }

    // Wallet Funding

	public function wallet_funding()
	{
		$page_data['pg_title'] = "Wallet Funding";
		$page_data['pg_name'] = "wallet";
        if( $this->input->post() ){
            $status = $this->input->post('action', true);
            $id = $this->input->post('txn_id', true);
            $amount = $this->input->post('amount', true);
            $user_id = $this->input->post('user_id', true);

            if( $this->site->update('transactions', array('status' => $status ), array('id' => $id))){
                // Update the balance
                if( $status =='approved'){
                    $this->site->set_field('users', 'wallet', "wallet+{$amount}", "id={$user_id}");
                }
                $this->session->set_flashdata('success_msg', 'Action success');
            }else{
                $this->session->set_flashdata('error_msg', 'There was an errror performing that action.');
            }
            redirect( $_SERVER['HTTP_REFERER']);
        }else{
            $page_data['page'] = 'approval';
            $page_data['fundings'] = $this->site->run_sql("SELECT t.* , u.name name, u.phone, u.email FROM transactions t LEFT JOIN users u ON (u.id = t.user_id) 
        WHERE t.status = 'pending' AND t.product_id = 6")->result();
            $page_data['airtime_to_cash_pin'] = $this->site->get_result('airtime_to_cash', 'id,tid,uid,incoming,outgoing,details,datetime,status,type', "(status = 'pending')");
            $page_data['title'] = "Funding Approval";
            $this->load->view('admin/wallet_funding', $page_data);
        }
	}

	// Airtime to cash

    public function airtime_to_cash()
    {
        $page_data['pg_title'] = "Airtime To Cash";
        $page_data['pg_name'] = "airtime_cash";
        $page_data['airtime_to_cash_pin'] = $this->site->get_result('airtime_to_cash', 'id,tid,uid,incoming,outgoing,details,datetime,status,type', "(status = 'pending')");
        $page_data['title'] = "Funding Approval";
        $this->load->view('admin/airtime_to_cash', $page_data);
    }

	public function manage_plans()
	{
        if( $this->input->post()){
            $plans = $this->input->post('plans', true);
            $sid = $this->input->post('service');
            $explode_plans = explode(',', $plans );
            $plans_array = array();
            $count = count( $explode_plans );
            // explode plans = array(1GB - 4000, 2GB - 5000 ...)
            // Lets get the discount for this service
//            $discount = $this->site->run_sql("SELECT discount FROM services WHERE id = {$sid}")->row()->discount;
            for ($x = 0; $x < $count; $x++){
                $explode = explode( '-',$explode_plans[$x] );
                if( $explode ) { // double check that admin didn't add extra comma (,)
                    $res['sid'] = $sid;
                    $res['name'] = trim(strtoupper($explode[0]));
                    $res['amount'] = null;
                    if( isset( $explode[1]) ) {
                        $res['amount'] = trim($explode[1]);
                        // leave to when it will be processed
//                        if( $discount > 0 ) {
//                            $res['amount'] = trim((int)$explode[1]) - ( $discount / 100 * trim((int)$explode[1]) );
//                        }
                    }
                    array_push( $plans_array, $res );
                }
            }
            // insert batch
            if( $this->site->insert_batch('plans', $plans_array)){
                $this->session->set_flashdata('success_msg', 'The plan has been added to the service');
            }else{
                $this->session->set_flashdata('error_msg', 'The plan has been added to the service');
            }

            redirect( $_SERVER['HTTP_REFERER']);

        }else{
            $query = "SELECT p.*,s.title service_name, s.discount_type FROM plans p LEFT JOIN services s ON(s.id = p.sid) GROUP BY p.sid";
            $id = $this->input->get('id', true);
            $id = cleanit($id);
            $page_data['id_set'] = false;
            if( $id ) {
                $query = "SELECT p.*,s.title service_name, s.discount_type FROM plans p LEFT JOIN services s ON(s.id = p.sid) WHERE p.sid = {$id}";
                $page_data['id_set'] = true;
            }
            $page_data['page'] = 'plans';

            $page_data['pg_title'] = "Manage Plans";
            $page_data['pg_name'] = "plans";
            $page_data['services'] = $this->site->run_sql('SELECT s.id,s.title,s.discount_type, p.title product_name FROM services s LEFT JOIN products p ON (p.id = s.product_id)')->result();
            $page_data['plans'] = $this->site->run_sql($query)->result();

            $this->load->view('admin/manage_plans', $page_data);
        }
	}

	// Manage users
	public function manage_users()
	{
		$page_data['pg_title'] = "Manage Users";
		$page_data['pg_name'] = "users";
        $page_data['users'] = $this->site->get_result('users');
		$this->load->view('admin/manage_users', $page_data);
	}

    public function approval(){

        if( $this->input->post() ){
            $status = $this->input->post('action', true);
            $id = $this->input->post('txn_id', true);
            $amount = $this->input->post('amount', true);
            $user_id = $this->input->post('user_id', true);

            if( $this->site->update('transactions', array('status' => $status ), array('id' => $id))){
                // Update the balance
                if( $status =='approved'){
                    $this->site->set_field('users', 'wallet', "wallet+{$amount}", "id={$user_id}");
                }
                $this->session->set_flashdata('success_msg', 'Action success');
            }else{
                $this->session->set_flashdata('error_msg', 'There was an errror performing that action.');
            }
            redirect( $_SERVER['HTTP_REFERER']);
        }else{
            $page_data['page'] = 'approval';
            $page_data['fundings'] = $this->site->run_sql("SELECT t.* , u.name name, u.phone, u.email FROM transactions t LEFT JOIN users u ON (u.id = t.user_id) 
        WHERE t.status = 'pending' AND t.product_id = 6")->result();
            $page_data['airtime_to_cash_pin'] = $this->site->get_result('airtime_to_cash', 'id,tid,uid,incoming,outgoing,details,datetime,status,type', "(status = 'pending')");
            $page_data['title'] = "Funding Approval";
            $this->load->view('app/admin/approval', $page_data);
        }
    }

    function tocashprocess(){
//        var_dump($_POST);
        $action = $this->input->post('action');
        $txid  = $this->input->post('txn_id');
        $user_id = $this->input->post('user_id');
        $amount = $this->input->post('amount');

        if( $action == 'approve' ){
            $txn_update = array('status' => 'success');
            $airtime_update = array('status' => 'success');
            $this->db->trans_start();
            if( $_POST['transaction_type'] == 'wallet' ){
                $this->site->set_field('users', 'wallet', "wallet+{$amount}", "id={$user_id}");
            }
            $this->site->update('airtime_to_cash', $airtime_update, "( tid = {$txid})");
            $this->site->update('transactions', $txn_update, "( id = {$txid})");

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
                $this->session->set_flashdata('error_msg', 'There was an error processing that request.');
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->session->set_flashdata('success_msg', 'Request successful.');
            }
            redirect( $_SERVER['HTTP_REFERER']);

        }

    }

    public function confirm_payment(){
        $tid = $this->input->get('tid', true);
        if( $tid ){
            $page_data['row'] = $this->site->run_sql("SELECT t.amount, t.id,t.user_id, s.bank_name, s.amount_paid, s.deposit_type, s.remark, s.date_paid FROM transactions t LEFT JOIN transaction_status s ON (s.tid = t.trans_id) 
WHERE t.trans_id = {$tid}")->row();
            $page_data['users'] = $this->site->get_result('users');
            $page_data['title'] = "Confirm Payment";
            $page_data['page'] = 'Confirm Payment';
            $this->load->view('app/admin/confirm_payment', $page_data);
        }
    }

    function user_action(){
        $action = $this->uri->segment(3);
        $user_id = $this->uri->segment(4);
        if( !$action || ! $user_id ){
            $this->session->set_flashdata('error_msg', 'Something is wrong somewhere...');
            redirect( $_SERVER['HTTP_REFERER']);
        }
        if( $action == 'delete' ){
            $this->site->delete("(user_id = {$user_id})", 'transactions');
            $this->site->delete("(id = {$user_id})", 'users');
        }else{
            $this->site->update('users', array('status' => $action), "(id = {$user_id})");
        }
        $this->session->set_flashdata('success_msg', "Action successful");
        redirect( $_SERVER['HTTP_REFERER']);
    }
}
