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

use Psy\Command\ReflectingCommand;
use Psy\Context;
use Psy\Presenter\PresenterManager;
use Psy\Presenter\PresenterManagerAware;

class Command extends ReflectingCommand implements PresenterManagerAware
{
    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    protected $repository;

    /**
     * @var \eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    protected $configResolver;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Psy\Presenter\PresenterManager
     */
    protected $presenterManager;

    /**
     * Set a reference to the PresenterManager.
     *
     * @param PresenterManager $manager
     */
    public function setPresenterManager(PresenterManager $manager)
    {
        $this->presenterManager = $manager;
    }

    public function setContext(Context $context)
    {
        parent::setContext($context);
        $this->repository = $this->getScopeVariable('repository');
        $this->configResolver = $this->getScopeVariable('configResolver');
        $this->container = $this->getScopeVariable('container');
    }
}
