<?php

namespace ConductorMagento1PlatformSupport;

// This module registers its strategies under its own platform key in ConfigProvider and never under
// the application-orchestration interface names. Binding the interfaces here is what made platform
// modules mutually exclusive: last ConfigProvider won (CTAP-1757).
return [
    'factories' => [
        Maintenance\AppMaintenanceStrategy::class => Maintenance\AppMaintenanceStrategyFactory::class,
    ],
];
