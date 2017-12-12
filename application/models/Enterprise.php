<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enterprise extends CI_Model{
     ##############################
    # MÉTODOS BÁSICOS DEL MODELO #
    ##############################

    // Crea una entrada en la base de datos
    public function create($table = 'enterprise', array $data) {
        $data['created_at'] = time();
        $data['edited_at'] = time();
        $query = $this->db->insert($table, $data);
        return $query;
    }

    // Obtiene una o varias entradas desde la base de datos
    public function read($table = 'enterprise', array $data = NULL,array $join = NULL,$select = NULL,$array = false) {
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
    public function update($table = 'enterprise', array $data, array $where) {
        $data['edited_at'] = time();
        $query = $this->db->update($table, $data, $where);
        return $query;
    }

    public function delete($table = 'enterprise', array $where){
        $data['deleted_at'] = time();
        $data['is_deleted'] = 1;
        $query = $this->db->update($table, $data, $where);
        return $query;
    }

    // Elimina una entrada en la base de datos
    public function destroy($table = 'enterprise', array $where) {     
        $query = $this->db->delete($table, $where);
        return $query;
    }

    ##################################
    # MÉTODOS ESPECÍFICOS DEL MODELO #
    ##################################
}