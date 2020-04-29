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

        ```
        git clone [https://github.com/CurrikiLibrary/CurrikiOpenCurriculumPortal.git](https://github.com/CurrikiLibrary/CurrikiOpenCurriculumPortal.git) directoryName
        ```



- **Configuration Settings**

  

- Configure environment variable using below commands

        ```
        cd project-directory
        cp .env.example .env
        ```

- Update database credentails in .env file. Use the same database configured for CurrikiOpenLibrary.

        ```
        DB_DATABASE=homestead

        DB_USERNAME=homestead

        DB_PASSWORD=secret

        CURRIKI_API_URL=URL TO CurrikiOpenLibrary ROOT

        CURRIKI_AVATARS_BASE_URL=URL TO "avatars" FOLDER on AWS S3

        APP_HUB_ID=ID OF GROUP TYPE "Hub" IN "custom_groups" TABLE, 1 IN SAMPLE DB

        CURRIKI_GROUP_ID=ID OF GROUP IN CURRIKI, 7980 IN SAMPLE DB

        GOOGLE_RECAPTCHA_SITE_KEY

        GOOGLE_RECAPTCHA_SECRET_KEY
        ```

- Run below commands

        ```
        composer install
        ```

- Change Group

        ```
        sudo chown -R $USER:www-data storage
        sudo chown -R $USER:www-data bootstrap/cache
        chmod -R 775 storage
        chmod -R 775 bootstrap/cache
        ```

- Generate laravel key using below command

        ```
        php artisan key:generate
        ```

- No need to run laravel migration or seeders, while using the CurrikiOpenLibrary sample DB

- **Vhost Configuration**
    - Create and enable vhost.
    - Restart the server
    - Update the hosts file entry.


- **Test Credentials**
    - user=root
    - pass=123456