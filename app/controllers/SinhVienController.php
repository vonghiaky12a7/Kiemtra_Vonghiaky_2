<?php
include 'db.php';
include 'SinhVien.php';

class SinhVienController
{
    private $model;

    public function __construct()
    {
        $this->model = new SinhVien($GLOBALS['conn']);
    }

    public function index()
    {
        $result = $this->model->getAllSinhVien();
        include 'views/index.php';
    }

    public function detail($MaSV)
    {
        $student = $this->model->getSinhVienById($MaSV);
        include 'views/detail.php';
    }

    public function create($data)
    {
        $success = $this->model->createSinhVien($data);
        if ($success) {
            header("Location: index.php");
        } else {
            echo "Error creating student.";
        }
    }

    public function edit($MaSV, $data)
    {
        $success = $this->model->updateSinhVien($MaSV, $data);
        if ($success) {
            header("Location: index.php");
        } else {
            echo "Error updating student.";
        }
    }

    public function delete($MaSV)
    {
        $success = $this->model->deleteSinhVien($MaSV);
        if ($success) {
            header("Location: index.php");
        } else {
            echo "Error deleting student.";
        }
    }
}