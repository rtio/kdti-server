<?php

namespace App\Tests;

use ReflectionClass;
use Coduo\PHPMatcher\Matcher;
use SebastianBergmann\Diff\Differ;
use League\FactoryMuffin\FactoryMuffin;
use Symfony\Component\HttpFoundation\Response;
use League\FactoryMuffin\Stores\RepositoryStore;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class TestCase extends WebTestCase
{
    protected $client;
    protected $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createHttpClient();
        
        $entityManager = self::$container->get('doctrine.orm.entity_manager');
        
        $factoriesDir = self::$kernel->getProjectDir().'/factories';
        $doctrine = new RepositoryStore($entityManager);
        $this->factory = new FactoryMuffin($doctrine);
        $this->factory->loadFactories($factoriesDir);
    }

    protected function createHttpClient(): KernelBrowser
    {
        return static::createClient([], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ]);
    }

    protected function assertHttpStatusCode(int $statusCode, Response $response): void
    {
        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    protected function assertResponseMatchesSnapshot(Response $response): void
    {
        $actual = trim($this->prettifyJson($response->getContent()));
        $expected = trim($this->getSnapshotContent());

        $matcher = self::$container->get(Matcher::class);

        if (!$matcher->match($actual, $expected)) {
            $this->fail((new Differ())->diff($expected, $actual));
        }
    }

    private function prettifyJson(string $content): string
    {
        return json_encode(json_decode($content), JSON_PRETTY_PRINT);
    }

    private function getSnapshotContent(): string
    {
        return file_get_contents(
            $this->getSnapshotDirectory().
            DIRECTORY_SEPARATOR.
            $this->getSnapshotId().
            '.json'
        );
    }

    private function getSnapshotId(): string
    {
        return (new ReflectionClass($this))->getShortName().'__'.$this->getName();
    }

    private function getSnapshotDirectory(): string
    {
        return dirname((new ReflectionClass($this))->getFileName()).
            DIRECTORY_SEPARATOR.
            '__snapshots__';
    }
}
