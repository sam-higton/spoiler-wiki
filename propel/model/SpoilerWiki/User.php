<?php

namespace SpoilerWiki;

use SpoilerWiki\Base\User as BaseUser;
use SpoilerWiki\Base\UserQuery;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser
{
    static function authenticate ($userName, $password) {
        $passwordCypher = md5($password);
        $user = UserQuery::create()->filterByUsername($userName)->findOne();

        if(!$user) {
            $user = UserQuery::create()->filterByEmail($userName)->findOne();
        }

        if($user) {
            if($user->getPassword() == $passwordCypher) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    static function userNameExists ($userName) {
        return (UserQuery::create()->filterByUsername($userName)->count() > 0);
    }

    static function emailExists ($email) {
        return (UserQuery::create()->filterByEmail($email)->count() > 0);
    }

    public function setPassword ($password) {
        $password = md5($password);
        return parent::setPassword($password);
    }
}
