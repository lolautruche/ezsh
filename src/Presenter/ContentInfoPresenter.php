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


use eZ\Publish\API\Repository\Values\Content\ContentInfo;

class ContentInfoPresenter extends ValueObjectPresenter
{
    const FMT = '<object>\\<<class>%s</class> <strong>#%d</strong>, <string>%s</string></object>';

    public function canPresent($value)
    {
        return $value instanceof ContentInfo;
    }

    /**
     * Present a reference to the value.
     *
     * @param mixed|ContentInfo $value
     * @param Boolean $color
     *
     * @return string
     */
    public function presentRef($value, $color = false)
    {
        return sprintf(self::FMT, get_class($value), $value->id, $value->name);
    }
}
