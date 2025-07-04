<?php

namespace App\Services;

use gusapi\GusApi;
use gusapi\ReportType;
use gusapi\exception\GusException;

class GUSApiService
{
    protected GusApi $client;

    public function __construct()
    {
        $this->client = new GusApi(config('services.gus.key'));
    }

    public function getCompanyByNip(string $nip): array|string|null
    {
        try {
            $this->client->login();

            $companies = $this->client->getByNip($nip);
            if (empty($companies)) {
                return null;
            }

            $company = $companies[0];
            $report = $this->client->getFullReport($company, ReportType::REPORT_CEIDG);

            return [
                'name' => $company->getName(),
                'regon' => $company->getRegon(),
                'city' => $company->getCity(),
                'street' => $company->getStreet(),
                'zip' => $company->getZipCode(),
                'report' => $report,
            ];
        } catch (GusException $e) {
            return 'Błąd: ' . $e->getMessage();
        }
    }
}