<?php

namespace DevopsToolMagento1PlatformSupport;

return [
    'invokables' => [
        \DevopsToolAppOrchestration\MaintenanceStrategy\MaintenanceStrategyInterface::class => AppMaintenanceStrategyFactory::class,
    ]
];
