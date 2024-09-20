<?php

namespace App\Service;

use phpseclib3\Net\SFTP;

class SftpService
{
    private $sftp;

    public function __construct(string $apiToken, string $apiEndpoint, string $host, string $username, string $password, number $port)
    {
        // Establecer la conexión SFTP
        $this->sftp = new SFTP($host);

        // Verificar si la conexión y el login son exitosos
        if (!$this->sftp->login($username, $password)) {
            throw new \Exception('Login to SFTP server failed');
        }
    }

    public function uploadFile(string $localFilePath, string $remoteFilePath): void
    {
        // Verificar si el archivo local existe y es un archivo CSV
        if (!file_exists($localFilePath)) {
            throw new \Exception("Local file does not exist: $localFilePath");
        }

        if (mime_content_type($localFilePath) !== 'text/csv') {
            throw new \Exception("File is not a valid CSV file: $localFilePath");
        }

        // Subir el archivo usando SFTP::SOURCE_LOCAL_FILE para archivos grandes
        if (!$this->sftp->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
            throw new \Exception("Failed to upload file to SFTP: $localFilePath");
        }

        // Opcional: Verificar que el archivo se subió correctamente comparando tamaños
        $localFileSize = filesize($localFilePath);
        $remoteFileSize = $this->sftp->size($remoteFilePath);

        if ($localFileSize !== $remoteFileSize) {
            throw new \Exception("Uploaded file size does not match the local file size");
        }
    }

    public function uploadCsv(SftpService $sftpService)
{
    $localFilePath = '/ruta/local/al/archivo.csv';
    $remoteFilePath = '/ruta/remota/en/el/servidor/archivo.csv';

    try {
        $sftpService->uploadFile($localFilePath, $remoteFilePath);
        echo "Archivo subido correctamente!";
    } catch (\Exception $e) {
        echo "Error al subir el archivo: " . $e->getMessage();
    }
}
}

