<?php

namespace ATDev\RocketChat\Users;

use ATDev\RocketChat\Common\Request;

/**
 * User class
 */
class User extends Request
{
    use Data;

    /**
     * Gets user listing
     *
     * @return Collection|boolean
     */
    public static function listing()
    {
        static::send("users.list", "GET");

        if (!static::getSuccess()) {
            return false;
        }

        $users = new Collection();
        foreach (static::getResponse()->users as $user) {
            $users->add(static::createOutOfResponse($user));
        }

        return $users;
    }

    /**
     * Creates user at api instance
     *
     * @return User|boolean
     */
    public function create()
    {
        static::send("users.create", "POST", $this);

        if (!static::getSuccess()) {
            return false;
        }

        return $this->updateOutOfResponse(static::getResponse()->user);
    }

    /**
     * Updates user at api instance
     *
     * @return User|boolean
     */
    public function update()
    {
        static::send("users.update", "POST", ["userId" => $this->getUserId(), "data" => $this]);

        if (!static::getSuccess()) {
            return false;
        }

        return $this->updateOutOfResponse(static::getResponse()->user);
    }

    /**
     * Gets extended info of user
     *
     * @return boolean|$this
     */
    public function info()
    {
        static::send("users.info", "GET", ["userId" => $this->getUserId()]);

        if (!static::getSuccess()) {
            return false;
        }

        return $this->updateOutOfResponse(static::getResponse()->user);
    }

    /**
     * Gets extended info of user
     *
     * @return boolean|$this
     */
    public function delete()
    {
        static::send("users.delete", "POST", ["userId" => $this->getUserId()]);

        if (!static::getSuccess()) {
            return false;
        }

        return $this->setUserId(null);
    }

    /**
     * Sets avatar for user
     *
     * @param Avatar $avatar
     *
     * @return boolean|$this
     */
    public function setAvatar(Avatar $avatar)
    {
        if ($avatar::IS_FILE) {
            static::send("users.setAvatar", "POST", ["userId" => $this->getUserId()], ["image" => $avatar->getSource()]);

            if (!static::getSuccess()) {
                return false;
            }
        } else {
            static::send("users.setAvatar", "POST", ["userId" => $this->getUserId(), "avatarUrl" => $avatar->getSource()]);

            if (!static::getSuccess()) {
                return false;
            }
        }

        return $this;
    }

    /**
     * Gets avatar for user
     *
     * @return boolean|$this
     */
    public function getAvatar()
    {
        static::send("users.getAvatar", "GET", ["userId" => $this->getUserId()]);

        if (!static::getSuccess()) {
            return false;
        }

        if (!empty(static::getResponseUrl())) {
            $this->setAvatarUrl(static::getResponseUrl());
        }

        return $this;
    }

    /**
     * Gets all connected users presence
     *
     * @param string|null $from The last date you got a status change. ISO 8601 datetime. Timezone, milliseconds and seconds are optional
     * @return Collection|false
     */
    public static function presence($from = null)
    {
        static::send("users.presence", "GET", ['from' => $from]);
        if (!static::getSuccess()) {
            return false;
        }

        $users = new Collection();
        $response = static::getResponse();
        foreach ($response->users as $user) {
            $users->add(static::createOutOfResponse($user));
        }
        if (isset($response->full)) {
            $users->setFull($response->full);
        }

        return $users;
    }

    /**
     * Gets a user's presence if the query string userId or username is provided, otherwise it gets the callee's
     *
     * @param User|null $user
     * @return false|mixed
     */
    public static function getPresence(User $user = null)
    {
        $params = [];
        if (isset($user) && !empty($user->getUserId())) {
            $params = ['userId' => $user->getUserId()];
        } elseif (isset($user) && !empty($user->getUsername())) {
            $params = ['username' => $user->getUsername()];
        }

        static::send("users.getPresence", "GET", $params);
        if (!static::getSuccess()) {
            return false;
        }
        // @todo: response format
        // response object can be returned within User instance from argument
        $response = static::getResponse();
        return $response;
    }

    /**
     * Deactivate Idle users. Requires `edit-other-user-active-status` permission.
     *
     * @param int $daysIdle
     * @param string $role
     * @return false|mixed
     */
    public static function deactivateIdle($daysIdle, $role = 'user')
    {
        static::send("users.presence", "POST", ['daysIdle' => $daysIdle, 'role' => $role]);
        if (!static::getSuccess()) {
            return false;
        }
        // @todo: response format
        return static::getResponse();
    }

    /**
     * Send email to reset your password.
     *
     * @param string $email
     * @return bool|string
     */
    public function forgotPassword($email)
    {
        static::send("users.forgotPassword", "POST", ['email' => $email]);
        return static::getSuccess();
    }

    /**
     * Gets a user's Status if the query string userId or username is provided, otherwise it gets the callee's.
     *
     * @param User|null $user
     * @return false|User
     */
    public static function getStatus(User $user = null)
    {
        $params = [];
        if (isset($user) && !empty($user->getUserId())) {
            $params = ['userId' => $user->getUserId()];
        } elseif (isset($user) && !empty($user->getUsername())) {
            $params = ['username' => $user->getUsername()];
        }

        static::send("users.getStatus", "GET", $params);
        if (!static::getSuccess()) {
            return false;
        }

        $response = static::getResponse();
        if (isset($user)) {
            $user->updateOutOfResponse($response);
        } else {
            $user = static::createOutOfResponse($response);
        }

        return $user;
    }

    /**
     * Sets a user Status when the status message and state is given
     *
     * @todo should status be validated against inclusion in ['online', 'away', 'busy', 'offline']
     *
     * @param string $message
     * @param string|null $status
     * @return bool
     */
    public static function setStatus($message, $status = null)
    {
        $params = ['message' => $message];
        if (isset($status) && is_string($status)) {
            $params['status'] = $status;
        }
        static::send("users.setStatus", "POST", $params);
        return static::getSuccess();
    }

    /**
     * Gets a suggestion a new username to user
     *
     * @return string|false
     */
    public static function getUsernameSuggestion()
    {
        static::send("users.getUsernameSuggestion", "GET");
        if (!static::getSuccess()) {
            return false;
        }

        return static::getResponse()->result;
    }
}
