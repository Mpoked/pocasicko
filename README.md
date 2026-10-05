# CodeIgniter 4 Application Starter

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Installation & updates

`composer create-project codeigniter4/appstarter` then `composer update` whenever
there is a new release of the framework.

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.2 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - The end of life date for PHP 8.1 was December 31, 2025.
> - If you are still using below PHP 8.2, you should upgrade immediately.
> - The end of life date for PHP 8.2 will be December 31, 2026.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

## Automatické mazání starých dat (cron)

Záznamy v tabulce `data` se po 11 letech označí jako smazané (soft delete –
nastaví se sloupec `deleted_at`, řádek v tabulce zůstane). Dělá to spark příkaz:

```
php spark data:cleanup              # smaže data starší 11 let
php spark data:cleanup --dry-run    # jen vypíše, kolik záznamů by smazal
php spark data:cleanup --years 5    # jiná hranice než 11 let
```

Příkaz je v `app/Commands/DeleteOldData.php`. Opakované spuštění nic nerozbije –
už smazané záznamy podruhé nebere. Výsledek zapisuje do logu v `writable/logs/`.

### Nastavení cronu na serveru

Spouští se jednou za měsíc, prvního dne ve 3:00 ráno. Na serveru spusťte
`crontab -e` a přidejte řádek (cestu k projektu a k PHP upravte podle serveru):

```
0 3 1 * * cd /var/www/pocasicko && /usr/bin/php spark data:cleanup >> /var/www/pocasicko/writable/logs/cron-cleanup.log 2>&1
```

Význam polí: `0 3 1 * *` = minuta 0, hodina 3, 1. den měsíce, každý měsíc,
jakýkoliv den v týdnu.

Na co dát pozor:

- `cd` do projektu je potřeba, aby `spark` našel konfiguraci – cron startuje
  v domovském adresáři uživatele.
- Cesta k PHP musí být celá (`which php` ji vypíše), cron nemá běžný `PATH`.
- Cron musí běžet pod uživatelem, který má právo zapisovat do `writable/`
  (typicky stejný jako webserver, např. `www-data`).
- V `.env` na serveru mít `CI_ENVIRONMENT = production`.

Kontrola, že je úloha nasazená: `crontab -l` vypíše seznam úloh,
`tail writable/logs/cron-cleanup.log` pak výstup posledního spuštění.

### Lokálně na Windows (Laragon)

Cron na Windows není, stejnou úlohu udělá Plánovač úloh:

```
schtasks /create /tn "pocasicko-cleanup" /tr "C:\laragon\bin\php\php-8.5.9-Win32-vs17-x64\php.exe C:\laragon\www\pokorny\pocasicko\spark data:cleanup" /sc monthly /d 1 /st 03:00
```
