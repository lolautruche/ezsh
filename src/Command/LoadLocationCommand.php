<?php
/*
 * This file is part of the eZ Debug Shell package.
 *
 * (c) Jérôme Vieilledent <http://www.lolart.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lolart\EzShell\Command;

use Psy\VarDumper\Presenter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadLocationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ez:location')
            ->setDescription('Loads a Content from repository by its ID.')
            ->setDefinition([
                new InputArgument('id', InputArgument::REQUIRED, 'ID of the location'),
                new InputOption('remote-id', 'r', InputOption::VALUE_NONE, 'If provided, will use the location\'s remote ID'),
            ])
            ->setHelp(<<<EOT
Loads a Location by its ID and displays it.
EOT
        );

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface|\Psy\Output\ShellOutput $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \eZ\Publish\API\Repository\LocationService $locationService */
        $locationService = $this->getScopeVariable('locationService');
        $id = $input->getArgument('id');

        if ($input->getOption('remote-id')) {
            $location = $locationService->loadLocationByRemoteId($id);
        } else {
            $location = $locationService->loadLocation($id);
        }

        $output->page($this->presenter->present($location, 10, true, Presenter::VERBOSE));
    }
}
