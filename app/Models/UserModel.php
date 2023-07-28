<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    // protected $useTimestamps = true;
    protected $allowedFields = ['id', 'nama', 'email', 'password'];
    protected $primaryKey = 'id';

    public function addUser($nama, $email, $password)
    {
        $newNama = str_replace("'", "", $nama);
        $newEmail = str_replace("'", "", $email);
        $newPwd = str_replace("'", "", $password);

        if (!empty($this->where("email = '$newEmail'")->first())) {
            return 'data_exist';
        } else {
            $this->insert([
                'id' => bin2hex(random_bytes(5)),
                'nama' => $newNama,
                'email' => $newEmail,
                'password' => $newPwd,
            ]);
        }
    }

    public function login($email, $password)
    {
        $newEmail = str_replace("'", "", $email);
        $newPwd = str_replace("'", "", $password);
        return $this->where("email = '$newEmail' AND password = '$newPwd'")->first();
    }
}
