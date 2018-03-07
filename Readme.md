# Site Monitor
Site monitor is a simple solution that takes screenshots of an array of sites you provide. You can schedule screenshots via a cron-job, and check up on it at any time.[View more info here.](https://ryderdamen.com/projects/site-monitor)

## Setup

First, clone this repository to your server (this is cloning into a folder called monitor)
```bash
git clone https://github.com/ryderdamen/Site-Monitor.git monitor
```

Next, enter the folder and install dependencies with composer
```bash
cd monitor
composer install
```

Once dependencies are installed, you can schedule the cron job
```cron

0 6 * * * php /path/to/your/installation/monitor/app/cron.php

```

To get things up and running quicker, force a screenshot right away
```bash

php /path/to/your/installation/monitor/app/cron.php

```

If you've installed your project in a public directory, it should now be visible on the web.


## Dependencies
* [PHP PhantomJS](http://jonnnnyw.github.io/php-phantomjs/)
* [Lightbox](http://lokeshdhakar.com/projects/lightbox2/)
* Lightbox