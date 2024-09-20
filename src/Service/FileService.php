<?php

namespace App\Service;

class FileService
{
    public function saveDataToFile(array $data): string
    {
        $date = new \DateTime();
        $fileName = 'data_' . $date->format('Ymd') . '.json';
        $filePath = __DIR__ . '/../../data/' . $fileName;

        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));

        return $filePath;
    }
}
