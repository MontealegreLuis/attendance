# Attendance application

[![Build Status](https://travis-ci.org/MontealegreLuis/attendance.svg?branch=master)](https://travis-ci.org/MontealegreLuis/attendance)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d8b0ab6c-fd8b-47d2-b042-441ef0416552/mini.png)](https://insight.sensiolabs.com/projects/d8b0ab6c-fd8b-47d2-b042-441ef0416552)

Command line and Web application to keep track of Codeup bootcamp's attendance.

## Setup

This application uses [PhantomJS][1] to scrape the DHCP page looking for the
students MAC addresses. One of the simplest ways to install PhantomJS is using
`npm`

```bash
$ npm install -g phantomjs-prebuilt
```

After setting up PhantomJS, use Composer to install the project's dependencies.

```bash
$ composer install
```

Create and seed the database.

```bash
$ cp configuration.tests.php configuration
$ bin/setup codeup:db:reset
```

## Tests

To run the tests, use the following commands:

```bash
$ bin/phpspec run
$ bin/phpunit --testdox
```

[1]: http://phantomjs.org/download.html
