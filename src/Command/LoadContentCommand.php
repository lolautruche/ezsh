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

class LoadContentCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ez:content')
            ->setDescription('Loads a Content from repository by its ID.')
            ->setDefinition([
                new InputArgument('id', InputArgument::REQUIRED, 'ID of the content'),
                new InputOption('remote-id', 'r', InputOption::VALUE_NONE, 'If provided, will use the content\'s remote ID'),
                new InputOption('all', 'a', InputOption::VALUE_NONE, 'If provided, the whole content will be loaded, including fields.')
            ])
            ->setHelp(<<<EOT
Loads a Content by its ID and displays it.
By default, it only loads the ContentInfo object, unless you use --all option.
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
        /** @var \eZ\Publish\API\Repository\Repository $repository */
        $repository = $this->getScopeVariable('repository');
        $contentService = $repository->getContentService();
        $id = $input->getArgument('id');

        if ($input->getOption('remote-id')) {
            $method = $input->getOption('all') ? 'loadContentByRemoteId' : 'loadContentInfoByRemoteId';
            $content = $contentService->$method($id);
        } else {
            $method = $input->getOption('all') ? 'loadContent' : 'loadContentInfo';
            $content = $contentService->$method($id);
        }

        $output->page($this->presenter->present($content, 10, true, Presenter::VERBOSE));
    }
}
