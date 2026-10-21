<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Configures aliases for Filter classes.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        // Custom Mutases Filters
        'auth'          => \App\Filters\AuthFilter::class,
        'role'          => \App\Filters\RoleFilter::class,
        'siswaAuth'     => \App\Filters\SiswaAuthFilter::class,
        'rateLimiter'   => \App\Filters\RateLimitFilter::class,
    ];

    /**
     * List of special required filters.
     */
    public array $required = [
        'before' => [
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    /**
     * List of filter aliases that are always applied before and after every request.
     */
    public array $globals = [
        'before' => [
            'invalidchars',
            // CSRF is enabled globally, except for Webhooks/API if needed
            'csrf' => [
                'except' => [
                    'api/*',
                    'test_api.php',
                ]
            ],
        ],
        'after' => [
            'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a particular HTTP method.
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any before or after URI patterns.
     */
    public array $filters = [];
}
