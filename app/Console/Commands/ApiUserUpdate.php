<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ApiRepositoryInterface;
use App\Services\UserServiceInterface;
use App\Models\User;

class ApiUserUpdate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apiupdate:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates users from api';

    /**
     *
     * @var UserApiRepository
     */
    protected $userRepo;

    /**
     *
     * @var UserService
     */
    protected $userService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ApiRepositoryInterface $userApiRepository, UserServiceInterface $userService)
    {
        parent::__construct();
        $this->userRepo = $userApiRepository;
        $this->userService = $userService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userResponses = $this->userRepo->getUserResponses();
        $userData = $this->userRepo->getUserData($userResponses);

        array_map(function ($data) {
            $this->userService->upsert($data, new User());
        }, $userData);

        echo "updated/added " . count($userData) . PHP_EOL;
        return 0;
    }
}
