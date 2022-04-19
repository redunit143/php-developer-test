<?php
namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserApiRepository extends ApiRepository
{

    /**
     *
     * @var array
     */
    protected $endpoints = [
        'users' => 'https://reqres.in/api/users'
    ];

    /**
     *
     * @return array
     */
    public function getUserResponses(): array
    {
        $responses = $this->makeApiCall("users");

        return array_map(function ($response) {
            return $response->json();
        }, $responses);
    }

    /**
     *
     * @param array $userResponses
     * @return array
     */
    public function getUserData(array $userResponses): array
    {
        if (! count($userResponses)) {
            return [];
        }
        // extract data array from response
        $data = array_map(function ($userResponse) {
            return $userResponse['data'];
        }, $userResponses);
        // flatten array
        $flatData = call_user_func_array('array_merge', $data);
        // return an array for upsert
        return array_map(function ($userData) {
            $data = [];
            $data['email'] = $userData['email'];
            $data['name'] = $userData['first_name'] . ' ' . $userData['last_name'];
            $data['password'] = Hash::make(Str::random(8));
            return $data;
        }, $flatData);
    }
}
