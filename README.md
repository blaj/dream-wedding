# dream-wedding

### Stack
* PHP 8.3
* Symfony 7
* Typescript
* Stimulus
* Turbo

### Prerequirements
* [DDEV](https://ddev.com/get-started/)
* Any IDE or text editor

### How to start
* Install DDEV
* Clone this repo into your wsl2 distrubtion (ex. ```/home/admin/projects```)
* Open directory containing repo in terminal
* Install composer dependencies via ```ddev composer install``` command
* Install node dependencies ```ddev yarn install```
* Type ```ddev start``` and press Enter
* Run all migrations ```ddev exec bin/console doctrine:migrations:migrate```
* Run npm watch ```ddev yarn watch```
* Open ```https://dream-wedding.ddev.site``` in your browser
