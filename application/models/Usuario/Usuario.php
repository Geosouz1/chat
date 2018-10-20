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
        
        $data = $this->db->select("id,username,password,email,first_name,avatar,logado,ativo,")
                ->get_where('usuario', ['username' => $username, 'password' => $password])->row();;

              return $data;
    }
    
       public function get($user_id)
    {
        $this->db->select();
        $this->db->from('usuario as users');
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
        $this->db->update('usuario', ['logado' => 1, 'last_login' => date('Y-m-d')]);

        return 1;
    }
    
    public function buscaIdUsuario($user_id){
       $usuario= $this->db->select('email, first_name, last_name, avatar')
                ->get_where('usuario',['id' => $user_id]);
        
    }
}


