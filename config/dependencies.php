<?php

namespace ConductorMagento1PlatformSupport;

return [
    'factories' => [
        \ConductorAppOrchestration\Maintenance\MaintenanceStrategyInterface::class => Maintenance\AppMaintenanceStrategyFactory::class,
    ],
    'aliases' => [
        \ConductorAppOrchestration\Deploy\CodeDeploymentStateInterface::class      => Deploy\CodeDeploymentState::class,
    ]
];
