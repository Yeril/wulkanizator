###### Gałek Rafał, Hotloś Tomasz, Jasztal Dawid

###### Year 3, Group 31, Lab group 02
# Topic

Designing an online system for an automotive blog grup.


# Wulkanizator project

## How to install?
```bash
#First step install dependencies:
cd wulkanizator && composer install

#Second update database:
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
>Use default `user0@user0.com:user` or `admin0@admin0.com:admin`
