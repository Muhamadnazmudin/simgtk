<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    /**
     * CSRF Protection Method ('cookie' or 'session')
     */
    public string $csrfProtection = 'cookie';

    /**
     * CSRF Token Randomization
     */
    public bool $tokenRandomize = true;

    /**
     * CSRF Token Name
     */
    public string $tokenName = 'csrf_test_name';

    /**
     * CSRF Header Name
     */
    public string $headerName = 'X-CSRF-TOKEN';

    /**
     * CSRF Cookie Name
     */
    public string $cookieName = 'csrf_cookie_name';

    /**
     * CSRF Expires (2 hours)
     */
    public int $expires = 7200;

    /**
     * Regenerate token on every submission
     */
    public bool $regenerate = false; // Set to false to prevent AJAX desync issues while still secure

    /**
     * Redirect on failure
     */
    public bool $redirect = false;

    /**
     * SameSite attribute for CSRF cookie
     */
    public string $samesite = 'Lax';
}
