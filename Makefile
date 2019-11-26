deploy:
	composer install

	rm -rf var/cache/*

	php bin/console cache:clear --no-debug --no-warmup --env=dev

	bin/console cache:warmup --env=dev

	serverless deploy --stage dev

	vendor/bin/bref cli kdti-backend-dev-console -- doctrine:migrations:migrate --env=dev

	aws s3 sync public/bundles s3://kdti-statics/bundles --delete
 
deploy-prod:
	composer install --prefer-dist --optimize-autoloader --no-dev

	rm -rf var/cache/*

	php bin/console cache:clear --no-debug --no-warmup --env=prod

	bin/console cache:warmup --env=prod

	serverless deploy --stage prod

	vendor/bin/bref cli kdti-backend-dev-console -- doctrine:migrations:migrate --env=prod

	aws s3 sync public/bundles s3://kdti-statics/bundles --delete

insights:
	vendor/bin/phpinsights