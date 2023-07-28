<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $allowedFields = ['namaKategori'];
    protected $primaryKey = 'id';

    public function getAllKategori($idKategori = false)
    {
        if ($idKategori == false) {
            return $this->findAll();
        }
        $newId = str_replace("'", "", $idKategori);
        return $this->where("id = '$newId'")->first();
    }

    public function addKategori($namaKategori)
    {
        $newKategori = str_replace("'", "", $namaKategori);
        if (!empty($this->where('namaKategori', $newKategori)->find())) {
            return 'data_exist';
        } else {
            $this->insert([
                'namaKategori' => $newKategori
            ]);
        }
    }

    public function updateKategori($idKategori, $namaKategori)
    {
        $newIdKategori = str_replace("'", "", $idKategori);
        $newKategori = str_replace("'", "", $namaKategori);

        $this->set('namaKategori', $newKategori);

        $this->where('id', $newIdKategori);
        $this->update();
    }

    public function deleteKategori($idKategori)
    {
        $newIdKategori = str_replace("'", "", $idKategori);

        $this->where('id', $newIdKategori);
        $this->delete();
    }
}
