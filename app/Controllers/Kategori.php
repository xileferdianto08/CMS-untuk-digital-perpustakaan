<?php

namespace App\Controllers;

use App\Models\KategoriModel;

class Kategori extends BaseController
{
    protected $kategoriModel;
    public $idAdmin, $emailAdmin;
    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->idAdmin = session()->get('idAdmin');
        $this->emailAdmin = session()->get('email');
    }

    public function index()
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
        } else {
            if (!empty(session()->get('idAdmin'))) {
                $data = [
                    'id' => '',
                    'title' => 'List Kategori',
                    'kategori' => [],
                    'dataKategori' => $this->kategoriModel->getAllKategori(),
                    'modal' => '',

                ];
                echo view('kategori/listKategori', $data);
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }

    public function addKategori()
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
        } else {
            if (!empty($this->idAdmin)) {
                helper(['form']);
                helper('text');
                $postName = $this->request->getPost('namaKategori');

                $validationErr = [
                    'namaKategori' => [
                        'required' => 'Nama Kategori wajib diisi',
                        'is_unique' => 'Kategori sudah terdaftar sebelumnya!'
                    ]
                ];

                $validationRules = [
                    'namaKategori' => 'required|is_unique[kategori.namaKategori]'
                ];

                $data['title'] = 'List Kategori';
                $data['dataKategori'] = $this->kategoriModel->getAllKategori();

                if (!$this->validate($validationRules, $validationErr)) {
                    $data['modal'] = "			
            <script>
                const myModal = document.querySelector('#add')
                const modal = new mdb.Modal(myModal)
                modal.show()
			</script>";
                    $data['validationErr'] = $this->validator;

                    echo view('kategori/listKategori', $data);
                } else {
                    $namaKategori = $this->kategoriModel->escape($postName);
                    $this->kategoriModel->addKategori($namaKategori);
                    return redirect()->to('kategori');
                }
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }

    public function showEditKategoriModal($idKategori)
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
        } else {
            if (!empty($this->idAdmin)) {
                $data['id'] = $idKategori;
                $data['title'] = 'List Kategori';
                $data['dataKategori'] = $this->kategoriModel->getAllKategori();
                $data['kategori'] = $this->kategoriModel->getAllKategori($idKategori);
                $data['validationErr'] = '';
                $data['modal'] = "			
                <script>
                var myModal = new bootstrap.Modal(document.getElementById('edit'), {});
                document.onreadystatechange = function () {
                myModal.show();
                };
                </script>";
                echo view('kategori/listKategori', $data);
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }

    public function editKategori($idKategori)
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
        } else {
            if (!empty($this->idAdmin)) {
                helper(['form']);
                helper('text');
                $postName = $this->request->getPost('namaKategori');

                $validationErr = [
                    'namaKategori' => [
                        'required' => 'Nama Kategori wajib diisi',
                        'is_unique' => 'Kategori sudah terdaftar sebelumnya!'
                    ]
                ];

                $validationRules = [
                    'namaKategori' => 'required|is_unique[kategori.namaKategori]'
                ];

                $data['title'] = 'List Kategori';
                $data['dataKategori'] = $this->kategoriModel->getAllKategori();
                $data['kategori'] = $this->kategoriModel->getAllKategori($idKategori);

                if (!$this->validate($validationRules, $validationErr)) {
                    $data['modal'] = "			
                    <script>
                        var myModal = new bootstrap.Modal(document.getElementById('edit'), {});
                        document.onreadystatechange = function () {
                        myModal.show();
                    </script>";
                    $data['validationErr'] = $this->validator;

                    echo view('kategori/listKategori', $data);
                } else {
                    $namaKategori = $this->kategoriModel->escape($postName);
                    $newId = $this->kategoriModel->escape($idKategori);
                    $this->kategoriModel->updateKategori($newId, $namaKategori);
                    return redirect()->to('kategori');
                }
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }


    public function deleteKategori($idKategori)
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
        } else {
            if (!empty($this->idAdmin)) {
                $this->kategoriModel->deleteKategori($idKategori);
                return redirect()->to('kategori');
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }
}
