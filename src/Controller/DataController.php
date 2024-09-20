<?php

namespace App\Controller;

use App\Service\ApiService;
use App\Service\CsvService;
use App\Service\DatabaseService;
use App\Service\SftpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    private $apiService;
    private $csvService;
    private $databaseService;
    private $sftpService;

    public function __construct(ApiService $apiService, CsvService $csvService, DatabaseService $databaseService, SftpService $sftpService)
    {
        $this->apiService = $apiService;
        $this->csvService = $csvService;
        $this->databaseService = $databaseService;
        $this->sftpService = $sftpService;
    }

    /**
     * @Route("/fetch-data", name="fetch_data")
     */
    public function fetchData(): JsonResponse
    {
        try {
            // 1. Obtener y transformar los datos
            $jsonFilePath = $this->apiService->fetchAndSaveData();
            $csvFilePath = $this->csvService->jsonToCsv($jsonFilePath);

            // 2. Guardar los datos en la base de datos
            $executionDate = new \DateTime();
            $processId = $this->databaseService->saveProcessHeader($executionDate);

            // Guardar los datos de summary_[AAAAMMDD].csv
            $summaryData = $this->csvService->loadCsvData('summary_' . date('Ymd') . '.csv');
            $this->databaseService->saveSummaryData($processId, $summaryData);

            // Guardar los datos de etl_[AAAAMMDD].csv
            $etlData = $this->csvService->loadCsvData('ETL_' . date('Ymd') . '.csv');
            $this->databaseService->saveEtlData($processId, $etlData);

            // 3. Subir los archivos a SFTP
            $this->sftpService->uploadFile($jsonFilePath, '/ruta/remota/data_' . date('Ymd') . '.json');
            $this->sftpService->uploadFile($csvFilePath, '/ruta/remota/etl_' . date('Ymd') . '.csv');
            $this->sftpService->uploadFile('summary_' . date('Ymd') . '.csv', '/ruta/remota/summary_' . date('Ymd') . '.csv');

            return new JsonResponse(['message' => 'Data processed successfully']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}

