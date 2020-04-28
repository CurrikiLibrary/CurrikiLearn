Enterprise-Level Collaboration System for Managing Curricula.

This project implements a custom interface of the Curriki library.

Key features:

- Simplified search and viewing experience.
- Group management that allows administration at the regional, district and school levels.
- Resource filtering based on an individual's group membership.

**Prerequisite**

    - Must configured and installed CurrikiOpenLibrary as CurrikiOpenCurriculumPortal is using API's from CurrikiOpenLibrary.
**Deployment:**

  

- **Clone repository**

    Clone using below command.

    git clone [https://github.com/CurrikiLibrary/CurrikiOpenCurriculumPortal.git](https://github.com/CurrikiLibrary/CurrikiOpenCurriculumPortal.git) directoryName



- **Configuration Settings**

  

- Configure environment variable using below commands

- cd project-directory

- cp .env.example .env

- Update database credentails in .env file. Use the same database configured for CurrikiOpenLibrary.

        DB_DATABASE=homestead

        DB_USERNAME=homestead

        DB_PASSWORD=secret

- Run below commands

        composer install

- Change Permissions

        chmod -R 777 bootstrap/cache storage/

  

- Generate laravel key using below command

		php artisan key:generate

  - Link storage
	php artisan storage:link
	// If this gives you a symlink error, delete public/storage and then try again


- Run laravel database migrations or load backup database if you have one
	
        php artisan migrate


**- Vhost Configuration**

  
    - Create and enable vhost.

    - Restart the server

    - Update the hosts file entry.
	


