# kd./ti/

[![CircleCI](https://circleci.com/gh/kdti/kdti-server/tree/master.svg?style=svg)](https://circleci.com/gh/kdti/kdti-server/tree/master)
[![codecov](https://codecov.io/gh/kdti/kdti-server/branch/master/graph/badge.svg)](https://codecov.io/gh/kdti/kdti-server)

## Local Environment

This guide assumes you're using [Docker](https://docker.io) and [Docker Compose](https://docs.docker.com/compose/).

Download the master branch:

```bash
git clone git@github.com:kdti/kdti-server.git
```

Create a `.env.local` to override the `.env` with your custom values.

Install the dependencies:

```bash
composer install
```

Run the migrations:

```bash
docker-compose run --rm --no-deps php bin/console doctrine:migrations:migrate
```

## Testing

After having your local environment set up, run the migrations for the testing environment:

```bash
docker-compose run --rm --no-deps php bin/console doctrine:migrations:migrate --env test
```

Run the tests:

```bash
docker-compose run --rm --no-deps php bin/phpunit
```

## Deploying


## Contributing

Please see [contributing](contributing.md) for details.

## Credits

- [Ian Rodrigues](https://github.com/leandronascimento)
- [Rafael Menezes](https://github.com/rtio)
- [All Contributors](../../contributors)

## License

[kd./ti/](https://kdti.dev) and the [Symfony](https://symfony.com) framework are open-sourced software licensed under the [MIT license](license.md).
