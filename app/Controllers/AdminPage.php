<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\BukuModel;
use App\Models\KategoriModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminPage extends BaseController
{
    protected $adminModel, $kategoriModel, $bukuModel, $idAdmin, $emailAdmin;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->kategoriModel = new KategoriModel();
        $this->bukuModel = new BukuModel();
        $this->idAdmin = session()->get('idAdmin');
        $this->emailAdmin = session()->get('emailAdmin');
    }
    public function login()
    {
        $data = [
            'title' => 'Login',
        ];
        return view('auth/adminLogin', $data);
    }

    public function doLogin()
    {
        helper(['form']);
        helper('text');

        $session = \Config\Services::session();

        $post = $this->request->getPost(['email', 'password']);

        $email = $this->adminModel->escape($post['email']);
        $hashedPwd = hash('sha512', $post['password']);
        $password = $this->adminModel->escape($hashedPwd);
        $validationRules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];


        $userData = $this->adminModel->login($email, $password);

        if (!empty($userData)) {
            session()->set('emailAdmin', $userData['email']);
            session()->set('idAdmin', $userData['id']);
            return redirect()->to('adminPage');
        } else {

            session()->setFlashdata('msgErr', 'Email atau Password anda salah!');
            return redirect()->to('adminPage/login');
        }
    }

    public function logout(){
        session()->destroy();
        return redirect()->to('welcome');
    }

    public function index()
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
            return redirect()->to('adminPage/login');
        } else {
            if (!empty($this->idAdmin)) {
                $data = [
                    'title' => 'Home | Admin Page',
                    'kategori' => $this->kategoriModel->getAllKategori(),
                    'buku' => $this->bukuModel->getListBuku(),
                ];
                return view('adminBuku/home', $data);
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }

    public function bukuPerKategori($idKategori)
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
            return redirect()->to('adminPage/login');
        } else {
            if (!empty($this->emailAdmin)) {
                $dataKategori = $this->kategoriModel->getAllKategori();
                foreach ($dataKategori as $k) {
                    $data = [
                        'title' => "{$dataKategori[$idKategori - 1]['namaKategori']} | Admin Page",
                        'kategori' => $this->kategoriModel->getAllKategori(),
                        'buku' => $this->bukuModel->getListBuku(false, $idKategori),
                    ];
                    return view('adminBuku/home', $data);
                }
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }

    public function detailBuku($slug)
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
            return redirect()->to('adminPage/login');
        } else {
            if (!empty($this->idAdmin)) {
                $dataDetail = $this->bukuModel->getDetailBuku($slug);
                $data = [
                    'title' => $dataDetail[0]['judulBuku'],
                    'buku' => $dataDetail
                ];

                return view('adminBuku/detailBuku', $data);
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }

    public function editBuku($slug)
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
            return redirect()->to('adminPage/login');
        } else {
            if (!empty($this->idAdmin)) {
                $newSlug = $this->bukuModel->escape($slug);
                //dd($this->bukuModel->getDetailBuku($newSlug,false));
                if (!empty($this->idAdmin)) {
                    $data = [
                        'title' => 'Edit Buku',
                        'dataBuku' => $this->bukuModel->getDetailBuku($newSlug),
                        'kategori' => $this->kategoriModel->getAllKategori()
                    ];
                    return view('adminBuku/editBuku', $data);
                } else {
                    return redirect()->to('adminPage/login');
                }
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }

    public function doEditBuku($slug)
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
            return redirect()->to('adminPage/login');
        } else {
            if (!empty($this->idAdmin)) {
                helper(['form']);
                helper('text');

                $post = $this->request->getPost(['judul', 'kategori', 'deskripsi', 'jumlah']);

                $getOldPdfName = $this->bukuModel->getDetailBuku($slug)[0]['linkPdfBuku'];
                $getOldCoverName = $this->bukuModel->getDetailBuku($slug)[0]['linkCoverBuku'];

                $newSlug = $this->bukuModel->escape($slug);
                $judulBuku = $this->bukuModel->escape($post['judul']);
                $kategori = $this->bukuModel->escape($post['kategori']);
                $deskripsi = $this->bukuModel->escape($post['deskripsi']);
                $jumlah = $this->bukuModel->escape($post['jumlah']);
                $coverBuku = $this->request->getFile('coverBuku');
                $pdfBuku = $this->request->getFile('pdfBuku');


                $validationErr = [
                    'pdfBuku' => [
                        'ext_in' => 'File hanya dapat berbentuk PDF saja!',
                        'max_size' => 'Maksimal ukuran file hanya 2048KB/2MB!'
                    ],
                    'coverBuku' => [
                        'is_image' => 'Mohon untuk menginput berupa foto!',
                        'mime_in' => 'Foto hanya dapat berbentuk JPEG/JPG/PNG saja!'
                    ]
                ];

                $validationRules = [
                    'pdfBuku' => [
                        'label' => 'File Buku PDF',
                        'rules' => [
                            'ext_in[pdfBuku,pdf]',
                            'max_size[pdfBuku,2048]'
                        ]
                    ],
                    'coverBuku' => [
                        'label' => 'Cover Buku',
                        'rules' => [
                            'is_image[coverBuku]',
                            'mime_in[coverBuku,image/jpg,image/jpeg,image/png]',
                        ]
                    ],
                ];

                if ($this->validate($validationRules, $validationErr)) {
                    if (empty($coverBuku->getName()) && empty($pdfBuku->getName())) {
                        $this->bukuModel->updateBuku($newSlug, $judulBuku, $kategori, $deskripsi, $jumlah, $getOldPdfName, $getOldCoverName);
                        return redirect()->to('adminPage');
                    } else
            if (empty($pdfBuku->getName())) {
                        $linkCover = '';
                        unlink('bukuAssets/cover/' . $getOldCoverName);

                        $linkCover = url_title($judulBuku, '-', true) . '.' . $coverBuku->getExtension();
                        $coverBuku->move('bukuAssets/cover', $linkCover);

                        $this->bukuModel->updateBuku($newSlug, $judulBuku, $kategori, $deskripsi, $jumlah, $getOldPdfName, $linkCover);

                        return redirect()->to('adminPage');
                    } else if (empty($coverBuku->getName())) {
                        $linkPdf = '';
                        unlink('bukuAssets/pdf/' . $getOldPdfName);
                        $linkPdf = url_title($judulBuku, '-', true) . '.pdf';
                        $pdfBuku->move('bukuAssets/pdf', $linkPdf, true);

                        $this->bukuModel->updateBuku($newSlug, $judulBuku, $kategori, $deskripsi, $jumlah, $linkPdf, $getOldCoverName);
                        return redirect()->to('adminPage');
                    } else {
                        $linkCover = '';
                        $linkPdf = '';

                        unlink('bukuAssets/cover/' . $getOldCoverName);
                        unlink('bukuAssets/pdf/' . $getOldPdfName);
                        $linkCover = url_title($judulBuku, '-', true) . '.' . $coverBuku->getExtension();
                        $coverBuku->move('bukuAssets/cover', $linkCover, true);

                        $linkPdf = url_title($judulBuku, '-', true) . '.pdf';
                        $pdfBuku->move('bukuAssets/pdf', $linkPdf, true);


                        $this->bukuModel->updateBuku($newSlug, $judulBuku, $kategori, $deskripsi, $jumlah, $linkPdf, $linkCover);

                        return redirect()->to('adminPage');
                    }
                } else {
                    $data = [
                        'title' => 'Tambah Buku',
                        'kategori' => $this->kategoriModel->getAllKategori(),
                        'validationErr' => $this->validator
                    ];
                    return view('adminBuku/addBuku', $data);
                }
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }

    public function deleteBuku($slug)
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
            return redirect()->to('adminPage/login');
        } else {
            if (!empty($this->idAdmin)) {
                $newSlug = $this->bukuModel->escape($slug);

                $getDataBuku = $this->bukuModel->getDetailBuku($slug);


                unlink('bukuAssets/cover/' . $getDataBuku[0]['linkCoverBuku']);
                unlink('bukuAssets/pdf/' . $getDataBuku[0]['linkPdfBuku']);


                $this->bukuModel->deleteBuku($newSlug);

                return redirect()->to('adminPage');
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }

    public function exportData($idKategori = false)
    {
        if (!empty(session()->get('idUser')) && !empty(session()->get('email'))) {
            session()->destroy();
            return redirect()->to('adminPage/login');
        } else {
            if (!empty($this->idAdmin)) {
                if ($idKategori == false) {
                    $dataBuku = $this->bukuModel->getListBuku();
                    $fileName = 'data_buku_keseluruhan.xlsx';
                } else {
                    $dataBuku = $this->bukuModel->getListBuku(false, $idKategori);
                    if (empty($dataBuku)) {
                        session()->setFlashdata('errMsg', 'Data pada kategori ini kosong');
                        return redirect()->to('adminPage');
                    } else {
                        $fileName = 'data_buku_' . $dataBuku[0]['namaKategori'] . '.xlsx';
                    }
                }

                $file = new Spreadsheet();
                $activeSheet = $file->getActiveSheet();
                $count = 2;

                $activeSheet->setCellValue('A1', 'Judul Buku');
                $activeSheet->setCellValue('B1', 'Diupload oleh');
                $activeSheet->setCellValue('C1', 'Nama Kategori');
                $activeSheet->setCellValue('D1', 'Deskripsi Buku');
                $activeSheet->setCellValue('E1', 'Jumlah Buku');
                $activeSheet->setCellValue('F1', 'Link PDF Buku');
                $activeSheet->setCellValue('G1', 'Link Cover Buku');
                $activeSheet->setCellValue('H1', 'Diupload pada');
                $activeSheet->setCellValue('I1', 'Terakhir diubah');


                foreach ($dataBuku as $data) {
                    $activeSheet->setCellValue('A' . $count, $data['judulBuku']);
                    $activeSheet->setCellValue('B' . $count, $data['namaUser']);
                    $activeSheet->setCellValue('C' . $count, $data['namaKategori']);
                    $activeSheet->setCellValue('D' . $count, $data['deskripsi']);
                    $activeSheet->setCellValue('E' . $count, $data['jumlah']);
                    $activeSheet->setCellValue('F' . $count, base_url('/bukuAssets/pdf/') . $data['linkPdfBuku']);
                    $activeSheet->setCellValue('G' . $count, base_url('/bukuAssets/cover/') . $data['linkCoverBuku']);
                    $activeSheet->setCellValue('H' . $count, $data['created_at']);
                    $activeSheet->setCellValue('I' . $count, $data['updated_at']);
                    $count = $count + 1;
                }

                $writer = IOFactory::createWriter($file, 'Xlsx');


                $writer->save($fileName);


                header("Content-Type: application/vnd.ms-excel");

                header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');

                header('Expires: 0');

                header('Cache-Control: must-revalidate');

                header('Pragma: public');

                header('Content-Length:' . filesize($fileName));

                readfile($fileName);

                unlink($fileName);
            } else {
                return redirect()->to('adminPage/login');
            }
        }
    }
}
