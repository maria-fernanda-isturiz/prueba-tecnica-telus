<?php

namespace App\Controller;

use App\Service\SftpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SftpController extends AbstractController
{
    private $sftpService;

    public function __construct(SftpService $sftpService)
    {
        $this->sftpService = $sftpService;
    }

    /**
     * @Route("/sftp/upload", name="sftp_upload")
     */
    public function upload(): JsonResponse
    {
        $localFilePath = '/path/to/local/file.txt';
        $remoteFilePath = '/path/to/remote/file.txt';
        
        $result = $this->sftpService->uploadFile($localFilePath, $remoteFilePath);

        return new JsonResponse(['success' => $result]);
    }

    /**
     * @Route("/sftp/download", name="sftp_download")
     */
    public function download(): JsonResponse
    {
        $remoteFilePath = '/path/to/remote/file.txt';
        $localFilePath = '/path/to/local/file.txt';
        
        $result = $this->sftpService->downloadFile($remoteFilePath, $localFilePath);

        return new JsonResponse(['success' => $result]);
    }
}
