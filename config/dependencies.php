<?php

namespace ConductorMagento1PlatformSupport;

return [
    'factories' => [
        \ConductorAppOrchestration\MaintenanceStrategy\MaintenanceStrategyInterface::class => AppMaintenanceStrategyFactory::class,
    ]
];
