# Wordpress Capistrano Deploy

Easy deployment for Wordpress sites.

## Requirements
1. Your production and staging servers have to be accessible via ssh
2. Git has to be installed in every environment
2. The same applies to wp-cli (http://wp-cli.org/#install)

## Setup
For the capistrano deployment to run properly you have make some  preparations to your local environment:

1. capistrano must be installed: `bundler install`
2. Rename local-config.SAMPLE.php to local-config.php and replace the placeholders with your database credentials
3. Rename config.SAMPLE.rb to config.rb (located in the config directory). For every stage you have to edit your database credentials. Pay attention to the :local_url variable!
4. Rename the files in config/deploy accordingly and edit the variables so they match the environment

## Tasks
### `bundle exec cap staging deploy`
Deploy your current code base to the staging server

### `bundle exec cap staging wordpress:db:pull`
Copy the staging database to your local database.

### `bundle exec cap staging wordpress:db:push`
Copy the local database to the staging server

The tasks `wordpress:db:pull` and `wordpress:db:push` will find and the remote url and replaces it with your local url. So keeping your database in sync is a no brainer.

## Todo
* Wordpress Multisite Deployment
* Keeping upload folders in sync
* adding Wordpress as a Submodule. I know it sucks having a copy of wordpress in the repository, but since Capistrano 3 no longer supports git submodules I'm fine with this.