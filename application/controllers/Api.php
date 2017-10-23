<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $headers = getallheaders();
        if(!isset($headers['Token'])) {
            $this->response(401, 'Token header not sent.');
        } elseif ($headers['Token'] == '93387e1269171cad096dfa95531963f20cf83616') {
            $this->verb = $_SERVER['REQUEST_METHOD'];
            $this->load->model('User');
            $this->load->model('Score');
            // Continues past the constructor to the requested method
        } else {
            $this->response(401, 'Token header is invalid.');
        }
        
    }

    public function scores_totem(){
        if($this->verb == 'POST'){
            $data['dinosaurs'] = $this->input->post('dinosaurs');
            $data['game_level'] = $this->input->post('game_level');
            $data['points'] = $this->input->post('points');
            $query = $this->Score->create('scores_totem',$data);
            if($query){
                $response['id'] = $this->Score->getLastId();
                $response['url'] = base_url('website/share_score/'.$response['id']);
                $this->response(200, 'Puntaje guardado correctamente',$response);
            }else{
                $this->response(500, 'Error al guardar el puntaje en base de datos');
            }
        }else if($this->verb == 'GET'){

        }
    }


    private function response(int $code, string $msg, array $data = NULL) {
        $json['code'] = $code;
        $json['msg'] = $msg;
        if ($data) {
            $json['data'] = $data;
        }
        echo json_encode($json);
        http_response_code($code);
        die;
    }



}