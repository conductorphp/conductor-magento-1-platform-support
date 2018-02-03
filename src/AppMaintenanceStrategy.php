<?php

namespace DevopsToolMagento1PlatformSupport;

use DevopsToolAppOrchestration\ApplicationConfig;
use DevopsToolAppOrchestration\MaintenanceStrategy\MaintenanceStrategyInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class AppMaintenanceStrategy implements MaintenanceStrategyInterface, LoggerAwareInterface
{
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
     * @param ApplicationConfig $application
     * @param string|null       $branch
     */
    public function enable(ApplicationConfig $application, string $branch = null): void
    {
        // @todo Write these files to each server in parallel with amphp
        foreach ($this->getServers($application) as $serverName => $server) {
            $filesystem = $this->getServerFilesystem($application, $serverName, $server);
            $filesystem->put('maintenance.flag', '');
        }
    }

    /**
     * @param ApplicationConfig $application
     * @param string|null       $branch
     */
    public function disable(ApplicationConfig $application, string $branch = null): void
    {
        // @todo Delete these files on each server in parallel with amphp
        foreach ($this->getServers($application) as $serverName => $server) {
            $filesystem = $this->getServerFilesystem($application, $serverName, $server);
            $filesystem->delete('maintenance.flag');
        }
    }

    /**
     * @param ApplicationConfig $application
     * @param string|null       $branch
     *
     * @return bool
     */
    public function isEnabled(ApplicationConfig $application, string $branch = null): bool
    {
        $servers = $this->getServers($application);
        $serversInMaintenance = [];
        // @todo Run these checks in parallel with amphp
        foreach ($servers as $serverName => $server) {
            $filesystem = $this->getServerFilesystem($application, $serverName, $server);
            if ($filesystem->has('maintenance.flag')) {
                $serversInMaintenance[$serverName] = true;
            }
        }

        if (!$serversInMaintenance) {
            return false;
        }

        if (count($serversInMaintenance) == count($servers)) {
            return true;
        }

        $serversNotInMaintenance = array_diff_key($servers, $serversInMaintenance);
        throw new Exception\RuntimeException(sprintf(
            'Maintenance mode enabled, but servers "%s" are missing maintenance flag.',
            implode(', ', array_keys($serversNotInMaintenance))
        ));
    }

    /**
     * @param ApplicationConfig $application
     */
    protected function getServers(ApplicationConfig $application): array
    {
        $servers = $application->getServers();
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
     * @param ApplicationConfig $application
     * @param string            $serverName
     * @param array             $server
     *
     * @return Filesystem
     * @throws Exception\RuntimeException if server configuration is invalid
     */
    protected function getServerFilesystem(ApplicationConfig $application, string $serverName, array $server): Filesystem
    {
        if (empty($server['host'])) {
            throw new Exception\RuntimeException(sprintf(
                'Server "%s" missing "host" config key.',
                $serverName
            ));
        }

        if (in_array($server['host'], ['127.0.0.1', 'localhost'])) {
            $adapter = new Local($application->getCodePath());
        } else {
            $adapter = new SftpAdapter(
                array_merge(
                    $application->getSshDefaults(),
                    $server,
                    ['root' => $application->getCodePath()]
                )
            );
        }
        return new Filesystem($adapter);
    }
}
