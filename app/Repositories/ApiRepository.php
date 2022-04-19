<?php
namespace App\Repositories;


use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class ApiRepository implements ApiRepositoryInterface
{

    /**
     *
     * @var int
     */
    public $perPages = 6;

    /**
     *
     * @var string
     */
    public $totalPagesKey = 'total_pages';

    /**
     *
     * @var string
     */
    public $perPagesKey = 'per_pages';

    /**
     *
     * @var string
     */
    public $pageNumberKey = 'page';

    /**
     *
     * @var array
     */
    protected $endpoints = [];

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\ApiRepositoryInterface::makeApiCall()
     */
    public function makeApiCall(string $endpointName): array
    {
        $url = $this->endpoints[$endpointName] . "?" . $this->perPagesKey . "=" . $this->perPages;
        $page1 = Http::get($url);
        if (! $page1->ok()) {
            return [];
        }
        $totalPages = $page1->json($this->totalPagesKey, 0);
        if ($totalPages == 1) {
            return [
                $page1
            ];
        }

        $responses = $this->makeSubsequentApiCall($url, $totalPages);
        array_unshift($responses, $page1);
        return $responses;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\ApiRepositoryInterface::makeSubsequentApiCall()
     */
    public function makeSubsequentApiCall(string $url, int $totalPages): array
    {
        return Http::pool(function (Pool $pool) use ($url, $totalPages) {
            $poolPromises = [];
            for ($page = 2; $page <= $totalPages; $page ++) {
                $poolPromises[] = $pool->get($url . '&' . $this->pageNumberKey . '=' . $page);
            }

            return $poolPromises;
        });
    }
}
