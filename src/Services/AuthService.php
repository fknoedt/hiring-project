<?php
namespace App\Services;

use App\Exceptions\AuthException;
use App\Models\UsageLogModel;
use App\Models\UserModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Class AuthService
 * This class depends on $_SESSION (session_start has to be previously called)
 * @package App\Services
 */
class AuthService extends MainService
{
    /**
     * creds required to login
     */
    const REQUIRED_CREDENTIALS = [
        'username',
        'password'
    ];

    /**
     * maximum length a credential input can have
     */
    const MAX_INPUT_LENGTH = 100;

    const PASSWORD_ALGORITHM = PASSWORD_DEFAULT;

    /**
     * is the user logged in?
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated'];
    }

    public static function getUserId()
    {
        if (! self::isLoggedIn()) {
            return false;
        }

        return $_SESSION['user']->id;
    }

    /**
     * is it an AJAX request (XHR)?
     * @return bool
     */
    public function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    /**
     * update DB and destroy session
     */
    public function logout(): void
    {
        $this->stopUsageLog();
        session_destroy();
    }

    /**
     * @param $credentials
     * @throws AuthException
     * @return bool
     */
    public function login(array $credentials): bool
    {
        if (self::isLoggedIn()) {
            throw new AuthException(__METHOD__ . ": logged in user trying to log in", 400); // bad request
        }

        // validate input
        $this->validateCredentials($credentials);

        $username = $credentials['username'];
        $password = $credentials['password'];

        // we only hash the password to store it; to validate we use password_verify
        // $passwordHash   = password_hash($password, self::PASSWORD_ALGORITHM);

        // helper on bootstrap.php
        // enableQueryLog();

        // let's use password_hash/verify()
        $user = UserModel::where([
            'username' => $username
        ])->first();

        // user not found
        if (! is_object($user)) {
            throw new AuthException("Invalid Login", 403);
        }

        // invalid inputted password
        if (! password_verify($password, $user->password)) {
            throw new AuthException("Invalid Password", 403);
        }

        // change session id
        session_regenerate_id(true);

        // store session in the database
        $this->startUsageLog($user);

        // main "logged in" flag
        $_SESSION['authenticated'] = true;
        // let's also save the user
        $_SESSION['user'] = $user;

        // dd(getQueryLog());

        return true;
    }

    /**
     * Validate $credentials against self::REQUIRED_CREDENTIALS
     * @param array $credentials
     * @throws AuthException
     */
    public function validateCredentials(array $credentials): void
    {
        // validate each required field
        foreach (self::REQUIRED_CREDENTIALS as $cred) {

            if (! isset($credentials[$cred]) || empty($credentials[$cred])) {
                throw new AuthException(__METHOD__ . ": invalid credentials ({$cred} missing or empty)", 422); // unprocessable entity
            }
            if (strlen($credentials[$cred]) > self::MAX_INPUT_LENGTH) {
                throw new AuthException(__METHOD__ . ": input ({$cred}) cannot exceed " . self::MAX_INPUT_LENGTH, 422); // unprocessable entity
            }

        }
    }

    /**
     * @param UserModel $user
     */
    public function startUsageLog(UserModel $user): void
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            'userId' => $user->id,
            'sessionId' => session_id(),
            'login' => $now,
            'last_movement' => $now
        ];

        $usageLog = new UsageLogModel($data);

        $usageLog->save();
    }

    /**
     * update usage_log.last_movement for the current user/session
     */
    public function updateUserTime(): void
    {
        $now = date('Y-m-d H:i:s');
        DB::table(UsageLogModel::getTableName())
            ->where('sessionId', session_id())
            ->update(['last_movement' => $now]);
    }

    /**
     * update usage_log.last_movement for the current user/session
     */
    public function stopUsageLog(): void
    {
        $now = date('Y-m-d H:i:s');
        DB::table(UsageLogModel::getTableName())
            ->where('sessionId', session_id())
            ->update(['logout' => $now]);
    }
}