<?php 


class Seeders{
    private $CI;

    function __construct() {
        // Assign by reference with "&" so we don't create a copy
        $this->CI = &get_instance();
        $this->CI->load->model('User');
        $this->CI->load->model('Group');
        $this->CI->load->model('Enterprise');
    }

    function init_db(){
        $this->create_enterprise_admin();
        $this->create_group_sadmin();
        $this->create_group_admin();
        $this->create_group_user();
        $this->create_user_admin();
    }

    private function create_group_sadmin(){
        $data['name'] = 'SuperAdministradores';
        $data['is_active'] = 1;
        $data['created_at'] = time();
        $this->CI->Group->create('groups',$data);

    }

    private function create_group_admin(){
        $data['name'] = 'Administradores';
        $data['is_active'] = 1;
        $data['created_at'] = time();
        $this->CI->Group->create('groups',$data);

    }

    private function create_group_user(){
        $data['name'] = 'Usuarios';
        $data['is_active'] = 1;
        $data['created_at'] = time();
        $this->CI->Group->create('groups',$data);

    }

    private function create_enterprise_admin(){
        $data['name'] = 'AUTHFOX';
        $data['fakername'] = 'AUTHFOX NCAI';
        $data['giro'] = 'Programins For Love';
        $data['email'] = 'info@auth.cl';
        $data['dni'] = '99666333';
        $data['dv'] = '7';
        $data['description'] = 'The AUTHFOX is a wonderfull enterprise.';
        $data['max_members'] = '100';
        $data['token'] = sha1($data['email'].$data['dni']);
        $data['logo_url'] = 'assets/img/logos/authfox.png';
        $data['is_active'] = 1;
        $data['is_deleted'] = 0;
        $data['created_at'] = time();
        $this->CI->Enterprise->create('enterprise',$data);
    }

    private function create_user_admin(){
        $data['username'] = 'admin';
        $data['email'] = $this->CI->config->item('user_admin');
        $data['name1'] = 'Administrador';
        $data['lastname1'] = 'Auth';
        $data['mobile'] = '+56973394675';
        $data['salt'] = uniqid(mt_rand(), true);
        $data['password'] = password_hash($data['salt'].'12345678',PASSWORD_BCRYPT);
        $data['is_active'] = 1;
        $data['is_locked'] = 0;
        $data['group_id'] = 1;
        $data['enterprise_id'] = 1;
        $data['permissions'] = 7;
        $data['created_at'] = time();
        $this->CI->User->create('users',$data);
    }

}