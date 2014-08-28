# eZ Debug Shell

eZ Debug Shell is an interactive CLI debugger for eZ Publish 5+.
It is built on top of [PsySH](http://psysh.org) and thus acts as a [*Read-eval-print-loop* (aka **repl**)](http://en.wikipedia.org/wiki/Read%E2%80%93eval%E2%80%93print_loop).

It allows you to debug live code in the context of an eZ Publish application, avoiding to create CLI commands.

## Install
### Global install (recommended)
```bash
composer global require lolautruche/ezsh:dev-master
```

This will install `ezsh` executable into the ~/.composer/vendor/bin folder.

> For `ezsh` to work properly without hassle, ensure you have `~.composer/vendor/bin` in your `$PATH`:
>
> ```bash
> export PATH=~/.composer/vendor/bin
> ```

### Project install
You can install `ezsh` binary in your eZ project with Composer:

```bash
composer require lolautruche/ezsh:dev-master
```

## Usage
To be able to use `ezsh`, **you first need to be in your eZ project root**:

```bash
cd /my/ezpublish/root
ezsh
```

This will display something like:

```bash
Psy Shell v0.1.12 (PHP 5.5.15 — cli) by Justin Hileman
>>>
```

Starting from here, you'll be able to evaluate any kind of code in the context of your project.

### Available variables
Some variables are already set for you, like the service container, the ConfigResolver and the Repository:

| Variable          | Description                                                     |
|-------------------|-----------------------------------------------------------------|
| `$container`      | Instance of Symfony ServiceContainer. Get your services with it |
| `$kernel`         | Instance of your `EzPublishKernel`                              |
| `$repository`     | Instance of the eZ Content repository (to use API)              |
| `$configResolver` | The ConfigResolver (to get SiteAccess aware settings)           |

### Environment
You can define the environment to work in (e.g. `dev`, `prod`, ...) by setting the `EZ_ENV` environment variable before launching `ezsh`:

```bash
# This will launch the shell in "prod" environment
$ export EZ_ENV="prod"
$ ezsh
```

### Example

> From the debug shell, you can define any kind of functions, loops, conditions...<br>
> For more information see [PsySH documentation](http://psysh.org).

```bash
ezsh

Psy Shell v0.1.12 (PHP 5.5.15 — cli) by Justin Hileman
>>> $repository
=> <eZPublishCoreSignalSlotRepository_000000001c2171540000000109a6622a #000000005890a1640000000113a69e24> {}
>>> $contentInfo = $repository->getContentService()->loadContentInfo(60);
=> <eZ\Publish\API\Repository\Values\Content\ContentInfo #000000005890a2330000000113a69e24> {}
>>> ls -la $contentInfo

Class Properties:
  $alwaysAvailable    0
  $contentTypeId      40
  $currentVersionNo   1
  $id                 2926
  $mainLanguageCode   "eng-GB"
  $mainLocationId     "2928"
  $modificationDate   <DateTime #0000000005c1481a000000016239202d>
  $name               "Wheelchair Barleywine"
  $ownerId            14
  $published          true
  $publishedDate      <DateTime #0000000005c1481b000000016239202d>
  $remoteId           "beer-d7nEsv"
  $sectionId          1

Class Methods:
  __construct     public function __construct(array $properties = null)
  __get           public function __get($property)
  __isset         public function __isset($property)
  __set           public function __set($property, $value)
  __set_state     public static function __set_state(array $array)
  __unset         public function __unset($property)
  attribute       final public function attribute($property)
  attributes      final public function attributes()
  getProperties   protected function getProperties($dynamicProperties = null)
  hasAttribute    final public function hasAttribute($property)

>>> $configResolver->getParameter('languages');
=> [
       "eng-GB"
   ]
```
