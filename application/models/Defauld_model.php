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

class Defauld_model extends CI_Model {

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
    public function create($table = '', array $data) {
        $data['created_at'] = time();
        $data['edited_at'] = time();
        $query = $this->db->insert($table, $data);
        return $query;
    }

    // Obtiene una o varias entradas desde la base de datos
    public function read($table = '', array $data = NULL,array $join = NULL,$select = NULL,$array = false) {
        $query;
        if($select){
            $this->db->select($select);
        }
        if ($join){
           foreach ($join as $jointable => $joinid) {
                $this->db->join($jointable, $jointable.'.id = '.$table.'.'.$joinid); 
            } 
        }
        if (!$data) {
            $query = $this->db->get($table);
        } else {
            $query = $this->db->get_where($table,$data);
        }
        if(!$query){
            return false; 
        } elseif ($query->num_rows() == 0) {
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

    // Actualiza una entrada en la base de datos
    public function update($table = '', array $data, array $where) {
        $data['edited_at'] = time();
        $query = $this->db->update($table, $data, $where);
        return $query;
    }

    public function delete($table, array $where){
        $data['deleted_at'] = time();
        $data['is_deleted'] = 1;
        $query = $this->db->update($table, $data, $where);
        return $query;
    }

    // Elimina una entrada en la base de datos
    public function destroy($table = '', array $where) {     
        $query = $this->db->delete($table, $where);
        return $query;
    }

    ##################################
    # MÉTODOS ESPECÍFICOS DEL MODELO #
    ##################################

}