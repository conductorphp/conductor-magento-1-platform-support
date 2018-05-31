<?php

namespace ConductorMagento1PlatformSupport\Maintenance;

use ConductorAppOrchestration\Config\ApplicationConfig;
use ConductorAppOrchestration\Maintenance\MaintenanceStrategyInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class AppMaintenanceStrategy implements MaintenanceStrategyInterface, LoggerAwareInterface
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    /**
     * AppMaintenanceStrategy constructor.
     *
     * @param ApplicationConfig $applicationConfig
     */
    public function __construct(ApplicationConfig $applicationConfig)
    {
        $this->applicationConfig = $applicationConfig;
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param string|null       $branch
     */
    public function enable(string $branch = null): void
    {
        // @todo Write these files to each server in parallel with amphp
        // TODO: Implement enable() method.
        throw new \LogicException(__METHOD__ . ' not yet implemented.');
//        foreach ($this->getServers() as $serverName => $server) {
//            $filesystem = $this->getServerFilesystem($serverName, $server);
//            $filesystem->put('maintenance.flag', '');
//        }
    }

    /**
     * @param string|null       $branch
     */
    public function disable(string $branch = null): void
    {
        // @todo Delete these files on each server in parallel with amphp
        // TODO: Implement enable() method.
        throw new \LogicException(__METHOD__ . ' not yet implemented.');
//        foreach ($this->getServers() as $serverName => $server) {
//            $filesystem = $this->getServerFilesystem($serverName, $server);
//            $filesystem->delete('maintenance.flag');
//        }
    }

    /**
     * @param string|null       $branch
     *
     * @return bool
     */
    public function isEnabled(string $branch = null): bool
    {
        // TODO: Implement enable() method.
        throw new \LogicException(__METHOD__ . ' not yet implemented.');
//
//        $servers = $this->getServers();
//        $serversInMaintenance = [];
//
//        // @todo Run these checks in parallel with amphp
//        foreach ($servers as $serverName => $server) {
//            $filesystem = $this->getServerFilesystem($serverName, $server);
//            if ($filesystem->has('maintenance.flag')) {
//                $serversInMaintenance[$serverName] = true;
//            }
//        }
//
//        if (!$serversInMaintenance) {
//            return false;
//        }
//
//        if (count($serversInMaintenance) == count($servers)) {
//            return true;
//        }
//
//        $serversNotInMaintenance = array_diff_key($servers, $serversInMaintenance);
//        throw new Exception\RuntimeException(sprintf(
//            'Maintenance mode enabled, but servers "%s" are missing maintenance flag.',
//            implode(', ', array_keys($serversNotInMaintenance))
//        ));
    }

    /**
     * @return array
     */
    protected function getServers(): array
    {
        $servers = $this->applicationConfig->getServers();
        if (empty($servers)) {
            $servers = [
                'localhost' => [
                    'host' => '127.0.0.1',
                ],
            ];
        }

        return $servers;
    }

    /**
     * @param string            $serverName
     * @param array             $server
     *
     * @return Filesystem
     * @throws Exception\RuntimeException if server configuration is invalid
     */
    protected function getServerFilesystem(string $serverName, array $server): Filesystem
    {
        if (empty($server['host'])) {
            throw new Exception\RuntimeException(sprintf(
                'Server "%s" missing "host" config key.',
                $serverName
            ));
        }

        if (in_array($server['host'], ['127.0.0.1', 'localhost'])) {
            $adapter = new Local($this->applicationConfig->getCodePath());
        } else {
            $adapter = new SftpAdapter(
                array_merge(
                    $this->applicationConfig->getSshDefaults(),
                    $server,
                    ['root' => $this->applicationConfig->getCodePath()]
                )
            );
        }
        return new Filesystem($adapter);
    }
}
