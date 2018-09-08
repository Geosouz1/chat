<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration
 *
 * @author alexandre.barboza
 */
class MY_model extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->dbforge();
        if (!$this->db->table_exists("chats")) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'null' => FALSE
                ],
                'topic' => [
                    'type' => 'TEXT',
                    'null' => TRUE
                ]
            ];
            $this->dbforge->add_field($fields)
                    ->add_field("created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP")
                    ->add_key("id", TRUE)
                    ->create_table("chats");
        }

        if (!$this->db->table_exists("chats_details")) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'null' => FALSE
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ],
                'chat_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ]
            ];
            $this->dbforge->add_field($fields)
                    ->add_field("created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP")
                    ->add_key("id", TRUE)
                    ->create_table("chats_details");
        }

        if (!$this->db->table_exists("chats_messages")) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'null' => FALSE
                ],
                'chat_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    "default" => NULL
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    "default" => NULL
                ],
                'content' => [
                    'type' => 'TEXT',
                    'null' => TRUE
                ],
                'is_image' => [
                    'type' => 'SMALLINT',
                    'null' => FALSE,
                    "default" => 0
                ],
                'is_read' => [
                    'type' => 'SMALLINT',
                    'null' => FALSE,
                    "default" => 0
                ],
                'is_doc' => [
                    'type' => 'SMALLINT',
                    'null' => FALSE,
                    "default" => 0
                ]
            ];
            $this->dbforge->add_field($fields)
                    ->add_field("created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP")
                    ->add_key("id", TRUE)
                    ->create_table("chats_messages");
        }

        if (!$this->db->table_exists("dashboard")) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'null' => FALSE
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    "default" => NULL
                ],
                'messages' => [
                    'type' => 'TEXT',
                    'null' => FALSE
                ]
            ];
            $this->dbforge->add_field($fields)
                    ->add_field("created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP")
                    ->add_key("id", TRUE)
                    ->create_table("dashboard");
        }

        if (!$this->db->table_exists("groups_chats")) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'null' => FALSE
                ],
                'chat_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    "default" => NULL
                ],
                'created_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ],
                'total_member' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    "default" => 0
                ]
            ];
            $this->dbforge->add_field($fields)
                    ->add_field("created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP")
                    ->add_key("id", TRUE)
                    ->create_table("groups_chats");
        }

        if (!$this->db->table_exists("groups_members")) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'null' => FALSE
                ],
                'chat_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    "default" => NULL
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    "default" => NULL
                ]
            ];
            $this->dbforge->add_field($fields)
                    ->add_field("created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP")
                    ->add_key("id", TRUE)
                    ->create_table("groups_members");
        }

        if (!$this->db->table_exists("uri_segments")) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'null' => FALSE
                ],
                'first' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => FALSE
                ],
                'second' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => FALSE
                ],
                'chat_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => FALSE
                ]
            ];
            $this->dbforge->add_field($fields)
                    ->add_field("created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP")
                    ->add_key("id", TRUE)
                    ->create_table("uri_segments");
        }

        if (!$this->db->table_exists("users")) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE,
                    'null' => FALSE
                ],
                'username' => [
                    'type' => 'TEXT',
                    'null' => FALSE
                ],
                'password' => [
                    'type' => 'TEXT',
                    'null' => FALSE
                ],
                'role' => [
                    'type' => 'INT',
                    'constraint' => 1,
                    'null' => FALSE,
                    "default" => 1
                ],
                'email' => [
                    'type' => 'TEXT',
                    'null' => FALSE
                ],
                'first_name' => [
                    'type' => 'TEXT',
                    'null' => FALSE
                ],
                'last_name' => [
                    'type' => 'TEXT',
                    'null' => FALSE
                ],
                'division' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => FALSE
                ],
                'avatar' => [
                    'type' => 'TEXT',
                    'null' => FALSE
                ],
                'is_logged_in' => [
                    'type' => 'INT',
                    'constraint' => 1,
                    'null' => TRUE,
                    "default" => 1
                ],
                'is_activated' => [
                    'type' => 'INT',
                    'constraint' => 1,
                    'null' => TRUE,
                    "default" => NULL
                ]
            ];
            $this->dbforge->add_field($fields)
                    ->add_field("last_login TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP")
                    ->add_key("id", TRUE)
                    ->create_table("users");
        }
    }

}
