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

use Psy\Context;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class WrappedCommand extends Command
{
    /**
     * @var \Symfony\Component\Console\Command\Command
     */
    protected $innerCommand;

    public function __construct($name = null)
    {
        $this->innerCommand = $this->getWrappedCommand();
        parent::__construct($name);
    }

    /**
     * Returns the wrapped command object.
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    abstract protected function getWrappedCommand();

    public function setContext(Context $context)
    {
        parent::setContext($context);
        if ($this->innerCommand instanceof ContainerAwareCommand) {
            $this->innerCommand->setContainer($this->container);
        }
    }

    protected function configure()
    {
        $this->setName($this->innerCommand->getName());
    }


    public function ignoreValidationErrors()
    {
        parent::ignoreValidationErrors();
        $this->innerCommand->ignoreValidationErrors();
    }

    public function setHelperSet(HelperSet $helperSet)
    {
        parent::setHelperSet($helperSet);
        $this->innerCommand->setHelperSet($helperSet);
    }

    public function getHelperSet()
    {
        return $this->innerCommand->getHelperSet();
    }

    public function getApplication()
    {
        return $this->innerCommand->getApplication();
    }

    public function setApplication(Application $application = null)
    {
        parent::setApplication($application);
        $this->innerCommand->setApplication($application);
    }

    public function asText()
    {
        return $this->innerCommand->asText();
    }

    public function isEnabled()
    {
        return $this->innerCommand->isEnabled();
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        return $this->innerCommand->run($input, $output);
    }

    public function setCode($code)
    {
        return $this->innerCommand->setCode($code);
    }

    public function mergeApplicationDefinition($mergeArgs = true)
    {
        return $this->innerCommand->mergeApplicationDefinition($mergeArgs);
    }

    public function setDefinition($definition)
    {
        return $this->innerCommand->setDefinition($definition);
    }

    public function getDefinition()
    {
        return $this->innerCommand->getDefinition();
    }

    public function getNativeDefinition()
    {
        return $this->innerCommand->getNativeDefinition();
    }

    public function addArgument($name, $mode = null, $description = '', $default = null)
    {
        return $this->innerCommand->addArgument($name, $mode, $description, $default);
    }

    public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        return $this->innerCommand->addOption($name, $shortcut, $mode, $description, $default);
    }

    public function setName($name)
    {
        parent::setName($name);
        return $this->innerCommand->setName($name);
    }

    public function setProcessTitle($title)
    {
        return $this->innerCommand->setProcessTitle($title);
    }

    public function getName()
    {
        return $this->innerCommand->getName();
    }

    public function setDescription($description)
    {
        return $this->innerCommand->setDescription($description);
    }

    public function getDescription()
    {
        return $this->innerCommand->getDescription();
    }

    public function setHelp($help)
    {
        return $this->innerCommand->setHelp($help);
    }

    public function getHelp()
    {
        return $this->innerCommand->getHelp();
    }

    public function getProcessedHelp()
    {
        return $this->innerCommand->getProcessedHelp();
    }

    public function setAliases($aliases)
    {
        return $this->innerCommand->setAliases($aliases);
    }

    public function getAliases()
    {
        return $this->innerCommand->getAliases();
    }

    public function getSynopsis($short = false)
    {
        return $this->innerCommand->getSynopsis($short);
    }

    public function getHelper($name)
    {
        return $this->innerCommand->getHelper($name);
    }

    public function asXml($asDom = false)
    {
        return $this->innerCommand->asXml($asDom);
    }
}
 