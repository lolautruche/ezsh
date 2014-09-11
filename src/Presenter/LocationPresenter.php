<?php
/*
 * This file is part of the eZ Debug Shell package.
 *
 * (c) Jérôme Vieilledent <http://www.lolart.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lolart\EzShell\Presenter;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Location;

class LocationPresenter extends ValueObjectPresenter
{
    const FMT = '<object>\\<<class>%s</class> <strong>#%d</strong>, <string>%s</string></object>';

    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function canPresent($value)
    {
        return $value instanceof Location;
    }

    /**
     * @param Location $value
     *
     * @return string
     */
    public function presentRef($value)
    {
        $urlAlias = $this->repository->getURLAliasService()->reverseLookup($value);
        return sprintf(self::FMT, get_class($value), $value->id, $urlAlias->path);
    }

    /**
     * @param Location $value
     * @param \ReflectionClass $class
     * @param int $propertyFilter
     *
     * @return array
     */
    protected function getProperties($value, \ReflectionClass $class, $propertyFilter)
    {
        $urlAliasService = $this->repository->getURLAliasService();
        $urlAliases = array_merge(
            $urlAliasService->listLocationAliases($value, false),
            $urlAliasService->listLocationAliases($value, true)
        );
        $locationPaths = [];
        foreach ($urlAliases as $alias) {
            $locationPaths[] = $alias->path;
        }

        return ['urlAliases' => $locationPaths] + parent::getProperties($value, $class, $propertyFilter);
    }
}
