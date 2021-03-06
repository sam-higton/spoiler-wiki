<?php

namespace SpoilerWiki;

use SpoilerWiki\Base\Canon as BaseCanon;

/**
 * Skeleton subclass for representing a row from the 'canon' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Canon extends BaseCanon
{
    static function fetchAll () {
        $canonList  = CanonQuery::create()->find();
        return $canonList;
    }
}
