<?php

namespace Correttore\Util;

class Utility{
    
    public static function BeansToArrays($beans)
    {
        foreach($beans as $bean)
			$items[] = $bean->export();
		return $items;
    }
}