<?php


/**
 * @property Usuario $Usuario Usuario
 */

class Principais extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['Dashboard_model', 'Chat_model', 'Usuario/Usuario']);
        $this->dashboard = $this->Dashboard_model;
        $this->chat = $this->Chat_model;
     

        checkSession();
    }

    public function index()
    {
        if ($this->session->userdata('role') == 1) {
            $data['record'] = $this->Usuario->get($this->session->userdata('user_id'));

            $this->template->load('template/principal', 'sistemas/index', $data);
        } else {
            $data['record'] = $this->db->get('users');
            $this->template->load('template/main_template', 'sistemas/admin/index', $data);
        }
    }

    public function post()
    {
        
    }
}
