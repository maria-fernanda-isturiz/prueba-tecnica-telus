<?php

namespace App\Command;

use App\Service\ApiService;
use App\Service\FileService;
use App\Service\SftpService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchUsersCommand extends Command
{
    protected static $defaultName = 'app:fetch-users';

    private $apiService;
    private $csvService;
    private $sftpService;

    public function __construct(ApiService $apiService, CsvService $csvService, SftpService $sftpService)
    {
        $this->apiService = $apiService;
        $this->csvService = $csvService;
        $this->sftpService = $sftpService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fetches users from API, saves them to a JSON file, and uploads to SFTP');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Extraer datos de la API
        $io->info('Fetching users from the API...');
        $users = $this->apiService->fetchUsers();

        // Transformar los datos y guardarlos en un archivo CSV
        $io->info('Transforming data to CSV...');
        $csvFilePath = $this->csvService->saveDataToCsv($users);
        $io->info('Data saved to ' . $csvFilePath);

        // Subir el archivo CSV al servidor SFTP
        $io->info('Uploading CSV file to SFTP...');
        $remoteFilePath = '/remote/path/' . basename($csvFilePath);
        if ($this->sftpService->uploadFile($csvFilePath, $remoteFilePath)) {
            $io->success('CSV file uploaded successfully to ' . $remoteFilePath);
        } else {
            $io->error('Failed to upload the CSV file');
        }

        return Command::SUCCESS;
    }
}
