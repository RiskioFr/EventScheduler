<?php
namespace Riskio\ScheduleModule;

use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $application    = $e->getApplication();
        $serviceManager = $application->getServiceManager();

        /* @var $reportHandler \Riskio\ScheduleModule\Report\ReportHandler */
        $reportHandler = $serviceManager->get('Riskio\ScheduleModule\Report\ReportHandler');

        $reportThumbnailListener = $serviceManager->get('Riskio\ScheduleModule\Listener\ReportThumbnailListener');
        $reportStatusListener    = $serviceManager->get('Riskio\ScheduleModule\Listener\ReportStatusListener');

        $reportHandler->getEventManager()->attach($reportThumbnailListener);
        $reportHandler->getEventManager()->attach($reportStatusListener);

        $reportUpdateListener = $serviceManager->get('Riskio\ScheduleModule\Listener\ReportUpdateListener');
        $application->getEventManager()->attach($reportUpdateListener);
    }

    public function getConsoleBanner(ConsoleAdapterInterface $console)
    {
        return 'Riskio Report';
    }

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        return [
            'report update' => 'Update queued report',
        ];
    }
}
