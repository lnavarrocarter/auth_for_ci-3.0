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

class LoginAttempt extends CI_Model {

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
    public function create(string $table, array $data) {
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $query = $this->db->insert($table, $data);
        return $query;
    }

    // Obtiene una o varias entradas desde la base de datos
    public function read(string $table, array $data = NULL) {
        if (!$data) {
            $query = $this->db->get($table);
            return $query->result();
        } else {
            $query = $this->db->get_where($table, $data);
            return $query->row();
        }
    }

    // Actualiza una entrada en la base de datos
    public function update(string $table, array $data, array $where) {
        $data['updated_at'] = time();
        $query = $this->db->update($table, $data, $where);
        return $query;
    }

    // Elimina una entrada en la base de datos
    public function delete($table, array $where) {     
        $query = $this->db->delete($table, $where);
        return $query;
    }

    ##################################
    # MÉTODOS ESPECÍFICOS DEL MODELO #
    ##################################

    // Cuenta los intentos de login fallidos
    public function count($data) {
        $this->db->where($data);
        $query = $this->db->get('login_attempts')->num_rows();
        return $query;
    }

}