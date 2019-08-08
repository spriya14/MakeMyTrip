<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


	public function index()
	{
        $data['temp'] = 0;
		$this->load->view('header',$data);
		$this->load->view('index');
		$this->load->view('footer');
	}

	public function signin(){  
            $this->load->model('loginModel');
            if($this->session->userdata('currently_logged_in')){
                echo "already loggedin";  
                $url = 'Login/home';
                echo'
                    <script>
                        window.location.href = "'.base_url().'index.php?/'.$url.'";
                    </script>';
            }
            else {
                $res = $this->loginModel->userLogin();
                if($res->num_rows() > 0){
                    $data = array(  
                        'username' => $this->input->post('username'),  
                        'currently_logged_in' => 1  
                    );
                    $this->session->set_userdata($data);  
                    $url = 'Login/home';
                    echo'
                        <script>
                            window.location.href = "'.base_url().'index.php?/'.$url.'";
                        </script>';
                }
                else{
                    $temp = array('login_error' => 'yes');
                    $this->session->set_userdata($temp);
                    $url = 'Login/signin';
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
            $this->load->model('loginModel');
            $data ['username'] = $this->session->userdata('username');
            $data['temp'] = 1;
            $data['temp1'] = "";
            $data['message'] = "";
            $data['cart'] = $this->loginModel->cartItems($data ['username']);
        	$this->load->view('header',$data);
        	$this->load->view('rides',$data);
        	$this->load->view('footer');
        }
        public function searchRides(){
            $this->load->model('loginModel');
            $data = array();
            $data ['username'] = $this->session->userdata('username');
            $data['temp'] = 1;
            $data['temp1'] = "";
            $data['message'] = "";
            $data['cart'] = $this->loginModel->cartItems($data ['username']);
            if(isset($_POST['search'])){
                $result = $this->loginModel->searchRides();
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
            $this->load->model('loginModel');
            $data ['username'] = $this->session->userdata('username');
            $result = $this->loginModel->addToCart();
            if($result != 0){
                return 1;
            }
            else{
                return 0;
            }
        }
        public function deleteCart(){
            $this->load->model('loginModel');
            $data ['username'] = $this->session->userdata('username');
            $result = $this->loginModel->deleteFromCart($data['username']);
            if($result){
                return 1;
            }
            else{
                return 0;
            }
        }
        public function checkOut(){
            $this->load->model('loginModel');
            $data ['username'] = $this->session->userdata('username');
            $data['temp'] = 1;
            $data['cart'] = $this->loginModel->cartItems($data ['username']);
            $this->load->view('header',$data);
            $this->load->view('checkout',$data);
            $this->load->view('footer');
        }
        public function confirmPay(){
            $this->load->model('loginModel');
            $data['temp'] = 1;
            $data ['username'] = $this->session->userdata('username');
            $data['cart'] = $this->loginModel->cartItems($data ['username']);
            $result = $this->loginModel->confirmPayment($data['username']);
            $data['details'] = $this->loginModel->bookings($data ['username']);
            $this->load->view('header',$data);
            $this->load->view('bookings',$data);
            $this->load->view('footer');
        }
         public function bookings(){
            $this->load->model('loginModel');
            $data['temp'] = 1;
            $data ['username'] = $this->session->userdata('username');
            $data['cart'] = $this->loginModel->cartItems($data ['username']);
            $data['details'] = $this->loginModel->bookings($data ['username']);
            $this->load->view('header',$data);
            $this->load->view('bookings',$data);
            $this->load->view('footer');
        }
    
    public function parking(){
            $this->load->model('loginModel');
            $data['temp'] = 1;
            $data ['username'] = $this->session->userdata('username');
            $data['cart'] = $this->loginModel->cartItems($data ['username']);
            $data['details'] = $this->loginModel->bookings($data ['username']);
            $this->load->view('header',$data);
            $this->load->view('parking',$data);
            $this->load->view('footer');
        }
    
      public function cars(){
            $this->load->model('loginModel');
            $data['temp'] = 1;
            $data ['username'] = $this->session->userdata('username');
            $data['cart'] = $this->loginModel->cartItems($data ['username']);
            $data['details'] = $this->loginModel->bookings($data ['username']);
            $this->load->view('header',$data);
            $this->load->view('cars',$data);
            $this->load->view('footer');
        }
}
