<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Modelo RESTful
|--------------------------------------------------------------------------
|
| Esta es la plantilla de Ncai SpA para un modelo RESTful en Codeigniter.
| Esta plantilla contiene los métodos por defecto para realizar consultas a
| la base de datos. Utiliza la convención CRUD para hacerlo: create, read,
| update y delete.
|
|
*/

class User extends CI_Model {

    ##########################
    # CONSTRUCTOR DEL MODELO #
    ##########################

    public function __construct() {
        parent::__construct();

    }
    
    ##############################
    # MÉTODOS BÁSICOS DEL MODELO #
    ##############################

    // Crea una entrada en la base de datos
    public function create(string $table = 'users', array $data) {
        $data['created_at'] = time();
        $data['edited_at'] = time();
        $query = $this->db->insert($table, $data);
        return $query;
    }

    // Obtiene una o varias entradas desde la base de datos
    public function read(string $table = 'users', array $data = NULL, bool $array = false) {
        if (!$data) {
            $this->db->select('users.*, groups.name as group_name');
            $this->db->from($table);
            $this->db->join('groups', 'groups.id = users.group_id');
            $query = $this->db->get();
            return $query->result();
        } else {
            $this->db->select('users.*, groups.name as group_name');
            $this->db->from($table);
            $this->db->join('groups', 'groups.id = users.group_id');
            foreach ($data as $key => $val) {
                $this->db->where($table.'.'.$key, $val);
            }
            $query = $this->db->get();
            if ($query->num_rows() == 0 ) {
                return false; 
            } elseif ($query->num_rows() == 1 ) {
                if ($array) {
                    return $query->result();
                } else {
                    return $query->row();
                }
            } else {
                return $query->result();
            }
        }
    }

    // Actualiza una entrada en la base de datos
    public function update(string $table = 'users', array $data, array $where) {
        $data['edited_at'] = time();
        $query = $this->db->update($table, $data, $where);
        return $query;
    }

    // Elimina una entrada en la base de datos
    public function delete($table = 'users', array $where) {     
        $query = $this->db->delete($table, $where);
        return $query;
    }

    ##################################
    # MÉTODOS ESPECÍFICOS DEL MODELO #
    ##################################

}