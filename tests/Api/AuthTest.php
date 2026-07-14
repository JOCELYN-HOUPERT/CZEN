<?php

namespace App\Tests\Api;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthTest extends WebTestCase
{
    private function supprimerUtilisateur(string $email): void
    {
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $email]);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }

    // Test 1 — L'inscription fonctionne
    public function testRegister(): void
    {
        $client = static::createClient();
        $this->supprimerUtilisateur('test_regression@test.com');

        $client->request('POST', '/api/register', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nom' => 'Test User',
                'email' => 'test_regression@test.com',
                'password' => '123456'
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $data);
    }

    // Test 2 — La connexion retourne un token
    public function testLogin(): void
    {
        $client = static::createClient();
        $this->supprimerUtilisateur('test_regression@test.com');

        $client->request('POST', '/api/register', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nom' => 'Test User',
                'email' => 'test_regression@test.com',
                'password' => '123456'
            ])
        );

        $client->request('POST', '/api/login', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'test_regression@test.com',
                'password' => '123456'
            ])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);
    }

    // Test 3 — Les ressources sont accessibles sans connexion
    public function testRessourcesPubliques(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/ressources');

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
    }

    // Test 4 — Le diagnostic retourne un score
    public function testDiagnosticScore(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/diagnostic/resultat', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['questions' => []])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(0, $data['score']);
        $this->assertEquals('Faible', $data['niveau']);
    }

    // Test 5 — Impossible de créer deux comptes avec le même email
    public function testRegisterEmailUnique(): void
    {
        $client = static::createClient();
        $this->supprimerUtilisateur('doublon@test.com');

        $client->request('POST', '/api/register', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nom' => 'Test Doublon',
                'email' => 'doublon@test.com',
                'password' => '123456'
            ])
        );
        $this->assertResponseStatusCodeSame(201);

        $client->request('POST', '/api/register', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nom' => 'Test Doublon 2',
                'email' => 'doublon@test.com',
                'password' => '654321'
            ])
        );

        $this->assertResponseStatusCodeSame(409);
    }
}
