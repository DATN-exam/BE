<?php

namespace App\Services\Site;

use App\Enums\User\UserStatus;
use App\Events\Site\UserRegisterEvent;
use App\Models\Image;
use App\Models\User;
use App\Notifications\Auth\ForgotPassNotification;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;
use App\Services\FileService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Throwable;
use Tymon\JWTAuth\Token;

class AuthService extends BaseService
{
    private $path;
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected FileService $fileSer
    ) {
        $this->path = config('define.path.avatar');
    }

    public function login(): array
    {
        $credentials = $this->data;
        if (!$token = auth('api')->attempt($credentials)) {
            throw new \Exception(__('alert.auth.login.failed'));
        }
        $this->checkStatus(auth('api')->user()->status);
        return $this->respondWithToken($token);
    }

    public function loginWithgoogle($user)
    {
        if (!$token = auth('api')->login($user)) {
            throw new \Exception(__('alert.auth.login.failed'));
        }
        $this->checkStatus(auth('api')->user()->status);
        return $this->respondWithToken($token);
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function profile(): User
    {
        return auth('api')->user();
    }

    private function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }

    private function checkStatus($status): void
    {
        switch ($status) {
            case UserStatus::BLOCK:
                throw new \Exception(__('alert.auth.login.blocked'));
                return;
            case UserStatus::ADMIN_BLOCK:
                throw new \Exception(__('alert.auth.login.blocked'));
                return;
            case UserStatus::WAIT_VERIFY:
                throw new \Exception(__('alert.auth.login.wait_verify'));
                return;
            default:
                return;
        }
    }

    public function register()
    {
        $tokenVerify = Str::random(40);
        $dataCreate = [...$this->data, 'token_verify' => $tokenVerify];
        $user = $this->userRepo->create($dataCreate);
        // $linkVerify = route('auth.verify', ['token' => $this->createVerifyToken($user)]);
        $linkVerify = config('define.url_verify') . "?token=" . $this->createVerifyToken($user);
        event(new UserRegisterEvent($user, $linkVerify));
        return $user;
    }

    private function createVerifyToken(User $user)
    {
        $data = [
            'user_id' => $user->id,
            'random' => rand() . time(),
            'token' => $user->token_verify,
        ];
        $token = JWTAuth::getJWTProvider()->encode($data);
        return $token;
    }

    public function handleVerify($rq)
    {
        $user = $this->checkVerifyToken($rq['token']);
        if ($user) {
            return $this->verify($user);
        }
        throw new \Exception();
    }

    private function checkVerifyToken($token)
    {
        $decode =  JWTAuth::getJWTProvider()->decode($token);
        return $this->userRepo->findUserVerify($decode['token'], $decode['user_id']);
    }

    public function verify(User $user)
    {
        $this->userRepo->update($user, [
            'status' => UserStatus::ACTIVE,
            'token_verify' => null,
        ]);
        return true;
    }

    public function update()
    {
        DB::beginTransaction();
        try {
            /**
             * @var User $user
             */
            $user = auth('api')->user();
            if (isset($this->data['avatar'])) {
                $file = $this->data['avatar'];
                if ($user->avatar) {
                    $this->fileSer->delete([$user->avatar->path]);
                }

                $image = new Image();
                $newPath = generatePathFile($this->path, $user->id, $file->getClientOriginalExtension());
                $image->old_name = $file->getClientOriginalName();
                $image->path = $newPath;
                $newImage = $this->userRepo->saveAvatar($user, $image);
                $this->fileSer->save($newImage->path, file_get_contents($file));
            }
            $dataUpdate = Arr::except($this->data, ['avatar']);
            $this->userRepo->update($user, $dataUpdate);
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            return throw $e;
        }
    }

    public function forgotPass()
    {
        $now = Carbon::now();
        $user = User::where('email', $this->data['email'])->first();

        if ($user->status !== UserStatus::ACTIVE) {
            throw new \Exception('Tài khoản của bạn đã bị khóa');
        }

        if ($user->email_forgot_at > Carbon::now()->subMinute(120)) {
            throw new \Exception('Mỗi 120 phút bạn mới có thể yêu cầu đặt lại mật khẩu 1 lần');
        }
        $token = JWTAuth::customClaims([
            'exp' => Carbon::now()->addMinutes(120)->timestamp
        ])->fromUser($user);
        $user->email_forgot_at = $now;
        $user->save();
        Notification::send($user, new ForgotPassNotification($token));
        return;
    }

    public function confirmForgotPass()
    {
        $token = $this->data['token'];
        try {
            JWTAuth::setToken($token);
            if (!JWTAuth::check()) {
                return throw new \Exception('Token không hợp lệ');
            }
            $data = JWTAuth::getJWTProvider()->decode($token);
            $user = User::find($data['sub']);
            $user->password = Hash::make($this->data['new_password']);
            JWTAuth::invalidate(true);
            $user->save();
            return;
        } catch (Throwable $e) {
            return throw new \Exception('Token không hợp lệ');
        }
    }
}
