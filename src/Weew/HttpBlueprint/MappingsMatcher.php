<?php

namespace Weew\HttpBlueprint;

use Weew\Url\IUrl;

class MappingsMatcher implements IMappingsMatcher {
    /**
     * @param $requestMethod
     * @param IUrl $url
     * @param array $mappings
     *
     * @return null|Mapping
     */
    public function match($requestMethod, IUrl $url, array $mappings) {
        $candidate = null;

        /** @var Mapping $mapping */
        foreach ($mappings as $mapping) {
            if ( ! $this->matchRequestMethod($requestMethod, $mapping)) {
                continue;
            }

            if ( ! $this->matchUrl($url, $mapping)) {
                continue;
            }

            $candidate = $mapping;
        }

        return $candidate;
    }

    /**
     * @param $requestMethod
     * @param Mapping $mapping
     *
     * @return bool
     */
    public function matchRequestMethod($requestMethod, Mapping $mapping) {
        return $requestMethod == $mapping->getRequestMethod();
    }

    /**
     * @param IUrl $url
     * @param Mapping $mapping
     *
     * @return bool
     */
    public function matchUrl(IUrl $url, Mapping $mapping) {
        $urlPath = $url->getPath();
        $mappingPath = $mapping->getUrl()->getPath();

        return $urlPath == $mappingPath;
    }
}
