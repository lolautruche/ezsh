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

use eZ\Publish\API\Repository\Values\ValueObject;
use Psy\Presenter\ObjectPresenter;

class ValueObjectPresenter extends ObjectPresenter
{
    public function canPresent($value)
    {
        return $value instanceof ValueObject;
    }

    /**
     * @param object|\eZ\Publish\API\Repository\Values\ValueObject $value
     * @param \ReflectionClass $class
     * @param int $propertyFilter
     *
     * @return array
     */
    protected function getProperties($value, \ReflectionClass $class, $propertyFilter)
    {
        $refGetProperties = $class->getMethod('getProperties');
        $refGetProperties->setAccessible(true);
        $propNames = $refGetProperties->invoke($value);
        $properties = [];
        foreach ($propNames as $propName) {
            $properties[$propName] = $value->$propName;
        }

        return $properties;
    }
}
