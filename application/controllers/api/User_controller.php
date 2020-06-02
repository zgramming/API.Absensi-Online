<?php
defined('BASEPATH') or exit('no direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
class User_controller extends REST_Controller
{
    //! Status User 1. Aktif 2.Disable 3.Deleted
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['api/User_model']);
    }

    public function userRegister_post()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $fullName = $this->input->post('full_name');
        $status = 1;
        $data = [
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "full_name" => $fullName,
            "status" => $status,
            "image" => "",
        ];
        $checkUsername = $this->User_model->checkUsername($username);
        $result = $this->User_model->addData("tbl_user", $data);
        if (empty($checkUsername)) {
            if ($result) {
                $this->response([
                    "status" => 1,
                    "message" => "Berhasil Mendaftarkan User Dengan Nama $fullName ",
                ], REST_Controller::HTTP_CREATED);
            } else {
                $this->response([
                    "status" => 0,
                    "message" => "Gagal Daftar User , Coba Lagi Nanti... ",
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                "status" => 0,
                "message" => "Username Already Exist ",
            ], REST_Controller::HTTP_CONFLICT);
        }
    }


    public function userLogin_post()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $getPasswordHashDatabase = $this->User_model->getPassword($username);

        if ($getPasswordHashDatabase != null) {
            if (password_verify($password, $getPasswordHashDatabase->password)) {
                $checkUsername = $this->User_model->checkUsername($username);
                $this->response([
                    "status" => 1,
                    "message" => "Username Valid",
                    "data" => $checkUsername
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    "status" => 0,
                    "message" => "Password Not Valid",
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                "status" => 0,
                "message" => "Username Not Found",
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function userUpdateImage_post()
    {
        $idUser = $this->input->post('id_user');
        $config['upload_path'] = './images/user/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '1000'; // max size in KB
        $config['overwrite'] = TRUE; // For Replace Image name
        $config['encrypt_name'] = TRUE; // For Encrypt Name
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')) {
            $errorArray = strip_tags($this->upload->display_errors());
            $this->response(
                ["status" => 0, "message" => "Gagal Upload Image $errorArray"],
                REST_Controller::HTTP_BAD_REQUEST
            );
        } else {
            $info = $this->upload->data();
            $image_path = $info['raw_name'] . $info['file_ext'];
            $result = $this->User_model->userUpdateImage($idUser, $image_path);
            if ($result) {
                $updateUser = $this->User_model->getUserById($idUser);
                $this->response([
                    "status" => 1,
                    "message" => "Berhasil Update Image",
                    "data" => $updateUser
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    "status" => 0,
                    "message" => " Gagal Update Image Profil , Coba lagi nanti..."
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function userUpdateFullName_post()
    {
        $idUser = $this->input->post('id_user');
        $fullName = $this->input->post('full_name');

        $data = [
            "full_name" => $fullName
        ];
        $result = $this->User_model->userUpdateFullName($idUser, $data);
        if ($result) {
            $updateUser = $this->User_model->getUserById($idUser);

            $this->response([
                "status" => 1,
                "message" => "Berhasil Update Nama Lengkap Dengan ID User $idUser",
                "data" => $updateUser
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                "status" => 0,
                "message" => "Gagal update Nama Lengkap Dengan ID User $idUser"
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function userDelete_post()
    {
        $idUser = $this->input->post('id_user');

        $result = $this->User_model->userDelete($idUser);

        if ($result) {

            $this->response([
                "status" => 1,
                "message" => "Berhasil Hapus User dengan Id $idUser"
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                "status" => 0,
                "message" => " Gagal Hapus User Dengan Id $idUser , Coba lagi nanti..."
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
