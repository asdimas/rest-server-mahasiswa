<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Mahasiswa extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }
    public function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('Mahasiswa_model', 'mhs');

        $this->methods['index_get']['limit'] = 30;
    }
    public function index_get() 
    {
        $id = $this->get('id');
        if ($id === null){
            $mahasiswa = $this->mhs->getMahasiswa();
        } else {
            $mahasiswa = $this->mhs->getMahasiswa($id);

        }
        if($mahasiswa){
            $this->response([
                'status'=> true,
                'data'=> $mahasiswa
                ],200);
        } else {
            $this->response([
                'status'=> false,
                'message'=> 'id not found'
                ],404);
        }
    }

    public function index_delete ()
    {
        $id = $this->delete('id');
        
        if ($id === null){
            $this->response([
                'status'=> false,
                'message'=> 'provide an id!'
                ],400);
        } else {
            if ( $this->mhs->deleteMahasiswa($id) > 0) {
                //ok
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'successfully deleted.'
                    ],200);
            } else {
                //id not found
                $this->response([
                    'status'=> false,
                    'message'=> 'id not found!'
                    ],400);
            }
        }
    }

    public function index_post()
    {
        $data = [
            'nrp' => $this->post('nrp'),
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'jurusan' => $this->post('jurusan')
        ];

        if ($this->mhs->createMahasiswa($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new mahasiswa has been created'
                ],201);
        } else {
            $this->response([
                'status'=> false,
                'message'=> 'failed to create new data!'
                ],400);
        }
    }

    public function index_put(){
        $id = $this->put('id');
        $data = [
            'nrp' => $this->put('nrp'),
            'nama' => $this->put('nama'),
            'email' => $this->put('email'),
            'jurusan' => $this->put('jurusan')
        ];

        if ($this->mhs->updateMahasiswa($data, $id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'data mahasiswa has been updated'
                ],200);
        } else {
            $this->response([
                'status'=> false,
                'message'=> 'failed to update data!'
                ],400);
        }
    }
}