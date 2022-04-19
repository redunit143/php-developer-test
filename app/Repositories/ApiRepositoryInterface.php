<?php
namespace App\Repositories;

interface ApiRepositoryInterface
{

    /**
     *
     * @param string $endpointName
     * @return array
     */
    public function makeApiCall(string $endpointName): array;

    /**
     *
     * @param string $url
     * @param int $totalPages
     * @return array
     */
    public function makeSubsequentApiCall(string $url, int $totalPages): array;
}
