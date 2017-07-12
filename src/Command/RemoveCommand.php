<?php

namespace Lolart\EzShell\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ez:rm')
            ->setDescription('Removes content location.')
            ->setDefinition([
                new InputArgument('source-id', InputArgument::REQUIRED, 'Location ID of the source')
            ])
            ->setHelp(<<<EOT
Removes a Location by it Location ID.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $repository \eZ\Publish\API\Repository\Repository */
        $repository = $this->getScopeVariable('repository');
        $locationService = $repository->getLocationService();

        $sourceLocation = $locationService->loadLocation($input->getArgument('source-id'));

        $locationService->deleteLocation($sourceLocation);
    }
}
