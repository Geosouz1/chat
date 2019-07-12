<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Usuario extends CI_Model {

    /**
     * autenticar Method Description
     * @param text $username
     * @param text $password
     * @return boolean
     */
 
    
    public function buscaIdUsuario($user_id){
       $usuario= $this->db->select('email, first_name, last_name, avatar')
                ->get_where('usuario',['id' => $user_id]);
        
    }
}


