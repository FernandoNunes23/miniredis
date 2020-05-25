<?php


namespace App\Client;


use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client as GuzzleClient;

class Connection
{
    /** @var string  */
    const DELETE = 'DELETE';

    /** @var string  */
    const POST = 'POST';

    /** @var string  */
    const PUT = 'PUT';

    /** @var string  */
    const GET = 'GET';

    /** @var string API base URL */
    const API_URL = 'http://localhost:8080';

    /** @var string Access Token */
    public $accessToken;

    /**
     * Connection constructor.
     *
     * @param $username
     * @param $password
     *
     * @throws \Exception
     */
    public function __construct($username = null, $password = null)
    {
        $this->accessToken = $this->getAccessToken($username, $password);

        if (!$this->accessToken) {
            throw new \Exception("Error on auth.");
        }
    }

    /**
     *
     * @param string $method
     * @param string $url
     * @param array $data
     *
     * @return ResponseInterface
     */
    public function send($method, $url, $data = [])
    {
        $client = new GuzzleClient();

        $options = [
            'headers' => $this->buildHeaders(),
            'exceptions' => false
        ];

        if (!empty($data)) {
            if ($method == self::POST || $method == self::PUT) {
                $options['form_params'] = $data;
            }

            if ($method === self::GET || $method === self::DELETE) {
                $url .= '?' . http_build_query($data);
            }
        }

        return $client->request($method, self::API_URL. $url, $options);
    }

    /**
     * @param $username
     * @param $password
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getAccessToken($username, $password)
    {
        try {
            $tokenFileData = $this->readTokenFile();

            if ($tokenFileData && (is_null($username) && is_null($password))) {
                $reponseArray = json_decode($tokenFileData, true);

                return $reponseArray['token'];
            }

            $client = new GuzzleClient();

            $options = [
                'headers' => ['Accept' => 'application/json'],
                'exceptions' => false,
                'auth' => [$username, $password]
            ];

            // Try to get an access token using the client credentials grant.
            $response = $client->request(self::POST, self::API_URL. '/token', $options);

            $reponseArray = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() == 200) {
                $this->createTokenFile($reponseArray['data']);
            }

            return $reponseArray['data']['token'];
        } catch (\Exception $exception) {
            // Failed to get the access token
            throw new \Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param array $token
     */
    private function createTokenFile(array $token)
    {
        $tokenFile = '../../var/token';
        $handle = fopen($tokenFile, 'w') or die('Cannot open file:  '.$tokenFile);
        fwrite($handle, json_encode($token));
        fclose($handle);
    }

    /**
     * @return false|string
     */
    private function readTokenFile()
    {
        $tokenFile = '../../var/token';

        if (file_exists($tokenFile) == false) {
            return false;
        }

        $handle = fopen($tokenFile, 'r');
        $token = fread($handle,filesize($tokenFile));

        return $token;
    }

    /**
     * Monta cabeçalho de requisição, adicionando o access_token
     *
     * @return array
     */
    private function buildHeaders()
    {
        return [
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ];
    }
}