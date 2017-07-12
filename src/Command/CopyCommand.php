<?php

namespace Lolart\EzShell\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CopyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ez:cp')
            ->setDescription('Copies content from one location to another.')
            ->setDefinition([
                new InputArgument('source-id', InputArgument::REQUIRED, 'Location ID of the source'),
                new InputArgument('dest-id', InputArgument::REQUIRED, 'Location ID of the destination'),
                new InputOption('recursive', 'r', InputOption::VALUE_NONE, 'If provided, a subtree copy will be used')
            ])
            ->setHelp(<<<EOT
Copies a Content by its Location ID.
By default, it only copies the content itself and not the subtree. unless you use --recursive option.
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

        if ($input->getOption("recursive")) {
            $locationService->copySubtree($sourceLocation, $destinationLocation);
        } else {
            $contentService = $repository->getContentService();
            $destinationLocationCreateStruct = $locationService->newLocationCreateStruct($destinationLocation->id);
            $contentService->copyContent($sourceLocation->contentInfo, $destinationLocationCreateStruct);
        }
    }
}
