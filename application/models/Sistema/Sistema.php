<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sistema extends CI_Model {


    public function autenticar($username, $password) {
        
        $usuario = $this->db->select("id,username,password,email,first_name,avatar,logado,ativo,")
                ->get_where('usuario', ['username' => $username, 'password' => $password])->row();
        
if(!empty($usuario)){
    $this->session->set_userdata([
        'user_id' => $usuario->id,
        'first_name' => $usuario->first_name,
        'avatar' => $usuario->avatar
    ]); 
}

              return $data;
    }
    
       public function get($user_id)
    {
        $this->db->select();
        $this->db->from('usuario as usuario');
        $this->db->where('usuario.id != ', $user_id);
        $this->db->where('usuario.id != 0');
        return $this->db->get();
    }

    public function getOne($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('usuario');
    }

    public function logged($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->update('usuario', ['logado' => 1, 'last_login' => date('Y-m-d')]);

        return 1;
    }
}