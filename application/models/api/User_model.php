<?php
class User_model extends CI_Model
{
    public function addData($table, $data)
    {
        $query = $this->db->insert($table, $data);
        return $query;
    }

    public function getPassword($username)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $query = $this->db->get()->row();
        return $query;
    }

    public function checkUsername($username)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function getUserById($idUser)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('id_user', $idUser);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function userUpdateImage($idUser, $image)
    {
        $this->db->select('image');
        $this->db->from('tbl_user');
        $this->db->where('id_user', $idUser);
        $selectOldImage = $this->db->get()->row()->image;
        $selectNewImage = ['image' => $image];
        $this->db->where('id_user', $idUser);
        $query = $this->db->update('tbl_user', $selectNewImage);
        if ($query) {
            if (!empty($selectOldImage)) {
                if (file_exists("./images/user/" . $selectOldImage)) {
                    unlink("./images/user/" . $selectOldImage);
                    return $query;
                } else {
                    return $query;
                }
            } else {
                return $query;
            }
        } else {
            return false;
        }
    }

    public function userUpdateFullName($idUser, $data)
    {
        $this->db->where('id_user', $idUser);
        $query = $this->db->update('tbl_user', $data);
        return $query;
    }
    public function userDelete($idUser)
    {
        $this->db->select('image');
        $this->db->from('tbl_user');
        $this->db->where('id_user', $idUser);
        $selectOldImage = $this->db->get()->row()->image;
        $this->db->where('id_user', $idUser);
        $query = $this->db->delete('tbl_user');
        if ($query) {
            if (!empty($selectOldImage)) {
                if (file_exists("./images/user/" . $selectOldImage)) {
                    unlink("./images/user/" . $selectOldImage);
                    return $query;
                } else {
                    return $query;
                }
            } else {
                return $query;
            }
        } else {
            return false;
        }
    }
}
