<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Filesystem\Filesystem;

class ApiService
{
    private $client;
    private $apiEndpoint;

    public function __construct(HttpClientInterface $client, string $apiEndpoint)
    {
        $this->client = $client;
        $this->apiEndpoint = $apiEndpoint;
    }

    public function fetchAndSaveData(): string
    {
        $response = $this->client->request('GET', $this->apiEndpoint);

        // Verificar si la solicitud fue exitosa
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Error fetching data from API.");
        }

        // Obtener datos
        $data = $response->getContent();

        // Ruta y nombre del archivo JSON
        $today = date('Ymd');
        $filePath = __DIR__ . "/../../data/data_{$today}.json";

        // Guardar los datos en un archivo JSON
        $filesystem = new Filesystem();
        $filesystem->dumpFile($filePath, $data);

        return $filePath;
    }

   /* public function fetchUsers(): array
    {
        $response = $this->client->request('GET', $this->apiEndpoint);
        return $response->toArray();
    } */
}

