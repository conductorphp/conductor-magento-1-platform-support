<?php

namespace DevopsToolMagento1PlatformSupport;

use Symfony\Component\Yaml\Yaml;

class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'app_setup' => [
                'platforms' => [
                    'magento1' => $this->getAppSetup(),
                ],
            ],
        ];
    }

    private function getAppSetup()
    {
        return array_merge(
            [
                'file_root' => dirname(__DIR__) . '/files',
            ],
            Yaml::parse(file_get_contents(__DIR__ . '/../config/app_setup.yaml'))
        );
    }

}