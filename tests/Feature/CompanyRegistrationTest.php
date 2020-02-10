<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CompanyRegistrationTest extends TestCase
{
    public function test_company_can_register_itself(): void
    {
        $payload = [
            'name' => 'Acme, Inc',
            'email' => 'john.snow@acme.com',
            'password' => [
                'first' => 's0m3p4sswor6',
                'second' => 's0m3p4sswor6',
            ],
        ];

        $this->client->request('POST', '/api/company/registration', [], [], [], json_encode($payload));
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_CREATED, $response);
        $this->assertResponseMatchesSnapshot($response);
    }

    /**
     * @dataProvider requiredFields
     */
    public function test_validate_required_fields(string $field, string $message): void
    {
        $this->client->request('POST', '/api/company/registration', [], [], [], json_encode([
            'name' => '',
            'email' => '',
        ]));
        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $this->assertHttpStatusCode(Response::HTTP_BAD_REQUEST, $response);
        $this->assertContains($message, $responseBody['errors'][$field]);
    }

    /**
     * @dataProvider passwordValidations
     */
    public function test_validate_password_field(array $password, string $message): void
    {
        $this->client->request('POST', '/api/company/registration', [], [], [], json_encode([
            'password' => $password,
        ]));
        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $this->assertHttpStatusCode(Response::HTTP_BAD_REQUEST, $response);
        $this->assertContains($message, $responseBody['errors']['password']['first']);
    }

    public function test_validate_email_format(): void
    {
        $this->client->request('POST', '/api/company/registration', [], [], [], json_encode([
            'email' => 'invalid_email_format',
        ]));
        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $this->assertHttpStatusCode(Response::HTTP_BAD_REQUEST, $response);
        $this->assertContains(
            'This value is not a valid email address.',
            $responseBody['errors']['email']
        );
    }

    public function requiredFields(): iterable
    {
        yield ['name', 'This value should not be blank.'];
        yield ['email', 'This value should not be blank.'];
    }

    public function passwordValidations(): iterable
    {
        yield [['first' => '', 'second' => ''], 'This value should not be blank.'];
        yield [['first' => 'strongpassword', 'second' => 'passwordstrong'], 'This value is not valid.'];
        yield [['first' => '123', 'second' => '123'], 'This value is too short. It should have 6 characters or more.'];
    }
}
