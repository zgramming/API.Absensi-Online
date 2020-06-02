<?php
class Absensi_model extends CI_Model
{
    public function getJamAbsenPulangKerjaKantor()
    {
        $this->db->select('jam_absen_pulang');
        $this->db->from('tbl_jam_kerja');
        $query = $this->db->get()->row()->jam_absen_pulang;
        return $query;
    }
    public function getJamAbsenMasukKerjaKantor()
    {
        $this->db->select('jam_absen_masuk');
        $this->db->from('tbl_jam_kerja');
        $query = $this->db->get()->row()->jam_absen_masuk;
        return $query;
    }

    public function getDestinationById($idUser)
    {
        $this->db->select('*');
        $this->db->from('tbl_destinasi');
        $this->db->where('id_user', $idUser);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function getSelectedDestinationById($idUser)
    {
        $this->db->select('*');
        $this->db->from('tbl_destinasi');
        $this->db->where('id_user', $idUser);
        $this->db->where('status', "t");
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function addData($table, $data)
    {
        $query = $this->db->insert($table, $data);
        return $query;
    }

    public function destinationUpdate($idDestination, $idUser, $data)
    {
        $this->db->where('id_destinasi', $idDestination);
        $this->db->where('id_user', $idUser);
        $query =  $this->db->update('tbl_destinasi', $data);
        return $query;
    }

    public function destinationUpdateImage($idDestination, $image)
    {
        $this->db->select('image');
        $this->db->from('tbl_destinasi');

        $this->db->where('id_destinasi', $idDestination);
        $selectOldImage = $this->db->get()->row()->image;
        $selectNewImage = ['image' => $image];
        $this->db->where('id_destinasi', $idDestination);
        $query = $this->db->update('tbl_destinasi', $selectNewImage);
        if ($query) {
            if (!empty($selectOldImage)) {
                if (file_exists("./images/destinasi/" . $selectOldImage)) {
                    unlink("./images/destinasi/" . $selectOldImage);
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

    public function destinationUpdateStatus($idDestination, $idUser, $data)
    {
        $this->db->where('id_user', $idUser);
        $this->db->where('id_destinasi', $idDestination);
        $query = $this->db->update('tbl_destinasi', $data);
        return $query;
    }

    public function destinationRemoveStatus($idUser)
    {
        $this->db->where('id_user', $idUser);
        $this->db->where('status', "t");
        $query = $this->db->update('tbl_destinasi', ["status" => null]);
        return $query;
    }

    public function destinationDelete($idDestination)
    {
        $this->db->select('image');
        $this->db->from('tbl_destinasi');
        $this->db->where('id_destinasi', $idDestination);
        $selectOldImage = $this->db->get()->row()->image;
        $this->db->where('id_destinasi', $idDestination);
        $query = $this->db->delete('tbl_destinasi');
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

    public function absensiUpdatePulang($idUser, $tanggalAbsenPulang, $data)
    {
        $this->db->where('id_user', $idUser);
        $this->db->where('tanggal_absen_masuk', $tanggalAbsenPulang);
        $query = $this->db->update('tbl_absensi', $data);
        return $query;
    }

    public function getJamAbsenMasuk($idUser, $tanggal)
    {
        $this->db->select('jam_absen_masuk');
        $this->db->from('tbl_absensi');
        $this->db->where('id_user', $idUser);
        $this->db->where('tanggal_absen_masuk', $tanggal);
        $query = $this->db->get()->row()->jam_absen_masuk;
        return $query;
    }
    //! Mulai Perhitungan

    public function getTotalOnTime($idUser, $tahunKerja, $bulanKerja, $hariKerja)
    {
        $this->db->select('jam_absen_masuk');
        $this->db->from('tbl_jam_kerja');
        $resultJamKerja = $this->db->get()->row()->jam_absen_masuk;

        $this->db->select('jam_absen_masuk');
        $this->db->from('tbl_absensi');
        $this->db->where('id_user', $idUser);
        $this->db->where('jam_absen_masuk <=', $resultJamKerja);
        $this->db->where('YEAR(tanggal_absen)', $tahunKerja);
        $this->db->where('MONTH(tanggal_absen)', $bulanKerja);
        $this->db->where('DAY(tanggal_absen)', $hariKerja);
        $query = $this->db->get()->num_rows();
        return $query;
    }
    public function checkAbsenMasukDanPulang($idUser, $tanggalAbsenMasuk)
    {
        $this->db->select('tanggal_absen_masuk,tanggal_absen_pulang');
        $this->db->from('tbl_absensi');
        $this->db->where('id_user', $idUser);
        $this->db->where('tanggal_absen_masuk', $tanggalAbsenMasuk);
        $query = $this->db->get()->result_array();
        if (empty($query)) {
            return null;
        } else {
            return $query;
        }
    }
    public function getStatusAbsenByMonth($idUser, $tahunKerja, $bulanKerja)
    {
        $this->db->select('tanggal_absen,status');
        $this->db->from('tbl_absensi');
        $this->db->where('id_user', $idUser);
        $this->db->where('YEAR(tanggal_absen)', $tahunKerja);
        $this->db->where('MONTH(tanggal_absen)', $bulanKerja);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function getAbsenMonthly($idUser, $tahunKerja, $bulanKerja)
    {
        $this->db->select('tanggal_absen,jam_absen_masuk,jam_absen_pulang,durasi_absen,status,durasi_lembur');
        $this->db->from('tbl_absensi');
        $this->db->where('id_user', $idUser);
        $this->db->where('YEAR(tanggal_absen)', $tahunKerja);
        $this->db->where('MONTH(tanggal_absen)', $bulanKerja);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function convertTimeToHoursMinute($time)
    {
        $second = $this->db->query("SELECT TIME_TO_SEC('$time')");
        $hoursMinute = $this->db->query("SELECT TIME_FORMAT(SEC_TO_TIME('$second'),'%Hh %im')");
        return $hoursMinute;
    }

    public function subTimeAbsen($jamPulang, $jamMasuk)
    {
        return $this->db->query("SELECT SUBTIME('$jamPulang','$jamMasuk') as result")->row()->result;
    }
}
