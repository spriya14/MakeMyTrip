<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {


	public function index()
	{
        $data['temp'] = 0;
		$this->load->view('header',$data);
		$this->load->view('index');
		$this->load->view('footer');
	}
    public function register(){
            $this->load->model('userModel');
            $result = $this->userModel->register();
            $data['temp'] = 0;
            $this->load->view('header',$data);
            $this->load->view('index');
            $this->load->view('footer');
    }

	public function signin(){  
            $this->load->model('userModel');
            if($this->session->userdata('currently_logged_in')){
                echo "already loggedin";  
                $url = 'App/home';
                echo'
                    <script>
                        window.location.href = "'.base_url().'index.php?/'.$url.'";
                    </script>';
            }
            else {
                $res = $this->userModel->userLogin();
                if($res->num_rows() > 0){
                    $email = "";
                    foreach($res->result_array() as $row){
                       $email = $row['emailid'];
                    }
                    $data = array(  
                        'username' => $this->input->post('username'),
                        'email' => $email,  
                        'currently_logged_in' => 1  
                    );
                    $this->session->set_userdata($data);  
                    $url = 'App/home';
                    echo'
                        <script>
                            window.location.href = "'.base_url().'index.php?/'.$url.'";
                        </script>';
                }
                else{
                    $temp = array('login_error' => 'yes');
                    $this->session->set_userdata($temp);
                    $url = 'App/signin';
                    echo'
                        <script>
                            window.location.href = "'.base_url().'index.php?/'.$url.'";
                        </script>';
                }
            }
        }
        
        public function logout(){
            $this->session->sess_destroy();
             $data['temp'] = 0;
            $this->load->view('header',$data);
            $this->load->view('index');
            $this->load->view('footer');
        }
        public function home(){
            $this->load->model('userModel');
            $data ['username'] = $this->session->userdata('username');
            $data['temp'] = 1;
            $data['temp1'] = "";
            $data['message'] = "";
            $data['cart'] = $this->userModel->cartItems($data ['username']);
        	$this->load->view('header',$data);
        	$this->load->view('rides',$data);
        	$this->load->view('footer');
        }
        public function searchRides(){
            $this->load->model('userModel');
            $data = array();
            $data ['username'] = $this->session->userdata('username');
            $data['temp'] = 1;
            $data['temp1'] = "";
            $data['message'] = "";
            $data['cart'] = $this->userModel->cartItems($data ['username']);
            if(isset($_POST['search'])){
                $result = $this->userModel->searchRides();
                if(sizeof($result) > 0){
                    $data['details'] = $result;
                    $data['message'] = "";
                    $data['temp1'] = 1;
                }
                else{
                    $data['details'] = array();
                    $data['message'] = "No Rides";
                    $data['temp1'] = 0;
                }
            }
            $this->load->view('header',$data);
            $this->load->view('rides',$data);
            $this->load->view('footer');
        }

        public function addCart(){
            $this->load->model('userModel');
            $data ['username'] = $this->session->userdata('username');
            $result = $this->userModel->addToCart();
            if($result != 0){
                return 1;
            }
            else{
                return 0;
            }
        }
        public function deleteCart(){
            $this->load->model('userModel');
            $data ['username'] = $this->session->userdata('username');
            $result = $this->userModel->deleteFromCart($data['username']);
            if($result){
                return 1;
            }
            else{
                return 0;
            }
        }
        public function checkOut(){
            $this->load->model('userModel');
            $data ['username'] = $this->session->userdata('username');
            $data['temp'] = 1;
            $data['cart'] = $this->userModel->cartItems($data ['username']);
            $this->load->view('header',$data);
            $this->load->view('checkout',$data);
            $this->load->view('footer');
        }
        public function confirmPay(){
            $this->load->model('userModel');
            $data['temp'] = 1;
            $email = $this->session->userdata('email');
            $data ['username'] = $this->session->userdata('username');
            $data['cart'] = $this->userModel->cartItems($data ['username']);
            $result = $this->userModel->confirmPayment($data['username']);
            $data['details'] = $this->userModel->bookings($data ['username']);
            $message = "Hello ".$data['username']."<br>"."<br>"."Thank you for booking with Make My Trip, Hope you enjoyed our service"."<br>"."<br>"."Thank you"."<br>"."Make My Trip Team.";
            $this->load->library('email');
            $this->email->from('surabhi.priya2514@gmail.com'); // change it to yours
            $this->email->to($email);// change it to yours
            $this->email->subject('Make My Trip');
            $this->email->message($message);
            if($this->email->send()){
               $this->load->view('header',$data);
               $this->load->view('bookings',$data);
               $this->load->view('footer');
            }
            else{
                echo "Something went wrong";
            }
            
        }
         public function bookings(){
            $this->load->model('userModel');
            $data['temp'] = 1;
            $data ['username'] = $this->session->userdata('username');
            $data['cart'] = $this->userModel->cartItems($data ['username']);
            $data['details'] = $this->userModel->bookings($data ['username']);
            $this->load->view('header',$data);
            $this->load->view('bookings',$data);
            $this->load->view('footer');
        }

        public function parking(){
            $this->load->model('userModel');
            $data['temp'] = 1;
            $data ['username'] = $this->session->userdata('username');
            $data['cart'] = $this->userModel->cartItems($data ['username']);
            $data['details'] = $this->userModel->parking($data ['username']);
            $this->load->view('header',$data);
            $this->load->view('parking',$data);
            $this->load->view('footer');
        }
    
        public function cars(){
            $this->load->model('userModel');
            $data['temp'] = 1;
            $data['temp1'] = "";
            $data['message'] = "";
            $data ['username'] = $this->session->userdata('username');
            $data['cart'] = $this->userModel->cartItems($data ['username']);
            $data['details'] = $this->userModel->bookings($data ['username']);
            $this->load->view('header',$data);
            $this->load->view('cars',$data);
            $this->load->view('footer');
        }

        public function pcheckOut(){
            $this->load->model('userModel');
            $data ['username'] = $this->session->userdata('username');
            $data['temp'] = 1;
            $result = $this->userModel->pcheck($data['username']);
            $data['cart'] = $this->userModel->cartItems($data ['username']);
            if($result != 0){
                $this->load->view('header',$data);
                $this->load->view('checkout',$data);
                $this->load->view('footer');
            }
        }

        public function searchCars(){
            $this->load->model('userModel');
            $data = array();
            $data ['username'] = $this->session->userdata('username');
            $data['temp'] = 1;
            $data['temp1'] = "";
            $data['message'] = "";
            $data['cart'] = $this->userModel->cartItems($data ['username']);
            if(isset($_POST['search'])){
                $result = $this->userModel->searchCars();
                if(sizeof($result) > 0){
                    $data['details'] = $result;
                    $data['message'] = "";
                    $data['temp1'] = 1;
                }
                else{
                    $data['details'] = array();
                    $data['message'] = "No Cars";
                    $data['temp1'] = 0;
                }
            }
            $this->load->view('header',$data);
            $this->load->view('cars',$data);
            $this->load->view('footer');
        }

}
