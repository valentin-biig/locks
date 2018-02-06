Php locks
========================

## Overview
 
 * Docker (nginx, php-fpm, redis)
 * Symfony 3.4
 * Commands to test the locks

## Install
 
 Create a ` .env ` file with the configuration in ` .env.dist `
 
 ` docker-compose build `
 
 ` docker-compose up -d `
 
 ` docker-compose start `

## Container

` docker-compose exec php bash `

## Commands

##### Ninja mutex

` bin/console app:lock:ninja `

##### Cheprasov php redis lock

` bin/console app:lock:cheprasov `

##### Lock component (symfony)

` bin/console app:lock:component `

##### Bonus :  Lock component with Combined Stores (symfony)

` bin/console app:lock:combined `

## Usage

 1. Launch multiple instances of your console
 2. Go to the project repository
 2. Run ` docker-compose exec php bash ` for every instances
 3. Try to acquire the resource by typing one of the command above