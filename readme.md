#dxcShifts

- [Description](#description)
- [Installation Steps](#installation-steps)

##Description

A simple

##Installation Steps

Web Service:
- Extract files.
- Install `php >= 7.2` and a `web server`. [Click here to view framework requirements.](https://laravel.com/docs/6.x/installation#server-requirements)
- Setup MySQL and create a database for this application.
- Modify the database configuration in the .env file.
- Modify the site URL at `config/app.php`
- Run: `composer install` *(Requires composer installed)*.
- Run: `npm install` *(Requires Node.js installed)*
- [Setup file and directory permissions as outlined here.](https://vijayasankarn.wordpress.com/2017/02/04/securely-setting-file-permissions-for-laravel-framework/)
- Run: `composer dump-autoload` *(Generates the class map for autoloading)*
- Run: `php artisan migrate:fresh --seed` *(This sets up the database)*
- Route the web server to the public folder in the root directory.
- Login with the default account, which is a `Super Account.`

Mail Server:
- Open the `config/mail.php` and enter the Mail Server configuration. [Click here for more information.](https://laravel.com/docs/6.x/mail#driver-prerequisites)
- Open the `config/dxc-shifts.php` and enter the target email address to receive the exported schedule.