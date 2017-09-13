![Voyager Hooks](https://raw.githubusercontent.com/larapack/voyager-hooks/master/logo.png)

<p align="center">
<a href="https://travis-ci.org/hinet/voyager-hooks"><img src="https://travis-ci.org/hinet/voyager-hooks.svg?branch=master" alt="Build Status"></a>
<a href="https://styleci.io/repos/76975411/shield?style=flat"><img src="https://styleci.io/repos/76975411/shield?style=flat" alt="Build Status"></a>
<a href="https://packagist.org/packages/hinet/voyager-hooks"><img src="https://poser.pugx.org/hinet/voyager-hooks/downloads.svg?format=flat" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/hinet/voyager-hooks"><img src="https://poser.pugx.org/hinet/voyager-hooks/v/stable.svg?format=flat" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/hinet/voyager-hooks"><img src="https://poser.pugx.org/hinet/voyager-hooks/license.svg?format=flat" alt="License"></a>
</p>

Made with ❤️ by [Mark Topper](https://marktopper.com)

# Voyager Hooks

[Hooks](https://github.com/larapack/hooks) system integrated into [Voyager](https://github.com/the-control-group/voyager).

# Installation

Install using composer:

```
composer require hinet/voyager-hooks
```

Then add the service provider to the configuration:

```php
'providers' => [
    Hinet\VoyagerHooks\VoyagerHooksServiceProvider::class,
],
```

In order for Voyager to automatically check for updates of hooks, add the following to your console kernel:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('hook:check')->sundays()->at('03:00');
}
```

That's it! You can now visit your Voyager admin panel and see a new menu item called `Hooks` have been added.
