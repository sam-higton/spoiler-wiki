<?php

namespace SpoilerWiki;

use SpoilerWiki\Base\User as BaseUser;

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
}
