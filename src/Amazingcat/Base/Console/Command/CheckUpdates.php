<?php

namespace Amazingcat\Base\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckUpdates
 * @package Amazingcat\Base\Console\Command
 */
class CheckUpdates extends Command
{
    /**
     * @var State
     */
    private $state;

    /**
     * @var \Amazingcat\Base\Model\Command\CheckUpdates
     */
    protected $checkUpdates;

    /**
     * @var \Amazingcat\Base\Model\Config\DataProvider
     */
    protected $configDataProvider;

    /**
     * CheckUpdates constructor.
     * @param State $state
     * @param \Amazingcat\Base\Model\Command\CheckUpdates $checkUpdates
     * @param \Amazingcat\Base\Model\Config\DataProvider $configDataProvider
     * @param string|null $name
     */
    public function __construct(
        State $state,
        \Amazingcat\Base\Model\Command\CheckUpdates $checkUpdates,
        \Amazingcat\Base\Model\Config\DataProvider $configDataProvider,
        string $name = null
    ) {
        parent::__construct($name);
        $this->state = $state;
        $this->checkUpdates = $checkUpdates;
        $this->configDataProvider = $configDataProvider;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('amazingcat:base:check_updates')->setDescription('Check module and info updates');
        parent::configure();
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(Area::AREA_FRONTEND);

        if (!$this->configDataProvider->notificationsEnabled()) {
            $output->writeln('Updates disabled');
        }

        $this->checkUpdates->run();
    }
}
