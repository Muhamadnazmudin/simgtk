<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Config\Services as AppServices;
use Config\Paths;
use Config\View as ViewConfig;
use App\Libraries\CompatView;

/**
 * Services Configuration file.
 */
class Services extends BaseService
{
    /**
     * Override renderer to use CompatView for CI3 view compatibility
     */
    public static function renderer(?string $viewPath = null, ?ViewConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('renderer', $viewPath, $config);
        }

        $viewPath = in_array($viewPath, [null, '', '0'], true) ? (new Paths())->viewDirectory : $viewPath;
        $config ??= config(ViewConfig::class);

        return new CompatView(
            $config,
            $viewPath,
            AppServices::get('locator'),
            CI_DEBUG,
            AppServices::get('logger')
        );
    }

    public static function compatView(bool $getShared = true)
    {
        return static::renderer(null, null, $getShared);
    }
}
