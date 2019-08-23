<?php


// solution for the task https://www.spoj.com/problems/CHOCOLA/


define('MAX_POSSIBLE_COST', 1000);		// max possible cost of a single break of one part


function main()
{
	$input = null;
	$output = null;
	try
	{
		$input = fopen('php://stdin', 'rb');
		$output = fopen('php://stdout', 'wb');
		solveTask($input, $output);
	}
	finally
	{
		foreach ([$input, $output] as $resource)
		{
			if ($resource !== null && $resource !== false)
			{
				fclose($resource);
			}
		}
	}
}

function solveTask($input, $output)
{
	assert(is_resource($input), '$input must be resource, but is: '. dbg($input));
	assert(is_resource($output), '$output must be resource, but is: '. dbg($output));
	
		
	fscanf($input, '%d', $testCaseCount);
	//echo dbg(['$testCaseCount' => $testCaseCount]) ."\n";
	
	for ($i = 0; $i < $testCaseCount; ++$i)
	{
		$answer = solveNextTestCase($input);
		fwrite($output, $answer ."\n");
	}
}

/**
 * @return Answer for current test case.
 */
function solveNextTestCase($input)
{
	assert(is_resource($input), '$input must be resource, but is: '. dbg($input));
	
	
	// We need to sort the costs and first break along the lines with higher costs,
	// because otherwise, if plate is broken along horizontal line A with lower cost,
	// and then along vertical line B with higher cost,
	// then there will be by one part more to break vertically along line B with higher
	// cost and by one part less to break horizontally along line A with lower cost,
	// so the overall cost will be higher.
	
		
	// variables are named as in the task description
	
	fgets($input);
	fscanf($input, '%d %d', $m, $n);
	//echo dbg(['$m' => $m, '$n' => $n]) ."\n";
	
	//histograms of costs x[1]..x[m - 1] and y[1]..y[n - 1]
	$xHistogram = [];
	$yHistogram = [];
	for ($i = 1; $i <= MAX_POSSIBLE_COST; ++$i)
	{
		$xHistogram[$i] = 0;
		$yHistogram[$i] = 0;
	}
	
	for ($i = 0; $i < $m - 1; ++$i)
	{
		fscanf($input, '%d', $costXi);
		//echo dbg(['$costXi' => $costXi]) ."\n";
		$xHistogram[$costXi]++;
	}
	
	for ($i = 0; $i < $n - 1; ++$i)
	{
		fscanf($input, '%d', $costYi);
		//echo dbg(['$costYi' => $costYi]) ."\n";
		$yHistogram[$costYi]++;
	}
	
	$totalCost = 0;
	$xPartCount = 1;		// number of parts along horizontal (x) axis ([number of vertical breaks] + 1)
	$yPartCount = 1;		// number of parts along vertical (y) axis ([number of horizontal breaks] + 1)
	for ($i = MAX_POSSIBLE_COST; $i >= 1; $i--)
	{
		// Breaking the plate along all vertical and horizontal lines with
		// the next highest cost per part ($i). It does not matter in which order to break -
		// first vertical, then horizontal, or vice-versa, or mixed,
		// because each horizontal break adds 1 x [current cost per part] to each successive
		// vertical break and vice-versa, so it does not matter whether to
		// break horizontally with cost = [current cost per part] x [$xPartCount] and
		// then break vertically with cost = [current cost per part] x [$yPartCount + 1],
		// or to first break vertically with cost = [current cost per part] x [$yPartCount] and
		// then break horizontally with cost = [current cost per part] x [$xPartCount + 1].
		
		$totalCost += $i * ($xPartCount * $yHistogram[$i] + $yPartCount * $xHistogram[$i] + $xHistogram[$i] * $yHistogram[$i]);
		$xPartCount += $xHistogram[$i];
		$yPartCount += $yHistogram[$i];
	}
	
	return $totalCost;
}

function dbg($what)
{
	return var_export($what, true);
}


main();
