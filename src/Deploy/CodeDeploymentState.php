<?php

namespace ConductorMagento1PlatformSupport\Deploy;

use ConductorAppOrchestration\Config\ApplicationConfig;
use ConductorAppOrchestration\Deploy\CodeDeploymentStateInterface;
use ConductorCore\Shell\Adapter\ShellAdapterInterface;

class CodeDeploymentState implements CodeDeploymentStateInterface
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;
    /**
     * @var ShellAdapterInterface
     */
    private $shellAdapter;

    /**
     * CodeDeploymentState constructor.
     *
     * @param ApplicationConfig     $applicationConfig
     * @param ShellAdapterInterface $shellAdapter
     */
    public function __construct(ApplicationConfig $applicationConfig, ShellAdapterInterface $shellAdapter)
    {
        $this->applicationConfig = $applicationConfig;
        $this->shellAdapter = $shellAdapter;
    }

    /**
     * @inheritdoc
     */
    public function codeDeployed(): bool
    {
        $documentRoot = $this->applicationConfig->getDocumentRoot();

        $command = '[[ -f ' . escapeshellcmd("$documentRoot/index.php") . ' ]] || exit 1';
        try {
            $this->shellAdapter->runShellCommand($command);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}