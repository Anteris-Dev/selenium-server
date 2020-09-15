# Selenium Server for PHP
[![Check & fix styling](https://github.com/Anteris-Dev/selenium-server/workflows/Check%20&%20fix%20styling/badge.svg)](https://github.com/Anteris-Dev/selenium-server/actions?query=workflow%3A%22Check+%26+fix+styling%22)

This package provides you with a Selenium server ready to run on Linux, Mac OS, or Windows.

# To Install
```bash
composer require anteris-dev/selenium-server
```

# To Run the Server
```bash
./vendor/bin/selenium serve
```

To modify the driver used, pass the --driver method to the command. Supported drivers are listed below.

- Gecko (Firefox)
- Chrome (Chromium)

```bash

# Start a Firefox instance
./vendor/bin/selenium serve --driver gecko

# Start a Chromium instance
./vendor/bin/selenium serve --driver chrome

```
