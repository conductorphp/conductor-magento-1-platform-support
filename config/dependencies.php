<?php

namespace ConductorMagento1PlatformSupport;

return [
    'invokables' => [
        \ConductorAppOrchestration\MaintenanceStrategy\MaintenanceStrategyInterface::class => AppMaintenanceStrategyFactory::class,
    ]
];
