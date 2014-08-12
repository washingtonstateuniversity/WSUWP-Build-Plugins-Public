#!/bin/sh
if [ -f "/var/repos/wsuwp-deployment/deploy.json" ]; then
  deploy_trigger=$(cat /var/repos/wsuwp-deployment/deploy.json)
  rm /var/repos/wsuwp-deployment/deploy.json

  deploy_date=$(date +%x%t%T%z)
  echo "$deploy_date - $deploy_trigger" >> /var/repos/wsuwp-deployment/prod-deployments.log
  echo "$deploy_date - $deploy_trigger" > /var/repos/wsuwp-platform/build/wordpress/deployed-last.txt

  # Setup permissions before syncing files.
  chown -R www-data:www-data /var/repos/wsuwp-platform

  # Ensure all files are set to be group read/write so that our www-data and
  # www-deploy users can handle them. This corrects possible issues in local
  # development where elevated permissions can be set.
  find "/var/repos/wsuwp-platform/build/" -type d -exec chmod 775 {} \;
  find "/var/repos/wsuwp-platform/build/" -type f -exec chmod 664 {} \;

  rsync -rlgDh --delete --exclude '.git' --exclude 'wp-config.php' --exclude 'wp-content/uploads' /var/repos/wsuwp-platform/build/ /var/www/

  # Setup permissions on production files.
  chown -R www-data:www-data /var/www/wordpress
  chown -R www-data:www-data /var/www/wp-content
fi