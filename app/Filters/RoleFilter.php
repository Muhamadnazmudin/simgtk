<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Role-Based Access Control Filter
 */
class RoleFilter implements FilterInterface
{
    /**
     * @param array|null $arguments Allowed role_ids (e.g., ['1', '2'] or ['admin'])
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth');
        }

        if (empty($arguments)) {
            return;
        }

        $userRoleId = (string) $session->get('role_id');
        $userRoleName = strtolower((string) ($session->get('role_name') ?? ''));

        $allowed = false;
        foreach ($arguments as $arg) {
            $arg = strtolower(trim((string) $arg));
            if ($arg === $userRoleId || $arg === $userRoleName) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            $response = response();
            $response->setStatusCode(403);
            return $response->setBody(view('errors/html/error_403', [
                'message' => 'Anda tidak memiliki hak akses untuk membuka halaman ini.'
            ]));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
