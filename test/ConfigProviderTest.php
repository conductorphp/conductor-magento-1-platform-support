<?php

namespace ConductorMagento1PlatformSupportTest;

use ConductorAppOrchestration\Deploy\CodeDeploymentStateInterface;
use ConductorAppOrchestration\Maintenance\MaintenanceStrategyInterface;
use ConductorAppOrchestration\Platform\PlatformManager;
use ConductorMagento1PlatformSupport\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    private ConfigProvider $configProvider;

    public function setUp(): void
    {
        $this->configProvider = new ConfigProvider();
    }

    public function testInvoke(): void
    {
        $configProvider = $this->configProvider;
        $this->assertIsArray($configProvider());
    }

    public function testRegistersItsStrategiesUnderItsOwnPlatformKey(): void
    {
        $config   = ($this->configProvider)();
        $services = $config['application_orchestration']['platforms']['magento1']['services'];

        $this->assertSame(\ConductorMagento1PlatformSupport\Maintenance\AppMaintenanceStrategy::class, $services[PlatformManager::SERVICE_MAINTENANCE_STRATEGY]);
        $this->assertTrue(is_a(\ConductorMagento1PlatformSupport\Maintenance\AppMaintenanceStrategy::class, MaintenanceStrategyInterface::class, true));
        $this->assertSame(\ConductorMagento1PlatformSupport\Deploy\CodeDeploymentState::class, $services[PlatformManager::SERVICE_CODE_DEPLOYMENT_STATE]);
        $this->assertTrue(is_a(\ConductorMagento1PlatformSupport\Deploy\CodeDeploymentState::class, CodeDeploymentStateInterface::class, true));
    }

    /**
     * Binding the interface is what made platform modules mutually exclusive: with two installed, the
     * last ConfigProvider in the host won. PlatformManager resolves them per configured platform now.
     */
    public function testDoesNotBindAnApplicationOrchestrationOrCoreInterface(): void
    {
        $dependencies = ($this->configProvider)()['dependencies'];
        $this->assertIsArray($dependencies);

        foreach (['aliases', 'factories', 'invokables', 'services', 'delegators'] as $section) {
            foreach (array_keys($dependencies[$section] ?? []) as $serviceName) {
                $this->assertFalse(
                    interface_exists($serviceName)
                    && (str_starts_with($serviceName, 'ConductorCore\\')
                        || str_starts_with($serviceName, 'ConductorAppOrchestration\\')),
                    "dependencies.$section binds $serviceName; register the implementation under the platform key instead",
                );
            }
        }
    }
}
