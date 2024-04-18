<?php

namespace App\Services\Site;

use App\Enums\User\UserStatus;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthService extends BaseService
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected AuthService $authService
    ) {
        //
    }

    public function getUrlLogin()
    {
        return $this->getGoogleDrive()->stateless()->redirect()->getTargetUrl();
    }

    public function loginCallback()
    {
        $googleUser = $this->getGoogleDrive()->stateless()->user()->user;
        $user = $this->checkUserExists($googleUser);
        if (!$user) {
            $user = $this->registerUserGoogle($googleUser);
        }
        try {
            $jsonResponse = [
                ...$this->authService->loginWithgoogle($user),
                "status" => "success"
            ];
        } catch (Throwable $e) {
            $jsonResponse = [
                "message" => $e->getMessage(),
                "status" => "failed",
            ];
        }
        return $this->dataReturn(json_encode($jsonResponse));
    }

    private function checkUserExists($googleUser)
    {
        $user = $this->userRepo->checkUserExists($googleUser['email']);
        if ($user === null) {
            return $user;
        }

        $dataUpdate = [];
        if ($user->status === UserStatus::WAIT_VERIFY) {
            $dataUpdate['status'] = UserStatus::ACTIVE;
        }

        if ($user->google_id === null) {
            $dataUpdate['google_id'] = $googleUser['sub'];
        }

        if (!empty($dataUpdate)) {
            $this->userRepo->update($user, $dataUpdate);
        }
        return $user;
    }

    private function registerUserGoogle($googleUser)
    {
        $data = [
            'email' => $googleUser['email'],
            'google_id' => $googleUser['sub'],
            'first_name' => $googleUser['family_name'],
            'last_name' => $googleUser['given_name'],
            'status' => UserStatus::ACTIVE
        ];
        return $this->userRepo->create($data);
    }

    private function getGoogleDrive()
    {
        /**
         * @var Mix $googleDrive
         */
        $googleDrive = Socialite::driver('google');
        return $googleDrive;
    }

    function dataReturn($jsonResponse)
    {
        return "<script>
                window.opener.receiveDataFromGoogleLoginWindow(
                    {$jsonResponse}
                );
                window.close();
            </script>";
    }
}
