<?php

namespace Weew\HttpBlueprint;

use Weew\Url\IUrl;

interface IMappingsMatcher {
    /**
     * Find matching mapping.
     *
     * @param $requestMethod
     * @param IUrl $url
     * @param array $mappings
     *
     * @return mixed
     */
    function match($requestMethod, IUrl $url, array $mappings);
}
