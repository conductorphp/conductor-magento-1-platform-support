<?php

namespace ConductorMagento1PlatformSupport\Maintenance;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class AppMaintenanceStrategyFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        ?array $options = null
    ): AppMaintenanceStrategy {
        $applicationConfig = $container->get(\ConductorAppOrchestration\Config\ApplicationConfig::class);
        return new AppMaintenanceStrategy($applicationConfig);
    }

}

