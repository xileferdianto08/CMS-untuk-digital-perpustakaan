<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\KategoriModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class Buku extends BaseController
{
    protected $bukuModel, $kategoriModel;

    public $idUser, $emailUser;
    public function __construct()
    {
        $this->bukuModel = new BukuModel();
        $this->kategoriModel = new KategoriModel();
        $this->idUser = session()->get('idUser');
        $this->emailUser = session()->get('email');
    }

    public function index()
    {
        if (!empty($this->emailUser)) {
            //dd($this->bukuModel->getListBuku(session()->get('idUser')));
            $data = [
                'title' => 'Home | Digital Perpustakaan',
                'kategori' => $this->kategoriModel->getAllKategori(),
                'buku' => $this->bukuModel->getListBuku($this->idUser),
            ];
            return view('buku/home', $data);
        } else {
            return redirect()->to('welcome');
        }
    }

    public function bukuPerKategori($idKategori)
    {
        if (!empty($this->emailUser)) {
            $dataKategori = $this->kategoriModel->getAllKategori();
            foreach ($dataKategori as $k) {
                $data = [
                    'title' => "{$dataKategori[$idKategori - 1]['namaKategori']} | Digital Perpustakaan",
                    'kategori' => $this->kategoriModel->getAllKategori(),
                    'buku' => $this->bukuModel->getListBuku($this->idUser, $idKategori),
                ];
                return view('buku/home', $data);
            }
        } else {
            return redirect()->to('welcome');
        }
    }

    public function addBuku()
    {
        if (!empty($this->emailUser)) {
            $data = [
                'title' => 'Tambah Buku',
                'kategori' => $this->kategoriModel->getAllKategori()
            ];
            return view('buku/addBuku', $data);
        } else {
            return redirect()->to('welcome');
        }
    }

    public function doAddBuku()
    {
        if (!empty($this->emailUser)) {
            helper(['form']);
            helper('text');

            $post = $this->request->getPost(['judul', 'kategori', 'deskripsi', 'jumlah']);

            $judulBuku = $this->bukuModel->escape($post['judul']);
            $kategori = $this->bukuModel->escape($post['kategori']);
            $deskripsi = $this->bukuModel->escape($post['deskripsi']);
            $jumlah = $this->bukuModel->escape($post['jumlah']);
            $coverBuku = $this->request->getFile('coverBuku');
            $pdfBuku = $this->request->getFile('pdfBuku');

            $validationErr = [
                'pdfBuku' => [
                    'uploaded' => 'File PDF buku wajib diinput!',
                    'ext_in' => 'File hanya dapat berbentuk PDF saja!',
                    'max_size' => 'Maksimal ukuran file hanya 2048KB/2MB!'
                ],
                'coverBuku' => [
                    'uploaded' => 'Cover Buku wajib diinput!',
                    'is_image' => 'Mohon untuk menginput berupa foto!',
                    'mime_in' => 'Foto hanya dapat berbentuk JPEG/JPG/PNG saja!'
                ]
            ];

            $validationRules = [
                'pdfBuku' => [
                    'label' => 'File Buku PDF',
                    'rules' => [
                        'uploaded[pdfBuku]',
                        'ext_in[pdfBuku,pdf]',
                        'max_size[pdfBuku,2048]'
                    ]
                ],
                'coverBuku' => [
                    'label' => 'Cover Buku',
                    'rules' => [
                        'uploaded[coverBuku]',
                        'is_image[coverBuku]',
                        'mime_in[coverBuku,image/jpg,image/jpeg,image/png]',
                    ]
                ],
            ];

            if ($this->validate($validationRules, $validationErr)) {

                $linkCover = '';

                if (!$coverBuku->hasMoved()) {
                    $linkCover = url_title($judulBuku, '-', true) . '.' . $coverBuku->getExtension();
                    $coverBuku->move('bukuAssets/cover', $linkCover);
                }



                $linkPdf = '';
                if (!$pdfBuku->hasMoved()) {
                    $linkPdf = url_title($judulBuku, '-', true) . '.pdf';
                    $pdfBuku->move('bukuAssets/pdf', $linkPdf);
                }

                $this->bukuModel->addBuku($judulBuku, $this->idUser, $kategori, $deskripsi, $jumlah, $linkPdf, $linkCover);

                return redirect()->to('buku');
            } else {
                $data = [
                    'title' => 'Tambah Buku',
                    'kategori' => $this->kategoriModel->getAllKategori(),
                    'validationErr' => $this->validator
                ];
                return view('buku/addBuku', $data);
            }
        } else {
            return redirect()->to('welcome');
        }
    }

    public function detailBuku($slug)
    {
        if (!empty($this->emailUser)) {
            $dataDetail = $this->bukuModel->getDetailBuku($slug, $this->idUser);
            $data = [
                'title' => $dataDetail[0]['judulBuku'],
                'buku' => $dataDetail
            ];

            return view('buku/detailBuku', $data);
        } else {
            return redirect()->to('welcome');
        }
    }

    public function editBuku($slug)
    {
        $newSlug = $this->bukuModel->escape($slug);
        if (!empty($this->emailUser)) {
            $data = [
                'title' => 'Edit Buku',
                'dataBuku' => $this->bukuModel->getDetailBuku($newSlug, $this->idUser),
                'kategori' => $this->kategoriModel->getAllKategori()
            ];
            return view('buku/editBuku', $data);
        } else {
            return redirect()->to('welcome');
        }
    }

    public function doEditBuku($slug)
    {
        if (!empty($this->emailUser)) {
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
                    $this->bukuModel->updateBuku($newSlug, $judulBuku, $kategori, $deskripsi, $jumlah, $getOldPdfName, $getOldCoverName, $this->idUser);
                    return redirect()->to('buku');
                } else
            if (empty($pdfBuku->getName())) {
                    $linkCover = '';
                    unlink('bukuAssets/cover/' . $getOldCoverName);

                    $linkCover = url_title($judulBuku, '-', true) . '.' . $coverBuku->getExtension();
                    $coverBuku->move('bukuAssets/cover', $linkCover);

                    $this->bukuModel->updateBuku($newSlug, $judulBuku, $kategori, $deskripsi, $jumlah, $getOldPdfName, $linkCover, $this->idUser);

                    return redirect()->to('buku');
                } else if (empty($coverBuku->getName())) {
                    $linkPdf = '';
                    unlink('bukuAssets/pdf/' . $getOldPdfName);
                    $linkPdf = url_title($judulBuku, '-', true) . '.pdf';
                    $pdfBuku->move('bukuAssets/pdf', $linkPdf, true);

                    $this->bukuModel->updateBuku($newSlug, $judulBuku, $kategori, $deskripsi, $jumlah, $linkPdf, $getOldCoverName, $this->idUser);
                    return redirect()->to('buku');
                } else {
                    $linkCover = '';
                    $linkPdf = '';

                    unlink('bukuAssets/cover/' . $getOldCoverName);
                    unlink('bukuAssets/pdf/' . $getOldPdfName);
                    $linkCover = url_title($judulBuku, '-', true) . '.' . $coverBuku->getExtension();
                    $coverBuku->move('bukuAssets/cover', $linkCover, true);

                    $linkPdf = url_title($judulBuku, '-', true) . '.pdf';
                    $pdfBuku->move('bukuAssets/pdf', $linkPdf, true);


                    $this->bukuModel->updateBuku($newSlug, $judulBuku, $kategori, $deskripsi, $jumlah, $linkPdf, $linkCover,  $this->idUser);

                    return redirect()->to('buku');
                }
            } else {
                $data = [
                    'title' => 'Tambah Buku',
                    'kategori' => $this->kategoriModel->getAllKategori(),
                    'validationErr' => $this->validator
                ];
                return view('buku/addBuku', $data);
            }
        } else {
            return redirect()->to('welcome');
        }
    }

    public function deleteBuku($slug)
    {
        if (!empty($this->emailUser)) {
            $newSlug = $this->bukuModel->escape($slug);

            $getDataBuku = $this->bukuModel->getDetailBuku($slug, $this->idUser);


            unlink('bukuAssets/cover/' . $getDataBuku[0]['linkCoverBuku']);
            unlink('bukuAssets/pdf/' . $getDataBuku[0]['linkPdfBuku']);


            $this->bukuModel->deleteBuku($newSlug, $this->idUser);

            return redirect()->to('buku');
        } else {
            return redirect()->to('welcome');
        }
    }

    public function exportData($idKategori = false)
    {
        if (!empty($this->emailUser)) {
            if ($idKategori == false) {
                $dataBukuUser = $this->bukuModel->getListBuku($this->idUser);
                $fileName = 'data_buku_' . $dataBukuUser[0]['namaUser'] . '_keseluruhan.xlsx';
            } else {
                $dataBukuUser = $this->bukuModel->getListBuku($this->idUser, $idKategori);
                if (empty($dataBukuUser)) {
                    session()->setFlashdata('errMsg', 'Data pada kategori ini kosong');
                    return redirect()->to('buku');
                } else {
                    $fileName = 'data_buku_' . $dataBukuUser[0]['namaUser'] . '_' . $dataBukuUser[0]['namaKategori'] . '.xlsx';
                }
            }

            $file = new Spreadsheet();
            $activeSheet = $file->getActiveSheet();
            $count = 2;

            $activeSheet->setCellValue('A1', 'Judul Buku');
            $activeSheet->setCellValue('B1', 'Nama Kategori');
            $activeSheet->setCellValue('C1', 'Deskripsi Buku');
            $activeSheet->setCellValue('D1', 'Jumlah Buku');
            $activeSheet->setCellValue('E1', 'Link PDF Buku');
            $activeSheet->setCellValue('F1', 'Link Cover Buku');
            $activeSheet->setCellValue('G1', 'Diupload pada');
            $activeSheet->setCellValue('H1', 'Terakhir diubah');


            foreach ($dataBukuUser as $data) {
                $activeSheet->setCellValue('A' . $count, $data['judulBuku']);
                $activeSheet->setCellValue('B' . $count, $data['namaKategori']);
                $activeSheet->setCellValue('C' . $count, $data['deskripsi']);
                $activeSheet->setCellValue('D' . $count, $data['jumlah']);
                $activeSheet->setCellValue('E' . $count, base_url('/bukuAssets/pdf/') . $data['linkPdfBuku']);
                $activeSheet->setCellValue('F' . $count, base_url('/bukuAssets/cover/') . $data['linkCoverBuku']);
                $activeSheet->setCellValue('G' . $count, $data['created_at']);
                $activeSheet->setCellValue('H' . $count, $data['updated_at']);
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
            return redirect()->to('welcome');
        }
    }
}
