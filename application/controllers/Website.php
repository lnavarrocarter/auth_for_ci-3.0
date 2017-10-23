<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Score');       
    }

    public function index(){
       $this->load->view('website/layout/header');
       /* Section Webpage */

       /* Section Webpage */
       $this->load->view('website/layout/footer');
    }

    public function expocicion(){
        $this->load->view('website/layout/header');
       /* Section Webpage */

       /* Section Webpage */
       $this->load->view('website/layout/footer');
    }


    public function galeria(){
        $this->load->view('website/layout/header');
       /* Section Webpage */

       /* Section Webpage */
       $this->load->view('website/layout/footer');
    }

    public function descargas(){
        $this->load->view('website/layout/header');
       /* Section Webpage */

       /* Section Webpage */
       $this->load->view('website/layout/footer');
    }

    public function share_score($id){

        $data['score'] = $this->Score->read('scores_totem',['id' => $id]);
        $this->load->view('website/layout/header',$data);
       /* Section Webpage */
       $this->load->view('website/share_score',$data);
       /* Section Webpage */
       $this->load->view('website/layout/footer');
    }

}