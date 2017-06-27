<?php

namespace Lolart\EzShell\Command;

use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause\ContentName;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ez:ls')
            ->setDescription('List the content at a location.')
            ->setDefinition([
                new InputArgument('source-id', InputArgument::REQUIRED, 'Location ID of the source')
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $repository \eZ\Publish\API\Repository\Repository */
        $repository = $this->getScopeVariable('repository');
        $searchService = $repository->getSearchService();

        $query = new LocationQuery();
        $query->filter = new Criterion\LogicalAnd(
            [
                new Criterion\ParentLocationId($input->getArgument('source-id')),
            ]
        );
        $query->limit = 0;
        $query->sortClauses = [new ContentName()];

        $countSearchResult = $searchService->findLocations($query);
        $totalCount = $countSearchResult->totalCount;

        $query->limit = 100;

        $output->page(function ($output) use ($searchService, $query, $totalCount) {
            while ($searchHits = $searchService->findLocations($query)->searchHits) {
                foreach ($searchHits as $searchHit) {
                    $output->writeln($searchHit->valueObject->contentInfo->name . " (" . $searchHit->valueObject->id . ")");
                }
                $query->offset += $query->limit;
                if ($query->offset > $totalCount) {
                    break;
                }
            }
        });
    }
}
