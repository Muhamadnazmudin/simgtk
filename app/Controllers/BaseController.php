<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides common tools, sessions, database connections,
 * and view rendering helpers for all Mutases CI4 controllers.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'compat', 'text', 'security'];

    /**
     * Database connection
     */
    protected BaseConnection $db;

    /**
     * Session service
     */
    protected Session $session;

    /**
     * Current authenticated user array/object
     */
    protected ?array $user = null;

    /**
     * Compatibility View renderer
     */
    protected \App\Libraries\CompatView $compatView;

    /**
     * @param CLIRequest|IncomingRequest $request
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load helpers before parent init
        parent::initController($request, $response, $logger);

        // Preload database, session, and compatView
        $this->db = \Config\Database::connect();
        $this->session = service('session');
        $this->compatView = service('compatView');

        if ($this->session->get('logged_in')) {
            $this->user = [
                'id'        => $this->session->get('user_id'),
                'username'  => $this->session->get('username'),
                'nama'      => $this->session->get('nama'),
                'role_id'   => $this->session->get('role_id'),
                'role_name' => $this->session->get('role_name'),
                'guru_id'   => $this->session->get('guru_id'),
                'tahun_id'  => $this->session->get('tahun_id'),
            ];
        }
    }

    /**
     * Get active academic year ID (tahun_id)
     */
    protected function tahunAktifId(): int
    {
        $tahunId = (int) $this->session->get('tahun_id');
        if ($tahunId > 0) {
            return $tahunId;
        }

        $row = $this->db->table('tahun_ajaran')
            ->where('aktif', 1)
            ->limit(1)
            ->get()
            ->getRow();

        return $row ? (int) $row->id : 0;
    }

    /**
     * Render template view with header, sidebar, and footer
     */
    protected function renderView(string $view, array $data = []): string
    {
        // Inject standard global data
        $data['csrf_name'] = csrf_token();
        $data['csrf_hash'] = csrf_hash();
        $data['user'] = $this->user;
        $data['tahun_id'] ??= $this->tahunAktifId();

        // Determine which sidebar to load (Guru vs Admin/Staff)
        $roleId = (int) $this->session->get('role_id');
        $sidebarView = ($roleId === 3 || $roleId === 4) ? 'templates/sidebar_guru' : 'templates/sidebar';

        return view('templates/header', $data)
             . view($sidebarView, $data)
             . view($view, $data)
             . view('templates/footer', $data);
    }

    /**
     * JSON Response Helper for AJAX
     */
    protected function responseJson(array $data, int $statusCode = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON($data);
    }
}
