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
```bash
Usage:
  ezsh [--siteaccess=<siteaccess_name>] [--env=<env>] [--debug] [--version] [--help] [files...]

Options:
  --siteaccess    -s SiteAccess to use (e.g. ezdemo_site). If not provided, fallbacks to configured default SiteAccess.
  --env           -e Environment to use (defaults to "dev").
  --debug         -d Use debugging if provided. Debug is always on with "dev" environment.
  --help          -h Display this help message.
  --version       -v Display the PsySH version.
  --config        -c Use an alternate PsySH config file location.
```

> **Important:** To be able to use `ezsh`, **you first need to be in your eZ project root**:

```bash
cd /my/ezpublish/root
ezsh --siteaccess=my_siteaccess
```

This will display something like:

```bash
Debugging eZ Publish using 'my_siteaccess' SiteAccess, in 'dev' environment.
Psy Shell v0.1.12 (PHP 5.5.15 — cli) by Justin Hileman
>>>
```

Starting from here, you'll be able to evaluate any kind of code in the context of your project.

### Available variables
Some variables are already set for you, like the service container, the ConfigResolver and the Repository:

| Variable              | Description                                                     |
|-----------------------|-----------------------------------------------------------------|
| `$container`          | Instance of Symfony ServiceContainer. Get your services with it |
| `$kernel`             | Instance of your `EzPublishKernel`                              |
| `$repository`         | Instance of the eZ Content repository (to use API)              |
| `$contentService`     | The ContentService                                              |
| `$contentTypeService` | The ContentTypeService                                          |
| `$locationService`    | The LocationService                                             |
| `$searchService`      | The SearchService                                               |
| `$userService`        | The UserService                                                 |
| `$configResolver`     | The ConfigResolver (to get SiteAccess aware settings)           |

### Example

> From the debug shell, you can define any kind of functions, loops, conditions...<br>
> For more information see [PsySH documentation](http://psysh.org).

```bash
Debugging eZ Publish using 'default' SiteAccess, in 'dev' environment.

Psy Shell v0.1.12 (PHP 5.5.15 — cli) by Justin Hileman
>>> $repository
=> <eZPublishCoreSignalSlotRepository_000000001c2171540000000109a6622a #000000005890a1640000000113a69e24> {}
>>> $contentInfo = $contentService->loadContentInfo(2926);
=> <eZ\Publish\API\Repository\Values\Content\ContentInfo #2926, Wheelchair Barleywine {
       id: 2926,
       contentTypeId: 40,
       name: "Wheelchair Barleywine",
       sectionId: 1,
       currentVersionNo: 1,
       published: true,
       ownerId: 14,
       modificationDate: <DateTime #00000000588e6c020000000142ceef3a> {
           date: "2014-08-28 10:32:44.000000",
           timezone_type: 3,
           timezone: "Europe/Paris"
       },
       publishedDate: <DateTime #00000000588e6c0c0000000142ceef3a> {
           date: "2014-08-28 10:32:44.000000",
           timezone_type: 3,
           timezone: "Europe/Paris"
       },
       alwaysAvailable: 0,
       remoteId: "beer-d7nEsv",
       mainLanguageCode: "eng-GB",
       mainLocationId: "2928"
   }

>>> $configResolver->getParameter('languages');
=> [
       "eng-GB"
   ]
```
