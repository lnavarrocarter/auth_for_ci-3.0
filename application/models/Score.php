<?php 

if (!defined('BASEPATH')) exit('No direct script access allowed');
 
/*
|--------------------------------------------------------------------------
| Modelo RESTful
|--------------------------------------------------------------------------
|
| Esta es la plantilla de Youyouch para un modelo RESTful en Codeigniter.
| Esta plantilla contiene los métodos por defecto para realizar consultas a
| la base de datos. Utiliza la convención CRUD original de Youtouch para hacerlo: create, read,
| update y delete.
| Ademas contiene funciones extendidas como getFields, getNumberRegister, getLastId
|
|
*/
class Score extends CI_Model 
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

    }


    ##############################
    # MÉTODOS BÁSICOS DEL MODELO #
    ##############################

    // Crea una entrada en la base de datos
    public function create($table = "scores_catch",array $data) {
        $data['create_at'] = time();
        $query = $this->db->insert($table, $data);
        return $query;
    }

    // Obtiene una o varias entradas desde la base de datos
    public function read($table = "scores_catch",array $data = NULL,array $join = NULL,$select = NULL,$isarray = null,array $order = NULL){
        $query;
        if($select){
            $this->db->select($select);   
        }
        if($join){
           foreach ($join as $jointable => $joinid) {
                $this->db->join($jointable,$table.'.id='.$jointable.'.'.$joinid);
               
            } 
        }

        if(!$data){
            if($order){
                $query = $this->db->get($table)->order_by($order,'ASC');
            }else{
                $query = $this->db->get($table);
            }
        }else{
            if($order){
                $query = $this->db->order_by($order)->get_where($table,$data);
            }else{
                $query = $this->db->get_where($table,$data);
            }

        }
        if(!$query){
            return false; 
        } elseif ($query->num_rows() == 0) {
            return false; 
        } elseif ($query->num_rows() == 1 ) {
            if ($isarray) {
                return $query->result();
            } else {
                return $query->row();
            }
        } else {
            return $query->result();
        }
        
    }

    // Actualiza una entrada en la base de datos
    public function update($table = "scores_catch",array $data, array $where) {
        $data['edited_at'] = time();
        $query = $this->db->update($table, $data, $where);
        return $query;
    }

    // Elimina una entrada en la base de datos
    public function delete($table = "scores_catch",array $where) {
        $query = $this->db->delete($table, $where);
        return $query;
    }

    ##################################
    # MÉTODOS ESPECÍFICOS DEL MODELO #
    ##################################

    //obtiene todos los nombres de los campos de la tabla definidos en el modelo
    public function getFields($table = "scores_catch"){
        return $this->fields;
    }
    //Obtiene el numero de registros que tiene la tabla.
    public function getNumberRegister($table = "scores_catch"){

    }
    //obtiene el ultimo Id agregado a la tabla.
    public function getLastId(){
        return $this->db->insert_id();
    }

    ###################################
    # MÉTODOS RELACIONALES DEL MODELO #
    ###################################

}
