<?php

namespace App\Services;

use GusApi\GusApi;
use GusApi\ReportTypes;
use GusApi\Exception\InvalidUserKeyException;
use GusApi\Exception\NotFoundException;
use Exception;


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
            
            return [
                'name' => $company->getName(),
                'regon' => $company->getRegon(),
                'city' => $company->getCity(),
                'street' => $company->getStreet(),
                'zip' => $company->getZipCode(),
                
            ];
        } catch (InvalidUserKeyException $e) {
            report($e);
            return 'Błąd: nieprawidłowy klucz użytkownika — ' . $e->getMessage();
        } catch (NotFoundException $e) {
            report($e);
            return 'Nie znaleziono podanego NIP-u';
        } catch (Exception $e) {
            report($e);
            return 'Wystąpił błąd: ' . $e->getMessage();
        }
    }
}