<?php
defined('BASEPATH') or exit('no direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
class Absensi_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['api/Absensi_model']);
    }


    public function destinationById_get()
    {

        $idUser = $this->input->get('id_user');
        $isSelected = $this->input->get('is_selected');

        $allDestination = $this->Absensi_model->getDestinationById($idUser);
        $selectedDestination = $this->Absensi_model->getSelectedDestinationById($idUser);

        $result = ($isSelected == "t")  ? $selectedDestination : $allDestination;
        $errorMessage = ($isSelected == "t") ? "Pilih lokasi absen terlebih dahulu" : " Lokasi absen tidak ditemukan";
        if (!empty($result)) {
            $this->response([
                "status" => 1,
                "message" => "Destinasi Ditemukan",
                "data" => $result,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                "status" => 0,
                "message" => $errorMessage
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }


    public function destinationRegister_post()
    {
        $idUser = $this->input->post('id_user');
        $nameDestination = $this->input->post('nama_destinasi');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');

        $data = [
            "id_user" => $idUser,
            "nama_destinasi" => $nameDestination,
            "latitude" => $latitude,
            "longitude" => $longitude,
        ];
        $result = $this->Absensi_model->addData("tbl_destinasi", $data);
        if ($result) {
            $this->response([
                "status" => 1,
                "message" => "Berhasil Menambahkan Lokasi Absen $nameDestination",
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                "status" => 0,
                "message" => "Gagal Menambahkan Lokasi Absen $nameDestination",
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function destinationUpdate_post()
    {
        //! Parameter yang dibutuhkan

        $idDestination = $this->input->post('id_destinasi');
        $idUser = $this->input->post('id_user');
        $nameDestination = $this->input->post('nama_destinasi');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $keterangan = $this->input->post('keterangan');

        $data = [
            "nama_destinasi" => $nameDestination,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "keterangan" => $keterangan,
        ];

        $result = $this->Absensi_model->destinationUpdate($idDestination, $idUser, $data);
        if ($result) {
            $this->response([
                "status" => 1,
                "message" => "Berhasil Update Lokasi Absen",
            ], REST_Controller::HTTP_NO_CONTENT);
        } else {
            $this->response([
                "status" => 0,
                "message" => " Gagal Update Lokasi Absen"
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function destinationUpdateImage_post()
    {
        $idDestination = $this->input->post('id_destinasi');
        $idUser = $this->input->post('id_user');
        $config['upload_path'] = './images/destinasi/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '1000'; // max size in KB
        $config['overwrite'] = TRUE; // For Replace Image name
        $config['encrypt_name'] = TRUE; // For Encrypt Name
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')) {
            $errorArray = strip_tags($this->upload->display_errors());
            $this->response(
                ["status" => 0, "message" => "Gagal Upload Gambar Lokasi Absen $errorArray"],
                REST_Controller::HTTP_BAD_REQUEST
            );
        } else {

            $info = $this->upload->data();
            $image_path = $info['raw_name'] . $info['file_ext'];
            $result = $this->Absensi_model->destinationUpdateImage($idDestination, $idUser, $image_path);
            if ($result) {
                $updateDestination = $this->Absensi_model->getDestinationById($idUser);
                $this->response([
                    "status" => 1,
                    "message" => "Berhasil Update Gambar Lokasi Absen",
                    "data" => $updateDestination
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    "status" => 0,
                    "message" => " Gagal Update Gambar Lokasi Absen , Coba lagi nanti."
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function destinationUpdateStatus_post()
    {
        $idDestination = $this->input->post('id_destinasi');
        $idUser = $this->input->post('id_user');

        $destinationRemoveStatus = $this->Absensi_model->destinationRemoveStatus($idUser);

        if ($destinationRemoveStatus) {
            $data = ["status" => "t"];
            $result = $this->Absensi_model->destinationUpdateStatus($idDestination, $idUser, $data);

            if ($result) {
                $this->response([
                    "status" => 1,
                    "message" => "Berhasil Update Status Lokasi Absen",
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    "status" => 0,
                    "message" => "Gagal Update Status Lokasi Absen",
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                "status" => 0,
                "message" => "Gagal Menghapus Status Lokasi Absen Lama",
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function destinationDelete_post()
    {
        $idDestination = $this->input->post('id_destinasi');
        $result = $this->Absensi_model->destinationDelete($idDestination);

        if ($result) {
            $this->response([
                "status" => 1,
                "message" => "Berhasil Hapus Lokasi Absen",
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                "status" => 0,
                "message" => "Gagal Hapus Lokasi Absen",
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    //! Mulai Pengetesan Dari Sini
    //! Status Absen Yang Tersedia
    //! 1. O => Tepat Waktu
    //! 2. T => Telat
    //! 3. A => Alfa
    public function checkAbsenMasukDanPulang_get()
    {
        $idUser = $this->input->get('id_user');
        $tanggalAbsenMasuk = $this->input->get('tanggal_absen_masuk');
        $result = $this->Absensi_model->checkAbsenMasukDanPulang($idUser, $tanggalAbsenMasuk);
        if (empty($result)) {
            $this->response([
                "status" => 1,
                "message" => "Belum Absen Hari Ini",
                "data" => 0
            ], REST_Controller::HTTP_OK);
        } else {
            foreach ($result as $r) {
                $tglMasuk = $r['tanggal_absen_masuk'];
                $tglPulang = $r['tanggal_absen_pulang'];
                if (!empty($tglMasuk) && !empty($tglPulang)) {
                    $this->response([
                        "status" => 1,
                        "message" => "Anda Sudah Selesai Bekerja",
                        "data" => 2
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        "status" => 1,
                        "message" => "Anda Sudah Absen Hari Ini",
                        "data" => 1
                    ], REST_Controller::HTTP_OK);
                }
            }
        }
    }


    public function absensiMasuk_post()
    {
        //! Fungsi Untuk Mendapatkan Waktu Daerah Jakarta
        $idUser = $this->input->post('id_user');
        $tanggalAbsen = $this->input->post('tanggal_absen');
        $tanggalAbsenMasuk = $this->input->post('tanggal_absen_masuk');
        $jamAbsenMasuk = $this->input->post('jam_absen_masuk');
        $createdDate = $this->input->post('created_date');

        $data = [];
        $getJamAbsenMasukKantor = $this->Absensi_model->getJamAbsenMasukKerjaKantor();
        $getJamAbsenPulangKantor = $this->Absensi_model->getJamAbsenPulangKerjaKantor();
        // //! Ini Waktu Jam Absen Diantara Batas Waktu Masuk Kantor dan Kurang Dari Batas Pulang Kantor
        // //! Ini Telat
        if ($jamAbsenMasuk > $getJamAbsenMasukKantor && $jamAbsenMasuk < $getJamAbsenPulangKantor) {
            $data = [
                "id_user" => $idUser,
                "tanggal_absen" => $tanggalAbsen,
                "tanggal_absen_masuk" => $tanggalAbsen,
                "jam_absen_masuk" => $jamAbsenMasuk,
                "status" => "t",
                "created_date" => $createdDate,
            ];
        }
        //! Ini Kalau Datang Masuk Kantor Melebihi Jam Pulang Kantor, Langsung Alfa
        else if ($jamAbsenMasuk > $getJamAbsenPulangKantor) {
            $data = [
                "id_user" => $idUser,
                "tanggal_absen" => $tanggalAbsen,
                "tanggal_absen_masuk" => $tanggalAbsen,
                "tanggal_absen_pulang" => $tanggalAbsen,
                "jam_absen_masuk" => $jamAbsenMasuk,
                "jam_absen_pulang" => $jamAbsenMasuk,
                "durasi_absen" => 0,
                "status" => "a",
                "durasi_lembur" => 0,
                "created_date" => $createdDate,
            ];
        }
        //! Ini Absen Tepat Waktu
        else {
            $data = [
                "id_user" => $idUser,
                "tanggal_absen" => $tanggalAbsen,
                "tanggal_absen_masuk" => $tanggalAbsenMasuk,
                "jam_absen_masuk" => $jamAbsenMasuk,
                "status" => "o",
                "created_date" => $createdDate,
            ];
        }
        $result = $this->Absensi_model->addData("tbl_absensi", $data);
        if ($result) {
            $this->response([
                "status" => 1,
                "message" => " Berhasil Absen Masuk Pada Pukul " . $jamAbsenMasuk . "",
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                "status" => 0,
                "message" => "Gagal Melakukan Absen Pada Pukul " . $jamAbsenMasuk . "",
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function absensiPulang_post()
    {
        $idUser = $this->input->post('id_user');
        $tanggalAbsenPulang = date('Y-m-d', strtotime($this->input->post('tanggal_absen_pulang')));
        $jamAbsenPulang = $this->input->post('jam_absen_pulang');
        $updateDate = $this->input->post('update_date');
        $getJamAbsenMasuk = $this->Absensi_model->getJamAbsenMasuk($idUser, $tanggalAbsenPulang);
        if (empty($getJamAbsenMasuk)) {
            $this->response([
                "status" => 0,
                "message" => " Jam Absen Masuk Pada Tanggal $tanggalAbsenPulang Tidak Ditemukan",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $durasiAbsen = $this->Absensi_model->subTimeAbsen($jamAbsenPulang, $getJamAbsenMasuk);
            $getJamAbsenPulangKantor = $this->Absensi_model->getJamAbsenPulangKerjaKantor();
            $statusLembur = ($jamAbsenPulang > $getJamAbsenPulangKantor) ? 1 : 0;
            $durasiLembur = ($statusLembur == 1) ? $this->Absensi_model->subTimeAbsen($jamAbsenPulang, $getJamAbsenPulangKantor) : 0;
            $data = [
                "tanggal_absen_pulang" => $tanggalAbsenPulang,
                "jam_absen_pulang" => $jamAbsenPulang,
                "durasi_absen" => $durasiAbsen,
                "durasi_lembur" => $durasiLembur,
                "update_date" => $updateDate
            ];
            $result = $this->Absensi_model->absensiUpdatePulang($idUser, $tanggalAbsenPulang, $data);
            if ($result) {
                $this->response([
                    "status" => 1,
                    "message" => "Berhasil Absen Pulang Pada Pukul " . $jamAbsenPulang . " ",
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    "status" => 0,
                    "message" => "Gagal Melakukan Absensi Pada Pukul " . $jamAbsenPulang . ". Coba Beberapa Saat Lagi... "
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    //!Disini mulai perhitungan 
    public function getPerformanceMonthly_get()
    {
        $idUser = $this->input->get('id_user');
        $tahun = date('Y', strtotime($this->input->get('tanggal_absen')));
        $bulan = date('m', strtotime($this->input->get('tanggal_absen')));
        $totalDayOfMonth = $this->input->get('total_day_of_month');
        $totalWeekDayOfMonth = $this->input->get('total_week_day_of_month');

        $total = 0;
        for ($i = 1; $i <= $totalDayOfMonth; $i++) {
            $result = $this->Absensi_model->getTotalOnTime($idUser, $tahun, $bulan, $i);
            if (!empty($result)) {
                $total++;
            }
        }
        $percentace = $total * 100 / $totalWeekDayOfMonth;
        $data = [
            "ot" => $total,
            "percentace" => round($percentace, 1)
        ];
        $this->response([
            "status" => 1,
            "message" => "Berhasil Mendapatkan Performance Bulanan",
            "data" => array($data)
        ], REST_Controller::HTTP_OK);
    }

    public function getStatusAbsenMonthly_get()
    {
        $idUser = $this->input->get('id_user');
        $tahun = date('Y', strtotime($this->input->get('tanggal_absen')));
        $bulan = date('m', strtotime($this->input->get('tanggal_absen')));

        $result = $this->Absensi_model->getStatusAbsenByMonth($idUser, $tahun, $bulan);
        $this->response(["status" => 1, "message" => "Data Absensi Bulan $bulan Tahun $tahun", "data" => $result], REST_Controller::HTTP_OK);
    }

    public function getAbsenMonthly_get()
    {
        $idUser = $this->input->get('id_user');
        $tahun = date('Y', strtotime($this->input->get('tanggal_absen')));
        $bulan = date('m', strtotime($this->input->get('tanggal_absen')));

        $result = $this->Absensi_model->getAbsenMonthly($idUser, $tahun, $bulan);

        $this->response(["status" => 1, "message" => "Data Absensi Bulan $bulan Tahun $tahun", "data" => $result], REST_Controller::HTTP_OK);
    }
}
