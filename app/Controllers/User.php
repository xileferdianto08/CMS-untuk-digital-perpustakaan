<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;


    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        $data = [
            'title' => 'Login',
        ];
        return view('auth/login', $data);
    }

    public function doLogin()
    {
        helper(['form']);
        helper('text');

        $session = \Config\Services::session();

        $post = $this->request->getPost(['email', 'password']);

        $email = $this->userModel->escape($post['email']);
        $hashedPwd = hash('sha512', $post['password']);
        $password = $this->userModel->escape($hashedPwd);
        $validationRules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];


        $userData = $this->userModel->login($email, $password);

        if (!empty($userData)) {
            session()->set('email', $userData['email']);
            session()->set('idUser', $userData['id']);
            return redirect()->to('buku');
        } else {
            
            session()->setFlashdata('msgErr', 'Email atau Password anda salah!');
            return redirect()->to('user/login');
        }
    }

    public function register()
    {
        $data = [
            'title' => 'Register',
        ];
        return view('auth/register', $data);
    }

    public function doRegister()
    {
        helper(['form']);
        helper('text');

        $post = $this->request->getPost(['nama', 'email', 'password', 'confirm-pwd']);

        $nama = $this->userModel->escape($post['nama']);
        $email = $this->userModel->escape($post['email']);
        $hashedPwd = hash('sha512', $post['password']);
        $password = $this->userModel->escape($hashedPwd);

        $validationErr = [
             'nama' => [
                'required'=>'Nama wajib diisi',
            ],
             'email' => [
                'required'=>'Email wajib diisi',
                'valid_email'=> 'Email harus valid!'
            ],
             'password' => [
                'required'=>'Password wajib diisi',
                'min_length'=>'Password minimal {param} karakter!',
                'alpha_numeric' => 'Password wajib terdiri dari huruf dan angka!'
            ],
             'confirm-pwd' => [
                'required'=>'Konfirmasi Password wajib diisi',
                'matches' => 'Input tidak sama dengan password sebelumnya'
            ],
        ];

        $validationRules = [
            'nama' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]|alpha_numeric',
            'confirm-pwd' => 'required|matches[password]'
        ];



        if ($this->validate($validationRules)) {
            $addData = $this->userModel->addUser($nama, $email, $password);

            if ($addData == 'data_exist') {
                $data = [
                    'title' => 'Register',
                ];
                session()->setFlashdata('msg', 'Data anda sudah terdaftar, silahkan login!');
                echo view('auth/register', $data);
            } else {

                session()->setFlashdata('msg', 'User berhasil ditambahkan!');

                return redirect()->to('user/login');
            }
        } else {
            $data = [
                'title' => 'Register',
                'validationErr' => $this->validator
            ];
            echo view('auth/register', $data);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('welcome');
    }
}
