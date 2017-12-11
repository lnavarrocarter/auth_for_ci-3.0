<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Enterprise extends CI_Migration {

    public function up() {
        /**
         * Tabla de Enterprise
         */
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
                'fakename' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '100',
                        'null' => TRUE,
                ),
                'giro' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '100',
                        'null' => TRUE,
                ),
                'email' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '60',
                        'null' => TRUE,
                ),
                'dni' => array(
                        'type' => 'INT',
                        'constraint' => 10,
                        'null' => TRUE,
                ),
                'dv' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 1,
                        'null' => TRUE,
                ),
                'description' => array(
                        'type' => 'LONGTEXT',
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
                'plan_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => TRUE,
                ),
                'is_active' => array(
                        'type' => 'TINYINT',
                        'constraint' => '1',
                        'unsigned' => TRUE,
                        'null' => TRUE
                ),
                'is_deleted' => array(
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
                'deleted_at' => array(
                        'type' => 'INT',
                        'constraint' => '11',
                        'unsigned' => TRUE,
                        'null' => TRUE
                ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('token');
        $this->dbforge->create_table('enterprise');
    }

    public function down() {
        $this->dbforge->drop_table('enterprise');
    }
}