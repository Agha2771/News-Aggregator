<?php namespace News\Enums;

class Boolean {
	const ACTIVE  = 1;
	const DISABLE = 0;

	const YES = 1;
	const NO = 0;

	const STATUS = [
	    self::YES => 'Yes',
        self::NO => 'No',
    ];
}
