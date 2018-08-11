<?php
require_once APPPATH.'vendor/autoload.php';
use Auth0\SDK\Auth0;

class Auth extends CI_Controller {
		// Hàm khởi tạo
		function __construct() {
				// Gọi đến hàm khởi tạo của cha
				parent::__construct();
        $this->auth0 = new Auth0([
          'domain' => 'ngthuc.auth0.com',
          'client_id' => '3XJ9FwA3T0Kl3FzM4lhSNfSSnykkP78w',
          'client_secret' => '3q4PtzvjwKI5kxzlqP_XqbkPF4whE-n4B90Dq3xpQgAnwG1MOfhAklgqa85AvPom',
          'redirect_uri' => base_url('auth/callback'),
          'audience' => 'https://ngthuc.auth0.com/userinfo',
          'responseType' => 'code',
          'scope' => 'openid email profile',
          'persist_id_token' => true,
          'persist_access_token' => true,
          'persist_refresh_token' => true,
        ]);
		}

		public function index()
		{
        if(!isset($_SESSION['user'])) {
          echo '<a href="'.base_url('auth/login').'">Login</a>';
        } else {
          echo '
          Username: '.$_SESSION['user']['nickname'].'<br />
          Name: '.$_SESSION['user']['name'].'<br />
          <img src="'.$_SESSION['user']['picture'].'" width="150px" /><br />
          Email: '.$_SESSION['user']['email'].'<br />
          <a href="'.base_url('auth/logout').'">Logout</a>
          ';
        }
		}

    public function callback(){
      $userInfo = $this->auth0->getUser();

      if (!$userInfo) {
          // We have no user info
          // redirect to Login
          header('Location: ' . base_url('auth/login'));
      } else {
	        $this->session->set_userdata('user', $userInfo);
	        header('Location: ' . base_url());
      }
    }

    public function login()
		{
        $this->auth0->login();
		}

    public function logout()
		{
        $this->auth0->logout();
        $this->session->unset_userdata('user');	// Unset session of user
        header('Location: ' . base_url());
		}
}
