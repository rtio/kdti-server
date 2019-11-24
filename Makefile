deploy:
	# composer install --prefer-dist --optimize-autoloader --no-dev
	composer install
	# bin/console cache:warmup --env=prod
	bin/console cache:warmup --env=dev
	# serverless deploy --stage prod
	serverless deploy --stage dev
	aws s3 sync public/bundles s3://kdti-statics/bundles --delete
