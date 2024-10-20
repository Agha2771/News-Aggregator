<?php namespace News\Enums;

class Strategy {
	const MOMENTUM  = 'momentum';
	const EQUAL_WEIGHT = 'equal weight';
	const TREND_FOLLOWING = 'trend following';
	const RISK_PARITY = 'risk parity';
	const VOLATILITY_TARGET_FILTER = 'volatility targeting';

	const EQUAL_WEIGHT_ID = 1;
	const TREND_FOLLOWING_ID = 2;
	const RISK_PARITY_ID = 3;
	const MOMENTUM_ID = 4;
	const VOLATILITY_TARGET_FILTER_ID = 5;
}
