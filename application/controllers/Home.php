<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index()
	{
		$page_data['pg_title'] = "Home";
		$this->load->view('landing/home', $page_data);
	}
	public function faq()
	{
		$page_data['pg_title'] = "F A Q";
		$this->load->view('landing/faq', $page_data);
	}
	public function contact()
	{
		$page_data['pg_title'] = "Contact";
		$this->load->view('landing/contact', $page_data);
	}
	public function about()
	{
		$page_data['pg_title'] = "About";
		$this->load->view('landing/about', $page_data);
	}
	public function login()
	{
		$page_data['pg_title'] = "Login";
		$this->load->view('landing/login', $page_data);
	}
	public function register()
	{
		$page_data['pg_title'] = "Register";
		$this->load->view('landing/register', $page_data);
	}
	public function recover()
	{
		$page_data['pg_title'] = "Recover";
		$this->load->view('landing/recover', $page_data);
	}
}
