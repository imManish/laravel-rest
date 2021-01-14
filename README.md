# Sample Works - Laravel Vuejs  Sample #
This repository contains the Laravel 8 api with Advance Laravel Features

##Cloning
To clone the Laravel-Rest project to your local machine, run the following command

`git clone --recursive -b master git@github.com:imManish/laravel-rest.git /your_desired_location`

##Composer
When checking out the project, be sure to run

`composer install` to install the vendor libraries. These libraries have been excluded from the Git repository. Also
run `composer install` from the submodule paths:

##Configuration Files
Some configuration files are excluded from the repository, as these are symlinked from the *.envexample* .
This allows each developer to have their own private configuration files without overriding configurations from team
members when these files are pushed.

**Please do not commit and push your configuration files into the repository!**

For your localhost, copy the following files:

 - `$ cp .env.example` into `.env`
 
 
 and edit them to suit your localhost configuration.

##Database Migration
All that needs to be done now, is to run the database migration script from the project root.

`./artisan migrate`
`./artisan db:seed`
`./artisan passport:install --force`

##Deployment
The project utilises codepipeline through github integration over AWS EBS.

##Npm Installation
For build the frontend app simply run

`npm install` or `npm run --watch`
##Deployed Url 
`http://localhost:8000` Or `http://app.dev.com:port`

