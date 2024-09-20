<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class DatabaseService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveProcessHeader(\DateTime $executionDate): int
    {
        $processHeader = new ProcessHeader();
        $processHeader->setExecutionDate($executionDate);

        $this->entityManager->persist($processHeader);
        $this->entityManager->flush();

        return $processHeader->getId();
    }

    public function saveSummaryData(int $processId, array $summaryData): void
    {
        foreach ($summaryData as $data) {
            $summary = new Summary();
            $summary->setProcessId($processId);
            $summary->setAgeRange($data['age_range']);
            $summary->setCount($data['count']);
            
            $this->entityManager->persist($summary);
        }

        $this->entityManager->flush();
    }

    public function saveEtlData(int $processId, array $etlData): void
    {
        foreach ($etlData as $data) {
            $etl = new Etl();
            $etl->setProcessId($processId);
            $etl->setFirstName($data['first_name']);
            $etl->setLastName($data['last_name']);
            $etl->setMaidenName($data['maiden_name']);
            $etl->setAge($data['age']);
            $etl->setGender($data['gender']);
            $etl->setEmail($data['email']);
            $etl->setPhone($data['phone']);
            $etl->setUsername($data['username']);
            $etl->setPassword($data['password']);
            $etl->setBirthDate($data['birth_date']);
            $etl->setImage($data['image']);
            $etl->setBloodGroup($data['blood_group']);
            $etl->setHeight($data['height']);
            $etl->setWeight($data['weight']);
            $etl->setEyeColor($data['eye_color']);
            $etl->setHairColor($data['hair_color']);
            $etl->setHairType($data['hair_type']);
            $etl->setIP($data['ip']);
            $etl->setAddress($data['address']);
            $etl->setMacAddress($data['mac_address']);
            $etl->setUniversity($data['university']);
            $etl->setBank($data['bank']);
            $etl->setCompany($data['company']);
            $etl->setEin($data['ein']);
            $etl->setSsn($data['ssn']);
            $etl->setUserAgent($data['user_agent']);
            $etl->setCrypto($data['crypto']);
            $etl->setRole($data['role']);

            $this->entityManager->persist($etl);
        }

        $this->entityManager->flush();
    }
}
