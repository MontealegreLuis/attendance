# Attendance application

[![Build Status](https://travis-ci.org/MontealegreLuis/attendance.svg?branch=master)](https://travis-ci.org/MontealegreLuis/attendance)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d8b0ab6c-fd8b-47d2-b042-441ef0416552/mini.png)](https://insight.sensiolabs.com/projects/d8b0ab6c-fd8b-47d2-b042-441ef0416552)

Command line and Web application to keep track of Codeup bootcamp's attendance.

## Setup

This application uses [PhantomJS][1] to scrape the router's DHCP page looking
for the students MAC addresses.

To install this application you will only need Docker. If you have it
configured run this command:

```bash
$ make install YOUR_GITHUB_TOKEN
```

Sometimes running `composer install` will ask you for a [Github token][2], in
order to avoid having to type it every time, your [token is saved][3] in
composer's global configuration.

## Usage

The database container is in a shared container that you need to start before
using the applications.

```bash
$ make start
```

## Installing dependencies

You can install the dependencies either for the packages or the applications
with the following commands.

```bash
composer install --working-dir applications/console
composer install --working-dir applications/setup
composer install --working-dir applications/web
composer install --working-dir packages/attendance
composer install --working-dir packages/events
composer install --working-dir packages/persistency
```

## Tests

To run the tests, use the following command:

```bash
$ tests
```

This command will start bash in a container, you can run all the applications
and packages tests from there.

[1]: http://phantomjs.org/download.html
[2]: https://github.com/settings/tokens
[3]: https://getcomposer.org/doc/06-config.md#github-oauth
