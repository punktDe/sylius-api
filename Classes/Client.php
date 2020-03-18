<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise\PromiseInterface;
use Neos\Flow\Annotations as Flow;
use GuzzleHttp\HandlerStack;
use PunktDe\Sylius\Api\Exception\SyliusApiConfigurationException;
use Sainsburys\Guzzle\Oauth2\GrantType\PasswordCredentials;
use Sainsburys\Guzzle\Oauth2\GrantType\RefreshToken;
use Sainsburys\Guzzle\Oauth2\Middleware\OAuthMiddleware;

class Client
{
    /**
     * @var string
     * @Flow\InjectConfiguration(path="clientId")
     */
    protected $clientId = '';

    /**
     * @var string
     * @Flow\InjectConfiguration(path="clientSecret")
     */
    protected $clientSecret = '';

    /**
     * @var string
     * @Flow\InjectConfiguration(path="apiUser")
     */
    protected $apiUser = '';

    /**
     * @var string
     * @Flow\InjectConfiguration(path="apiPassword")
     */
    protected $apiPassword = '';

    /**
     * @var string
     * @Flow\InjectConfiguration(path="baseUri")
     */
    protected $baseUri = '';

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @throws SyliusApiConfigurationException
     */
    public function initializeObject(): void
    {
        $this->validateConfiguration();

        $oauthClientConfig = [
            PasswordCredentials::CONFIG_USERNAME => $this->apiUser,
            PasswordCredentials::CONFIG_PASSWORD => $this->apiPassword,
            PasswordCredentials::CONFIG_CLIENT_ID => $this->clientId,
            PasswordCredentials::CONFIG_CLIENT_SECRET => $this->clientSecret,
            PasswordCredentials::CONFIG_TOKEN_URL => '/api/oauth/v2/token',
        ];

        $oauthClient = new HttpClient(['base_uri' => $this->baseUri]);
        $grantType = new PasswordCredentials($oauthClient, $oauthClientConfig);
        $refreshToken = new RefreshToken($oauthClient, $oauthClientConfig);
        $middleware = new OAuthMiddleware($oauthClient, $grantType, $refreshToken);

        $handlerStack = HandlerStack::create();
        $handlerStack->push($middleware->onBefore());
        $handlerStack->push($middleware->onFailure(5));

        $options = [
            'handler' => $handlerStack,
            'base_uri' => $this->baseUri . '/api/v1/',
            'auth' => 'oauth2',
            'http_errors' => false,
            'headers' => [
                'User-Agent' => 'PunktDe SyliusApi/1.0',
                'Accept' => 'application/json',
            ]
        ];

        $this->httpClient = new HttpClient($options);
    }

    /**
     * @param string $url
     * @param mixed[] $body
     * @param string[] $files
     * @return PromiseInterface
     */
    public function postAsync(string $url, array $body, array $files = []): PromiseInterface
    {
        $options = ['json' => $body];
        foreach ($files as $key => $filePath) {
            $options['multipart'][] = [
                'name' => $key,
                'contents' => $filePath,
            ];
        }

        return $this->httpClient->requestAsync('POST', $url, $options);
    }

    /**
     * @param string $url
     * @param mixed[] $body
     * @return PromiseInterface
     */
    public function patchAsync(string $url, array $body): PromiseInterface
    {
        return $this->httpClient->patchAsync($url, ['json' => $body]);
    }

    /**
     * @param string $url
     * @param mixed[] $body
     * @return PromiseInterface
     */
    public function putAsync(string $url, array $body): PromiseInterface
    {
        return $this->httpClient->requestAsync('PUT', $url, ['body' => json_encode($body), 'headers' => [
            'Content-Type' => 'application/json'
        ]
        ]);
    }

    /**
     * @param string $url
     * @param mixed[] $queryParameters
     * @return PromiseInterface
     */
    public function getAsync(string $url, array $queryParameters = []): PromiseInterface
    {
        return $this->httpClient->requestAsync('GET', $url, ['query' => $queryParameters]);
    }

    /**
     * @param string $url
     * @return PromiseInterface
     */
    public function deleteAsync(string $url): PromiseInterface
    {
        return $this->httpClient->requestAsync('DELETE', $url);
    }

    /**
     * Get a client configuration option.
     *
     * These options include default request options of the client, a "handler"
     * (if utilized by the concrete client), and a "base_uri" if utilized by
     * the concrete client.
     *
     * @param string|null $option The config option to retrieve.
     *
     * @return mixed
     *
     * phpcs:disable
     */
    public function getConfig($option = null)
    {
        return $this->httpClient->getConfig($option);
    }

    /**
     * @throws SyliusApiConfigurationException
     */
    private function validateConfiguration(): void
    {
        $requiredSettingKeys = ['apiUser', 'apiPassword', 'baseUri', 'clientSecret', 'clientId'];
        foreach ($requiredSettingKeys as $requiredSettingKey) {
            if (trim($this->$requiredSettingKey) === '') {
                throw new SyliusApiConfigurationException(sprintf('The required configuration setting %s for the Sylius API was not set or empty', $requiredSettingKeys), 1572349688);
            }
        }
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }
}
