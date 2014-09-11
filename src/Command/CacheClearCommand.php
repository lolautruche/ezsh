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

use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand as FrameworkCacheClearCommand;

/**
 * Wrapper for Symfony cache:clear command.
 */
class CacheClearCommand extends WrappedCommand
{
    protected function getWrappedCommand()
    {
        return new FrameworkCacheClearCommand();
    }
}
 