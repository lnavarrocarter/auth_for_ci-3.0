<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Plans extends CI_Migration {

    public function up() {
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
                'descripcion' => array(
                        'type' => 'LONGTEXT',
                        'null' => TRUE
                ),
                'cost' => array(
                        'type' => 'INT',
                        'constraint' => '20',
                        'unsigned' => TRUE,
                        'null' => TRUE
                ),
                'duration' => array(
                        'type' => 'INT',
                        'constraint' => '2',
                        'unsigned' => TRUE,
                        'null' => TRUE
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
        $this->dbforge->create_table('plans');
    }

    public function down() {

    }
}