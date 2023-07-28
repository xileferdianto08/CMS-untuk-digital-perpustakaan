<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table = 'buku';
    protected $allowedFields = ['id', 'judulBuku', 'slug', 'idUser', 'idKategori', 'deskripsi', 'jumlah', 'linkPdfBuku', 'linkCoverBuku'];
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useTimestamps = true;

    public function addBuku($judulBuku, $idUser, $idKategori, $deskripsi, $jumlah, $linkPdf, $linkCover)
    {
        $newJudul = str_replace("'", "", $judulBuku);
        $newIdUser = str_replace("'", "", $idUser);
        $newIdKategori = str_replace("'", "", $idKategori);
        $newDeskripsi = str_replace("'", "", $judulBuku);
        $newJumlah = str_replace("'", "", $jumlah);
        $newLinkPdf = str_replace("'", "", $linkPdf);
        $newLinkCover = str_replace("'", "", $linkCover);


        $this->insert([
            'judulBuku' => $newJudul,
            'slug' => url_title($newJudul, '-', true),
            'idUser' => $newIdUser,
            'idKategori' => $newIdKategori,
            'deskripsi' => $newDeskripsi,
            'jumlah' => $newJumlah,
            'linkPdfBuku' => $newLinkPdf,
            'linkCoverBuku' => $newLinkCover
        ]);
    }

    public function getListBuku($idUser = false, $idKategori = false)
    {
        //for admin only!
        if ($idUser == false) {
            if ($idKategori == false) {
                return $this->select('*, u.nama AS namaUser, k.namaKategori AS namaKategori,')->join('user u', 'buku.idUser = u.id')->join('kategori k', 'buku.idKategori = k.id')->orderBy('updated_at', 'DESC')->findAll();
            } else if ($idKategori != false) {
                //filter by kategori
                return $this->select('*, u.nama AS namaUser, k.namaKategori AS namaKategori,')->where('buku.idKategori', $idKategori)->join('user u', 'buku.idUser = u.id')->join('kategori k', 'buku.idKategori = k.id')->orderBy('updated_at', 'DESC')->findAll();
            }
        } else {
            if ($idKategori == false) {
                return $this->select('*, u.nama AS namaUser, k.namaKategori AS namaKategori,')->where('buku.idUser', $idUser)->join('user u', 'u.id = buku.idUser ')->join('kategori k', 'k.id = buku.idKategori')->orderBy('buku.updated_at', 'DESC')->findAll();
            } else if ($idKategori != false) {
                //filter by kategori
                return $this->select('*, u.nama AS namaUser, k.namaKategori AS namaKategori')->where("idUser = '$idUser' AND idKategori = '$idKategori'")->join('user u', 'buku.idUser = u.id')->join('kategori k', 'buku.idKategori = k.id')->orderBy('updated_at', 'DESC')->findAll();
            }
        }
    }

    public function getDetailBuku($slug, $idUser = false)
    {
        $newSlug = str_replace("'", "", $slug);
        if ($idUser == false) {
            return $this->select('*, u.nama AS namaUser, k.namaKategori AS namaKategori,')->where('buku.slug', $newSlug)->join('user u', 'u.id = buku.idUser ')->join('kategori k', 'k.id = buku.idKategori')->find();
        } else {
            $newIdUser = str_replace("'", "", $idUser);
            return $this->select('*, u.nama AS namaUser, k.namaKategori AS namaKategori,')->where("buku.slug = '$newSlug' AND buku.idUser = '$newIdUser'")->join('user u', 'u.id = buku.idUser ')->join('kategori k', 'k.id = buku.idKategori')->find();
        }
    }

    public function updateBuku($slug, $judulBuku, $idKategori, $deskripsi, $jumlah, $linkPdf, $linkCover, $idUser = false)
    {
        $newSlug = str_replace("'", "", $slug);
        $newJudul = str_replace("'", "", $judulBuku);
        $newIdKategori = str_replace("'", "", $idKategori);
        // $newDeskripsi = str_replace("'","",$judulBuku);
        $newJumlah = str_replace("'", "", $jumlah);
        $newLinkPdf = str_replace("'", "", $linkPdf);
        $newLinkCover = str_replace("'", "", $linkCover);

        if ($idUser == false) {
            $this->set([
                'judulBuku' => str_replace(array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''), "", $newJudul),
                'slug' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    url_title($newJudul, '-', true)
                ),
                'idKategori' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $newIdKategori
                ),
                'deskripsi' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $deskripsi
                ),
                'jumlah' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $newJumlah
                ),
                'linkPdfBuku' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $linkPdf
                ),
                'linkCoverBuku' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $linkCover
                )
            ]);
            $this->where('slug', $newSlug);
            $this->update();
        } else {
            $newIdUser = str_replace("'", "", $idUser);
            $this->set([
                'judulBuku' => str_replace(array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''), "", $newJudul),
                'slug' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    url_title($newJudul, '-', true)
                ),
                'idKategori' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $newIdKategori
                ),
                'deskripsi' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $deskripsi
                ),
                'jumlah' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $newJumlah
                ),
                'linkPdfBuku' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $newLinkPdf
                ),
                'linkCoverBuku' => str_replace(
                    array("\r", "\n", '\r', '\n', "'", "\"", "\\", '\''),
                    "",
                    $newLinkCover
                )
            ]);
            $this->where("slug = '$newSlug' AND idUser = '$newIdUser'");
            $this->update();
        }
    }

    public function deleteBuku($slug, $idUser = false)
    {
        $newSlug = str_replace("'", "", $slug);

        if ($idUser == false) {
            $this->where('slug', $newSlug);
            $this->delete();
        } else {
            $newIdUser = str_replace("'", "", $idUser);
            $this->where("slug = '$newSlug' AND idUser = '$newIdUser'");
            $this->delete();
        }
    }
}
