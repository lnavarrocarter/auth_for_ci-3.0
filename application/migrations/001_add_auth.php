<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Auth extends CI_Migration {

        public function up() {

                ###############
                # USERS TABLE #
                ###############

                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 6,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'username' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => TRUE,
                        ),
                        'email' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100'
                        ),
                        'password' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '150',
                        ),
                        'salt' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '150',
                                'null' => TRUE,
                        ),
                        'name1' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '30',
                                'null' => TRUE,
                        ),
                        'name2' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '30',
                                'null' => TRUE,
                        ),
                        'lastname1' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '30',
                                'null' => TRUE,
                        ),
                        'lastname2' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '30',
                                'null' => TRUE,
                        ),
                        'mobile' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '30',
                                'null' => TRUE
                        ),
                        'phone' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '30',
                                'null' => TRUE
                        ),
                        'birthdate' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'null' => TRUE,
                        ),
                        'gender' => array(
                                'type' => 'TINYINT',
                                'constraint' => '1',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        ),
                        'location' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => TRUE,
                        ),
                        'avatar_url' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => TRUE,
                        ),
                        'group_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'null' => TRUE,
                        ),
                        'permissions' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'null' => TRUE,
                        ),
                        'activation_code' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '40',
                                'null' => TRUE
                        ),
                        'forgotten_password_code' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '60',
                                'null' => TRUE
                        ),
                        'needs_passwd_change' => array(
                                'type' => 'TINYINT',
                                'constraint' => '1',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        ),
                        'is_active' => array(
                                'type' => 'TINYINT',
                                'constraint' => '1',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        ),
                        'is_locked' => array(
                                'type' => 'TINYINT',
                                'constraint' => '1',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        ),
                        'blocked_time' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'null' => TRUE,
                        ),
                        'lastlogin_time' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'null' => TRUE,
                        ),
                        'lastlogin_ip' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '15',
                                'null' => TRUE,
                        ),
                        'created_at' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'null' => TRUE,
                        ),
                        'edited_at' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'null' => TRUE,
                        ),

                ));
                // Adds the keys. TRUE for the primary key.
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->add_key('username');
                $this->dbforge->add_key('email');       
                // Creates the table. Second parameter TRUE creates it only if doesn't exist.
                $this->dbforge->create_table('users');

                #########################
                # LOGGIN ATTEMPTS TABLE #
                #########################

                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'ip_address' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '16'
                        ),
                        'user_id' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'null' => TRUE
                        ),
                        'status' => array(
                                'type' => 'INT',
                                'constraint' => '1',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        ),
                        'created_at' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        ),
                        'updated_at' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        )
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('login_attempts');

                ###############
                # GROUP TABLE #
                ###############

                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '30',
                                'null' => TRUE,
                        ),
                        'email' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '50',
                                'null' => TRUE,
                        ),
                        'max_members' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'null' => TRUE,
                        ),
                        'token' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '50',
                                'null' => TRUE,
                        ),
                        'logo_url' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '200',
                                'null' => TRUE,
                        ),
                        'is_active' => array(
                                'type' => 'TINYINT',
                                'constraint' => '1',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        ),
                        'created_at' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        ),
                        'edited_at' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'unsigned' => TRUE,
                                'null' => TRUE
                        ),
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->add_key('token');
                $this->dbforge->create_table('groups');
        }

        public function down() {
                $this->dbforge->drop_table('users');
                $this->dbforge->drop_table('login_attempts');
                $this->dbforge->drop_table('groups');
        }
}