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
>>> var_dump($contentInfo);
class eZ\Publish\API\Repository\Values\Content\ContentInfo#616 (13) {
  protected $id =>
  int(60)
  protected $contentTypeId =>
  int(22)
  protected $name =>
  string(8) "Feedback"
  protected $sectionId =>
  int(1)
  protected $currentVersionNo =>
  int(1)
  protected $published =>
  bool(true)
  protected $ownerId =>
  int(14)
  protected $modificationDate =>
  class DateTime#615 (3) {
    public $date =>
    string(26) "2013-01-22 16:15:17.000000"
    public $timezone_type =>
    int(3)
    public $timezone =>
    string(12) "Europe/Paris"
  }
  protected $publishedDate =>
  class DateTime#620 (3) {
    public $date =>
    string(26) "2013-01-22 15:02:10.000000"
    public $timezone_type =>
    int(3)
    public $timezone =>
    string(12) "Europe/Paris"
  }
  protected $alwaysAvailable =>
  int(0)
  protected $remoteId =>
  string(32) "6442aa1a9c5ed4cbfe2bc8d26fade210"
  protected $mainLanguageCode =>
  string(6) "eng-GB"
  protected $mainLocationId =>
  string(2) "62"
}
=> null

>>> $configResolver->getParameter('languages');
=> [
       "eng-GB"
   ]
```
