<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (empty($arguments)) {
            return;
        }

        helper('auth');
        
        $authorized = false;
        foreach ($arguments as $permission) {
            if (has_permission($permission)) {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            return redirect()->to(base_url('unauthorized'))->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
