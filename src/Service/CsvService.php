<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class CsvService
{
    public function jsonToCsv(string $jsonFilePath): string
    {
        $filesystem = new Filesystem();

        // Verificar si el archivo JSON existe
        if (!$filesystem->exists($jsonFilePath)) {
            throw new \Exception("El archivo JSON no existe: " . $jsonFilePath);
        }

        // Leer el archivo JSON
        $jsonData = file_get_contents($jsonFilePath);
        $dataArray = json_decode($jsonData, true)['users']; // Datos de usuarios en array

        // Ruta del archivo CSV
        $today = date('Ymd');
        $csvFilePath = __DIR__ . "/../../data/ETL_{$today}.csv";

        // Abrir archivo CSV para escritura
        $csvFile = fopen($csvFilePath, 'w');

        // Obtener las claves del primer elemento del array para usarlas como encabezado
        if (!empty($dataArray)) {
            fputcsv($csvFile, array_keys($dataArray[0]));
        }

        // Escribir cada fila del array en el CSV
        foreach ($dataArray as $row) {
            fputcsv($csvFile, $row);
        }

        // Cerrar archivo CSV
        fclose($csvFile);

        return $csvFilePath;
    }
}
