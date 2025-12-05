<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Feature extends BaseConfig
{
    /**
     * Disable improved auto routing (default: true).
     * Must be false when using manual routing.
     */
    public bool $autoRoutesImproved = false;

    /**
     * Disable old auto routing.
     */
    public bool $autoRoutes = false;

    /**
     * Use filter execution order in 4.4 or before.
     */
    public bool $oldFilterOrder = false;

    /**
     * The behavior of `limit(0)` in Query Builder.
     */
    public bool $limitZeroAsAll = true;

    /**
     * Locale strict mode.
     */
    public bool $strictLocaleNegotiation = false;
}
