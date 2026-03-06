<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        if ($this->request->is('post')) {
            $username = strtolower($this->request->getPost('username'));
            $password = $this->request->getPost('password');

            $db = \Config\Database::connect();
            $user = $db->table('users')->where('LOWER(username)', $username)->get()->getRow();

            if ($user && password_verify($password, $user->password)) {
                // Get Role
                $roleObj = $db->table('user_roles')
                             ->select('roles.name')
                             ->join('roles', 'roles.id = user_roles.role_id')
                             ->where('user_id', $user->id)
                             ->get()->getRow();
                
                $role = $roleObj ? $roleObj->name : 'User';

                // Get Permissions
                $permissions = [];
                if ($role !== 'Admin') {
                    $permsObj = $db->table('role_permissions')
                                  ->select('permissions.name')
                                  ->join('permissions', 'permissions.id = role_permissions.permission_id')
                                  ->join('user_roles', 'user_roles.role_id = role_permissions.role_id')
                                  ->where('user_roles.user_id', $user->id)
                                  ->get()->getResultArray();
                    $permissions = array_column($permsObj, 'name');
                }

                session()->set([
                    'isLoggedIn'   => true,
                    'user_id'      => $user->id,
                    'username'     => $user->username,
                    'fullname'     => $user->fullname,
                    'role'         => $role,
                    'permissions'  => $permissions,
                    'active_env'   => 'Pelayanan'
                ]);

                return redirect()->to(base_url('workspace'));
            }

            return redirect()->back()->with('error', 'Username atau password salah.');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }

    public function switchEnv($env)
    {
        $allowedEnvs = ['Pelayanan', 'Penunjang', 'Administrasi', 'Sistem'];
        if (in_array($env, $allowedEnvs)) {
            session()->set('active_env', $env);
        }
        return redirect()->back();
    }
}
