<?php


/**
 * @property Usuario $Usuario Usuario
 */

class Logins extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model(['Usuario/Usuario']);
    }


    public function index() {
        if (isset($_POST['submit'])) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            /**
             *  Verify process and storing user_id to session
             */
            $verify = $this->Usuario->verify($username, $password);

            if ($verify == 1) {
                /* Set session of status login if success */
                $this->session->set_userdata('login_status', 'ok');

                /* Update column = ['is_logged_in', 'last_login'] */
                $this->Usuario->logged($this->session->userdata('user_id'));

                redirect('principal');
            } elseif ($verify == 2) {
                $this->session->set_userdata('error', '<b><span style class="alert alert-warning col-sm-12" '
                    . 'role="alert">Peça ao administrador para verificar sua conta!</span></b>');
                redirect('login');
            } else {
                /* Destory session if failed */
                // $this->session->sess_destroy();
                $this->session->set_userdata(['error' => '<b><span style class="alert alert-danger col-sm-12" '
                    . 'role="alert">Erro !! Nome de usuário e senha inválidos</span></b>']);
                redirect('login');
            }
        } elseif (isset($_POST['submit_register'])) {
            redirect('registro');
        } else {
            $data['error'] = $this->session->userdata('error');

            $this->template->load('template/login_template', 'logins/logins', $data);
          
        }
    }

    public function registro() {
        if (isset($_POST['submit'])) {
            $data['first_name'] = $this->input->post('first_name');
            $data['last_name'] = $this->input->post('last_name');
            $data['division'] = $this->input->post('division');
            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');
            $data['avatar'] = 'default.jpeg';
            
            $this->db->trans_begin();
            $this->db->insert('users', $data);

            redirect('login');
        } else {
            $this->template->load('template/login_template', 'register/index');
        }
    }

    public function logout() {
        $this->db->where('id', $this->session->userdata('user_id'));
        $this->db->update('users', ['is_logged_in' => 0]);

        $this->session->sess_destroy();

        redirect('login');
    }

}
