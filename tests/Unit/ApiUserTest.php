<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Client\Response;
use App\Repositories\ApiRepository;
use App\Repositories\UserApiRepository;
use App\Services\UserService;
use App\Models\User;

class ApiUserTest extends \Tests\TestCase
{

    public function test_getUserResponses()
    {
        $mock = $this->getMockBuilder(UserApiRepository::class)
            ->onlyMethods([
            'makeApiCall'
        ])
            ->getMock();

        $mock->expects($this->once())
            ->method('makeApiCall')
            ->willReturn([
            new class() {

                public function json()
                {}
            }
        ]);

        $responses = $mock->getUserResponses();

        $this->assertIsArray($responses, "Should be an array");
    }

    public function test_getUserData_canHandleEmptyArrays()
    {
        $mock = $this->getMockBuilder(UserApiRepository::class)
            ->onlyMethods([])
            ->getMock();

        $result = $mock->getUserData([]);
        $this->assertIsArray($result, "Can handle empty array");
    }

    public function test_getUserData()
    {
        $mock = $this->getMockBuilder(UserApiRepository::class)
            ->onlyMethods([])
            ->getMock();

        $result = $mock->getUserData([
            [
                'data' => []
            ]
        ]);
        $this->assertIsArray($result, "Can handle empty array");
    }

    public function test_makeApiCall()
    {}

    public function test_makeSubsequentApiCall()
    {}

    public function test_upsert_canCreateNewUser()
    {
        $mock = $this->getMockBuilder(UserService::class)
            ->onlyMethods([])
            ->getMock();

        $mock->upsert([
            [
                'name' => 'James T. Kirk',
                'email' => 'captain@starfleet.org',
                'password' => 'ncc 1701'
            ]
        ], new User());

        $user = User::where('name', 'James T. Kirk')->first();
        $this->assertEquals('captain@starfleet.org', $user->getAttributes()['email'], 'Can Created user');
    }

    public function test_upsert_canUpdateUser()
    {
        $mock = $this->getMockBuilder(UserService::class)
            ->onlyMethods([])
            ->getMock();

        $mock->upsert([
            [
                'name' => 'James T. Kirk',
                'email' => 'admiral@starfleet.org',
                'password' => 'ncc 1701'
            ]
        ], new User());

        $user = User::where('name', 'James T. Kirk')->first();
        $this->assertEquals('captain@starfleet.org', $user->getAttributes()['email'], 'Can Created user');

        // teardown
        $user = User::where('name', 'James T. Kirk')->delete();
    }
}
