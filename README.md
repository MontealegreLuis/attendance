# Attendance application

[![Build Status](https://travis-ci.org/MontealegreLuis/attendance.svg?branch=master)](https://travis-ci.org/MontealegreLuis/attendance)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d8b0ab6c-fd8b-47d2-b042-441ef0416552/mini.png)](https://insight.sensiolabs.com/projects/d8b0ab6c-fd8b-47d2-b042-441ef0416552)

Command line and Web application to keep track of Codeup bootcamp's attendance.

## Setup

This application uses [PhantomJS][1] to scrape the router's DHCP page looking
for the students MAC addresses.

To install this application you'll need [Docker][3] and [Docker Compose][4]. If
you have them configured, run this command:

```bash
$ make env
```

This will create the file [containers/.env.sh](containers/.env.sh). You can
change the configuration settings for the application, containers and images
there. The only variable without a sensible default is `GITHUB_TOKEN`. You'll
need a [Github token][2] as you will be running `composer install` for several
applications.

## Usage

I created some aliases to ease the use of the containers.

```bash
$ source .alias
```

To run the Web application execute:

```bash
$ web
```

You can run the console commands by using one of the following commands.

```bash
$ console codeup:rollcall
$ console codeup:checkout
```

### Development environment

You can install the dependencies either for the packages or the applications
by running the `dev` container and executing composer. You can also run the
migrations, where needed.

```bash
$ dev
```

### Tests

You can run the tests from the `dev` container:

[1]: http://phantomjs.org/download.html
[2]: https://github.com/settings/tokens
[3]: https://www.docker.com/
[4]: https://docs.docker.com/compose/
