set :application, 'APPLICATION-NAME'
set :repo_url, 'REPO URL'
set :local_url, "LOCAL WORDPRESS URL"

set :deploy_via, :remote_cache
set :copy_exclude, [".git", ".DS_Store", ".gitignore", ".gitmodules"]
set :scm, :git
set :use_sudo, false

# Database
# Set the values for host, user, pass, and name for both production and staging.
set :wpdb, {
  :production => {
    :host     => 'PRODUCTION DB HOST',
    :user     => 'PRODUCTION DB USER',
    :password => 'PRODUCTION DB PASS',
    :name     => 'PRODUCTION DB NAME',
  },
  :staging => {
    :host     => 'STAGING DB HOST',
    :user     => 'STAGING DB USER',
    :password => 'STAGING DB PASS',
    :name     => 'STAGING DB NAME',
  },
  :local => {
    :host     => 'LOCAL DB HOST',
    :user     => 'LOCAL DB USER',
    :password => 'LOCAL DB PASS',
    :name     => 'LOCAL DB NAME',
  },
}