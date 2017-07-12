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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoveCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ez:mv')
            ->setDescription('Move content from one location to another.')
            ->setDefinition([
                new InputArgument('source-id', InputArgument::REQUIRED, 'Location ID of the source'),
                new InputArgument('dest-id', InputArgument::REQUIRED, 'Location ID of the destination')
            ])
            ->setHelp(<<<EOT
Moves Content by its Location ID to another Location ID.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $repository \eZ\Publish\API\Repository\Repository */
        $repository = $this->getScopeVariable('repository');
        $locationService = $repository->getLocationService();
        $sourceLocation = $locationService->loadLocation($input->getArgument('source-id'));
        $destinationLocation = $locationService->loadLocation($input->getArgument('dest-id'));
        $locationService->moveSubtree($sourceLocation, $destinationLocation);
    }
}
