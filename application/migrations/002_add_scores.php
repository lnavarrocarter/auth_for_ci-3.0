<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Scores extends CI_Migration {

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
                        'user_id' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'null' => TRUE
                        ),
                        'bounds_count' => array(
                                'type' => 'INT',
                                'constraint' => 50,
                                'null' => false
                        ),
                        'dinosaurs_count' => array(
                                'type' => 'INT',
                                'constraint' => 50,
                                'null' => false
                        ),
                        'points' => array(
                                'type' => 'INT',
                                'constraint' => 50,
                                'null' => false
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
                $this->dbforge->add_key('user_id');     
                // Creates the table. Second parameter TRUE creates it only if doesn't exist.
                $this->dbforge->create_table('scores_catch');

                /**
                 *  scores_totem
                 * */

                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 6,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'dinosaurs' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 100,
                                'null' => false
                        ),
                        'game_level' => array(
                                'type' => 'INT',
                                'constraint' => 50,
                                'null' => false
                        ),
                        'points' => array(
                                'type' => 'INT',
                                'constraint' => 50,
                                'null' => false
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
                // Creates the table. Second parameter TRUE creates it only if doesn't exist.
                $this->dbforge->create_table('scores_totem');

                /**
                 *  scores_movil
                 * */
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 6,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'user_id' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'null' => TRUE
                        ),
                        'dinosaurs' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 100,
                                'null' => false
                        ),
                        'game_level' => array(
                                'type' => 'INT',
                                'constraint' => 50,
                                'null' => false
                        ),
                        'points' => array(
                                'type' => 'INT',
                                'constraint' => 50,
                                'null' => false
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
                // Creates the table. Second parameter TRUE creates it only if doesn't exist.
                $this->dbforge->create_table('scores_movil');




                
        }

        public function down() {
                $this->dbforge->drop_table('scores_catch');
                $this->dbforge->drop_table('scores_totem');
                $this->dbforge->drop_table('scores_movil');
        }
}