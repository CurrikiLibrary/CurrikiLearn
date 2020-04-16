Enterprise-Level Collaboration System for Managing Curricula

This project implements a custom interface of the Curriki library.

Key features:

- Simplified search and viewing experience.
- Group management that allows administration at the regional, district and school levels.
- Resource filtering based on an individual's group membership.

Deployment:

- Clone repository

- Install composer dependencies
	composer install

- Set directory permissions
	// set ./storage and  ./bootstrap/cache directories to be writable by your webserver of choice.
	// But if you're lazy (don't do in production):
	sudo chmod -R 777 storage
	sudo chmod -R 777 bootstrap/cache

- If you're setting up a local version of the site, make sure to get a copy of the storage folder and
	extract it to storage/app

- Link storage
	php artisan storage:link
	// If this gives you a symlink error, delete public/storage and then try again

- Configure environment variables
	cp .env.example .env
	// Open the newly copied .env file in a text editor and fill in the settings (Database credentials and so on)

- Generate laravel application key
	php artisan key:generate

- Run laravel database migrations or load backup database if you have one
	php artisan migrate