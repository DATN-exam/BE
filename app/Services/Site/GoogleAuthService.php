<?php

namespace App\Services\Site;

use App\Enums\User\UserStatus;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;
use Laravel\Socialite\Facades\Socialite;

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
        $googleUser = $this->data;
        $message = __('alert.auth.login.success');
        $user = $this->checkUserExists($googleUser);
        if (!$user) {
            $user = $this->registerUserGoogle($googleUser);
            $message = __('alert.auth.register.success');
        }
        return [
            ...$this->authService->loginWithgoogle($user),
            "message" => $message
        ];
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

    function dataReturn($type, $mess)
    {
        return "<script>
                window.opener.receiveDataFromGoogleLoginWindow({status:'$type',message:'$mess'});
                window.close();
            </script>";
    }
}
