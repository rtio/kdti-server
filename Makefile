deploy-staging:
	APP_ENV=staging php composer install --prefer-dist --optimize-autoloader --no-dev

	rm -rf var/cache/*

	APP_ENV=staging php bin/console cache:clear --no-debug --no-warmup --env=staging

	APP_ENV=staging php bin/console cache:warmup --env=staging

	serverless deploy --stage prod

	APP_ENV=staging php vendor/bin/bref cli kdti-backend-dev-console -- doctrine:migrations:migrate --env=staging

	aws s3 sync public/bundles s3://kdti-statics-staging/bundles --delete
 
deploy-prod:
	APP_ENV=prod php composer install --prefer-dist --optimize-autoloader --no-dev

	rm -rf var/cache/*

	APP_ENV=prod php bin/console cache:clear --no-debug --no-warmup --env=prod

	APP_ENV=prod php bin/console cache:warmup --env=prod

	serverless deploy --stage prod

	APP_ENV=prod php vendor/bin/bref cli kdti-backend-dev-console -- doctrine:migrations:migrate --env=prod

	aws s3 sync public/bundles s3://kdti-statics/bundles --delete

insights:
	vendor/bin/phpinsights