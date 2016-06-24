# Attendance console application

This application has two commands. One to keep record of the hour at which
students arrive to class. And another to keep record of the hour at which they
leave.

```bash
$ bin/console codeup:rollcall
$ bin/console codeup:checkout
```

Both commands scrape the router's DHCP status page. Which can make them
difficult to test. If you don't have access to the router, you can use a static
fixture served by PHP's built-in server by adding the option `--locally` to both
commands.

```bash
$ bin/console codeup:rollcall -l
$ bin/console codeup:checkout -l
```

# Tests

To run this application tests, execute the following command.

```bash
$ bin/phpunit
```
