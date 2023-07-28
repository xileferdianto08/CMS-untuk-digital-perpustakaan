<?php 
namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model{
    protected $table = 'admin';
    protected $allowedFields = ['id', 'nama', 'email', 'password'];
    protected $primaryKey = 'id';

    public function login($email, $password)
    {
        $newEmail = str_replace("'", "", $email);
        $newPwd = str_replace("'", "", $password);
        return $this->where("email = '$newEmail' AND password = '$newPwd'")->first();
    }
}

?>