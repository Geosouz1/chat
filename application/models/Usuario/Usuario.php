<?php

class Usuario extends CI_Model {

    /**
     * Verify Method Description
     * @param text $username
     * @param text $password
     * @return boolean
     */
    public function verify($username, $password) {
        $user = $this->db->get_where('users', ['username' => $username, 'password' => $password]);

        $data = $user->row_array();
        $user_array = $user->row_array();

        if ($user->num_rows() > 0) {
            if ($user_array['is_activated'] == '1') {
                $this->session->set_userdata([
                    'user_id' => $data['id'],
                    'first_name' => $data['first_name'],
                    'avatar' => $data['avatar'],
                    'role' => $data['role']
                ]);
                return 1;
            } else {
                $this->session->set_userdata('error', 'Peça ao administrador para verificar sua conta!!');
                redirect('auth/login');
            }
        } else {
            return 0;
        }
    }
    
       public function get($user_id)
    {
        $this->db->select();
        $this->db->from('users as users');
        $this->db->where('users.id != ', $user_id);
        $this->db->where('users.id != 0');
        return $this->db->get();
    }

    public function getOne($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('users');
    }

    public function logged($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->update('users', ['is_logged_in' => 1, 'last_login' => date('Y-m-d')]);

        return 1;
    }
}


