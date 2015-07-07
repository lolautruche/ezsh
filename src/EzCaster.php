<?php
/*
 * This file is part of the eZ Debug Shell package.
 *
 * (c) Jérôme Vieilledent <http://www.lolart.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lolart\EzShell;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use Symfony\Component\VarDumper\Caster\Caster;
use Symfony\Component\VarDumper\Cloner\Stub;

class EzCaster
{
    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function castContentInfo(ContentInfo $info, array $a, Stub $stub, $isNested, $filter = 0)
    {
        $a = [
            Caster::PREFIX_PROTECTED.'id' => $info->id,
            Caster::PREFIX_PROTECTED.'name' => $info->name,
        ] + $a;

        if (!$filter & Caster::EXCLUDE_VERBOSE) {
            $contentType = $this->repository->getContentTypeService()->loadContentType($info->contentTypeId);
            $a[Caster::PREFIX_VIRTUAL.'contentTypeName'] = $contentType->getName($info->mainLanguageCode);
        }

        return $a;
    }

    public function castContent(Content $content, array $a, Stub $stub, $isNested, $filter = 0)
    {
        $info = $content->contentInfo;

        $a = [
            Caster::PREFIX_VIRTUAL.'id' => $info->id,
            Caster::PREFIX_VIRTUAL.'name' => $info->name,
        ] + $a;

        if (!$filter & Caster::EXCLUDE_VERBOSE) {
            $groupedFields = [];
            foreach ($content->getFields() as $field) {
                $groupedFields[$field->languageCode][$field->fieldDefIdentifier] = $field->value;
            }

            $a += [
                Caster::PREFIX_VIRTUAL.'names' => $content->versionInfo->getNames(),
                Caster::PREFIX_VIRTUAL.'contentInfo' => $info,
                Caster::PREFIX_VIRTUAL.'fields' => $groupedFields,
            ];
        }

        return $a;
    }

    public function castLocation(Location $location, array $a, Stub $stub, $isNested, $filter = 0)
    {
        $urlAliasService = $this->repository->getURLAliasService();
        $prefix = Caster::PREFIX_PROTECTED;

        if (!isset($a[$prefix.'id'])) {
            $b = (array) $location;
            if (isset($b[$prefix.'id'])) {
                $a += [$prefix.'id' => $b[$prefix.'id']];
            }
        }

        if ($filter & Caster::EXCLUDE_VERBOSE) {
            $a[Caster::PREFIX_VIRTUAL.'urlAlias'] = $urlAliasService->reverseLookup($location);
        } else {
            $urlAliases = array_merge(
                $urlAliasService->listLocationAliases($location, false),
                $urlAliasService->listLocationAliases($location, true)
            );
            $locationPaths = [];
            foreach ($urlAliases as $alias) {
                $locationPaths[] = $alias->path;
            }

            $a[Caster::PREFIX_VIRTUAL.'urlAliases'] = $locationPaths;
        }

        return $a;
    }

}
