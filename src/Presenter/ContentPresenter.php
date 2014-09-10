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
use eZ\Publish\API\Repository\Values\Content\Content;

class ContentPresenter extends ValueObjectPresenter
{
    const FMT = '<object>\\<<class>%s</class> <strong>#%d</strong>, <string>%s</string></object>';

    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    protected $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function canPresent($value)
    {
        return $value instanceof Content;
    }

    /**
     * @param Content|mixed $value
     * @param bool $color
     *
     * @return string
     */
    public function presentRef($value, $color = false)
    {
        $contentInfo = $value->contentInfo;
        return sprintf(self::FMT, get_class($value), $contentInfo->id, $contentInfo->name);
    }

    /**
     * @param Content|object $value
     * @param \ReflectionClass $class
     * @param int $propertyFilter
     *
     * @return array
     */
    protected function getProperties($value, \ReflectionClass $class, $propertyFilter)
    {
        $contentType = $this->repository->getContentTypeService()->loadContentType($value->contentInfo->contentTypeId);

        $properties = [
            'names' => $value->versionInfo->getNames(),
            'contentInfo' => (object)[
                'id' => $value->contentInfo->id,
                'currentVersionNo' => $value->contentInfo->currentVersionNo,
                'remoteId' => $value->contentInfo->remoteId,
                'contentTypeIdentifier' => $contentType->identifier,
                'contentTypeName' => $contentType->getName($value->contentInfo->mainLanguageCode),
                'mainLocationId' => $value->contentInfo->mainLocationId,
                'mainLanguageCode' => $value->contentInfo->mainLanguageCode,
                'sectionId' => $value->contentInfo->sectionId,
                'modificationDate' => $value->contentInfo->modificationDate->format('Y-m-d H-i-s'),
                'publishedDate' => $value->contentInfo->publishedDate->format('Y-m-d H-i-s'),
                'alwaysAvailable' => (bool)$value->contentInfo->alwaysAvailable
            ],
            'fields' => $this->getFields($value),
        ];

        return $properties;
    }

    /**
     * @param Content $content
     *
     * @return array
     */
    private function getFields($content)
    {
        $groupedFields = [];
        foreach ($content->getFields() as $field) {
            $groupedFields[$field->languageCode][$field->fieldDefIdentifier] = $field->value;
        }

        return $groupedFields;
    }
}
 