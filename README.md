# Attack monitor

This web page will send webhook's to Discord on acknowldgement of an attack from your Full Time Hosting account. 
It will then post the name of the server, the IP address, whether the attack has started or ended and finally the power in Gigabit.

## Getting Started

1. Edit the script and put in your webhook (line 12)
2. Edit the switch statement and add in your own IP addresses. 

### Prerequisites

1. Web hosting (Not neccessarily from FTH), with PHP.
2. A discord server with access to making a webhook.

Ubuntu:

```
Good LAMP setup (Apache): https://howtoubuntu.org/how-to-install-lamp-on-ubuntu
Good LEMP setup (NGINX): https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-ubuntu-18-04
```

### Installing

Place the index.php file once modified into the root of your webhost (or the corresponding address that you want, anyway).
For example: https://attack.jamdoog.com/index.php

## Running the tests

It's not possible to test directly from FTH. If you wish to test the script, please send a fake POST request.
I'll update this at some point with the POST request variables it needs if you can't figure it out.

## Authors

Jamdoog#3644
Emile#3038 (Helped figure out some things)
