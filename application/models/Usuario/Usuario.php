<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Usuario extends CI_Model {

    /**
     * autenticar Method Description
     * @param text $username
     * @param text $password
     * @return boolean
     */
    public function autenticar($username, $password) {
        
        $data = $this->db->select("id,name,password,email,first_name,avatar,logado,ativo,")
                ->get_where('usuario', ['username' => $username, 'password' => $password])->row();;

              return $data;
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
    
    public function buscaIdUsuario($user_id){
       $usuario= $this->db->select('email, first_name, last_name, division, avatar')
                ->get_where('users',['id' => $user_id]);
        
    }
}


