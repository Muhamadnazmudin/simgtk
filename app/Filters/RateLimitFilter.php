<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Rate Limiter Filter - Protects against Brute-Force & DDoS
 */
class RateLimitFilter implements FilterInterface
{
    /**
     * @param array|null $arguments [maxRequests, timeWindowSeconds]
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();

        // Unique key based on IP address and request path
        $ip = $request->getIPAddress();
        $path = $request->getUri()->getPath();
        $key = 'rl_' . md5($ip . '_' . $path);

        // Default limit: 60 requests per minute
        $maxRequests = 60;
        $timeWindow = 60;

        if (!empty($arguments) && is_array($arguments)) {
            $maxRequests = (int) ($arguments[0] ?? 60);
            $timeWindow = (int) ($arguments[1] ?? 60);
        }

        // Check rate limit
        if ($throttler->check($key, $maxRequests, $timeWindow) === false) {
            $response = Services::response();
            
            if ($request->isAJAX()) {
                return $response
                    ->setStatusCode(429)
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Terlalu banyak permintaan (Rate limit exceeded). Silakan coba lagi dalam beberapa saat.'
                    ]);
            }

            return $response
                ->setStatusCode(429)
                ->setBody('<h1>429 - Terlalu Banyak Permintaan</h1><p>Sistem mendeteksi aktivitas berlebih dari IP Anda. Mohon tunggu sejenak sebelum mencoba kembali demi keamanan server.</p>');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
