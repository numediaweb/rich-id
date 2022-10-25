# rich-id

Programming assignment for Software Engineer: User Registry.

## Install

Follow those steps to setup the app in your local developement environement:

### Requirements

  * PHP 8.0 or higher;
  * [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/installation.rst#globally-homebrew) to force the Symfony standards: `composer require --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer` to install it.
  * and the [usual Symfony application requirements](https://symfony.com/doc/5.4/setup.html#technical-requirements).

### Clone the repository 

Clone the repository in your local workspace folder
```
git clone git@github.com:numediaweb/rich-id.git
```

### Enforce Coding standards

Copy the pre-commit.dist into .git/hooks and rename it:
```shell
mkdir -p .git/hooks && cp pre-commit.dist .git/hooks/pre-commit && chmod +x .git/hooks/pre-commit
```

## Install composer

Connect via vagrant ssh to your local dev environement and then: `composer install`

## Database

* Create a database: `php bin/console doctrine:database:create`
* Do database migrations: `php bin/console doctrine:migrations:migrate`

## JS & Styles

You should have Node and NPM running on your machine in order to update styles or js code

run `npm install` Installing the JavaScript dependencies.
run `npm run watch` to watch for any changes inside the asset folder.
run `npm run build`  (optional) to build the production ready files (the deployment script does that on the production server).

## Deployment

YOU NEED TO EXECUTE THIS IN YOUR LOCAL MACHINE: NOT IN VM!!

This project uses [EasyCorp/easy-deploy-bundle](https://github.com/EasyCorp/easy-deploy-bundle) for managing deploys via `config/prod/deploy.php`: it is set to use the master branch and also to sync the ignored `.env.prod` (if not exist make copy of `.env` and add params) to the new releases.

First Configure the ssh setting locally as explained [here](https://github.com/EasyCorp/easy-deploy-bundle/blob/master/doc/tutorials/local-ssh-config.md#defining-the-ssh-server-configuration):

```shell script
Host rich.almois.me
  ForwardAgent yes
  HostName 123.456.789.123
  User me
  Port 22
  IdentityFile ~/.ssh/something
```
 
Once configured you should be able to ssh to the server with: `ssh rich.almois.me`

### Shared files and folders

The `config/prod/deploy.php` allows to sync local files and folder into the host before starting the deploy job. Add or remove shared folders from there.

### Steps to deploy

* Create a new release that pushes changes to master branch.
* Make sure your local ssh key to Github is loaded on the ssh agent: `ssh-add -l` if not add it `ssh-add -K ~/.ssh/something`
* Run `php bin/console deploy -vvv` locally: NOT IN VM!
