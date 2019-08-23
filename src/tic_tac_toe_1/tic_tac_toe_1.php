<?php


// solution for the task https://www.spoj.com/problems/TOE1/
//TODO: Yields "wrong result".


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
		
		/*fwrite($output, $answer);
		if ($i != $testCaseCount - 1)
		{
			fwrite($output, "\n");
		}*/
	}
}

/**
 * @return Answer for current test case.
 */
function solveNextTestCase($input)
{
	assert(is_resource($input), '$input must be resource, but is: '. dbg($input));
	
	
	static $cache = [];
	
	$grid = '';
	$countX = $countO = 0;
	$safetyCounter = 0;
	do
	{
		$c = fgetc($input);
		if ($c != "\r" && $c != "\n")
		{
			$grid .= $c;
			
			if ($c == 'X')
			{
				++$countX;
			}
			elseif ($c == 'O')
			{
				++$countO;
			}
		}
		
		++$safetyCounter;
		if ($safetyCounter > 20)
		{
			//break;
		}
	}
	while (strlen($grid) != 9);
	
	//echo dbg(['$grid' => $grid]) ."\n";
	
	if (isset($cache[$grid]))
	{
		$answer = $cache[$grid];
	}
	else
	{
		if (
			($countX == $countO || $countX == $countO + 1)
			&& !hasMultipleWinningLines($grid)
		)
		{
			$answer = 'yes';
		}
		else
		{
			$answer = 'no';
		}
		
		$cache[$grid] = $answer;
	}
	
	//fgets($input);
	
	return $answer;
}

function hasMultipleWinningLines(string $grid)
{
	$lines = [
		[0, 1, 2],
		[3, 4, 5],
		[6, 7, 8],
		[0, 3, 6],
		[1, 4, 7],
		[2, 5, 8],
		[0, 4, 8],
		[2, 4, 6],
	];
	
	$count = 0;
	foreach ($lines as $line)
	{
		if (($grid[$line[0]] == 'X' || $grid[$line[0]] == 'O') && $grid[$line[0]] == $grid[$line[1]] && $grid[$line[2]] == $grid[$line[1]])
		{
			$count++;
		}
		if ($count >= 2)
		{
			return true;
		}
	}
	
	return false;
}

function dbg($what)
{
	return var_export($what, true);
}


main();
