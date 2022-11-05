<?php
$problem = [
    [2, 0, 9, 6, 0, 1, 8, 0, 0],
    [0, 1, 0, 0, 5, 0, 0, 4, 0],
    [5, 0, 4, 3, 0, 9, 1, 0, 0],
    [1, 0, 5, 0, 3, 0, 0, 0, 9],
    [8, 4, 0, 0, 0, 2, 0, 7, 0],
    [3, 0, 7, 1, 8, 0, 5, 2, 0],
    [7, 0, 2, 0, 1, 0, 4, 0, 6],
    [0, 6, 0, 9, 0, 3, 0, 5, 0],
    [9, 0, 8, 4, 0, 0, 2, 0, 3],
];

function displayProblem($problem)
{
    echo "+" . "---------" . "+";
    echo "---------" . "+";
    echo "\n";
    for ( $r = 0 ; $r < 9 ; ++$r )
    {
        echo "|";
        for ( $c = 0 ; $c < 9 ; ++$c )
        {
            echo " ";
            echo $problem[$r][$c];
        }
        echo " |";
        echo "\n";
    }
    echo "+" . "---------" . "+";
    echo "---------" . "+";
    echo "\n";
}

function checkRow($problem, $num)
{
    for ( $r = 0 ; $r < 9 ; ++$r )
    {
        if (in_array($num, $problem[$r], true))
        {
            for ( $c = 0 ; $c < 9 ; ++$c )
            {
                if ($problem[$r][$c] === 0 )
                    $problem[$r][$c] = 'F'; 
                else if ($problem[$r][$c] === 'T')
                    $problem[$r][$c] = 'F';
            }
        }
        else
        {
            for ( $c = 0 ; $c < 9 ; ++$c )
            {
                if ($problem[$r][$c] === 0 )
                    $problem[$r][$c] = 'T'; 
            }
        }
    }
    return $problem;
}

function checkColumn($problem, $num)
{
    for ( $c = 0 ; $c < 9 ; ++$c )
    {
        $column = array_column($problem, $c);
        if (in_array($num, $column, true))
        {
            for ( $r = 0 ; $r < 9 ; ++$r )
            {
                if ($problem[$r][$c] === 'T')
                    $problem[$r][$c] = 'F';
            }
        }
    }
    return $problem;
}

function checkSquare($problem, $num)
{
    for ( $x = 0 ; $x < 3 ; ++$x )
    {
        for ( $y = 0 ; $y < 3 ; ++$y )
        {
            $square = [];
            for ( $r = $x * 3 ; $r < $x * 3 + 3 ; ++$r )
            {
                for ( $c = $y * 3 ; $c < $y * 3 + 3 ; ++$c )
                {
                    $square[] = $problem[$r][$c];
                }
            }
            if ( in_array($num, $square, true))
            {
                for ( $r = $x * 3 ; $r < $x * 3 + 3 ; ++$r )
                {
                    for ( $c = $y * 3 ; $c < $y * 3 + 3 ; ++$c )
                    {
                        if ($problem[$r][$c] === 'T')
                            $problem[$r][$c] = 'F'; 
                    }
                }
            }
        }
    }
    return $problem;
}

function solveRow($problem, $num) 
{
    for ( $r = 0 ; $r < 9 ; ++$r )
    {
        if ((array_key_exists('T', array_count_values($problem[$r]))) && (array_count_values($problem[$r])['T'] === 1))
        {
            $c = array_search('T', $problem[$r]);
            $problem[$r][$c] = $num;
        }
    }
    return $problem;
}

function solveColumn($problem, $num) 
{
    for ($c = 0 ; $c < 9 ; ++$c )
    {
        $column = array_column($problem, $c);
        if ((array_key_exists('T', array_count_values($column))) && (array_count_values($column)['T'] === 1))
        {
            $r = array_search('T', $column);
            $problem[$r][$c] = $num;
        }
    }
    return $problem;
}


function solveSquare($problem, $num) 
{
    for ( $x = 0 ; $x < 3 ; ++$x )
    {
        for ( $y = 0 ; $y < 3 ; ++$y )
        {
            $square = array();
            for ( $r = $x * 3 ; $r < $x * 3 + 3 ; ++$r )
            {
                for ( $c = $y * 3 ; $c < $y * 3 + 3 ; ++$c )
                {
                    $square[] = $problem[$r][$c];
                }
            }
            if ((array_key_exists('T', array_count_values($square))) && (array_count_values($square)['T'] === 1))
            {
                $s = array_search('T', $square);
                $sr = intval($s / 3) + $x * 3;
                $sc = $s % 3 + $y * 3;
                $problem[$sr][$sc] = $num;
            }
        }
    }
    return $problem;
}

function cleanBool($problem, $num)
{
    for ( $r = 0 ; $r < 9 ; ++$r)
    {
        for ( $c = 0 ; $c < 9 ; ++$c )
        {
            if (($problem[$r][$c] === 'T' || $problem[$r][$c] === 'F'))
                $problem[$r][$c] = 0;
        }
    }
    return $problem;
}

function checkSolved($problem)
{
    $zero = 0;
    for ( $r = 0 ; $r < 9 ; ++$r)
    {
        for ( $c = 0 ; $c < 9 ; ++$c )
        {
            if ($problem[$r][$c] === 0)
            {
                $zero++;
            }
        }
    }
    if ($zero > 0)
        return false;
    else
        return true;
}


$attempt = 0;
$solved = false;
$stuck = false;
$last_array = array();
while (!$stuck)
{
    for ( $num = 1 ; $num < 10 ; ++$num )
    {
        $problem = checkRow($problem, $num);
        $problem = checkColumn($problem, $num);
        $problem = checkSquare($problem, $num);
        $problem = solveRow($problem, $num);
        $problem = checkColumn($problem, $num);
        $problem = checkSquare($problem, $num);
        $problem = solveColumn($problem, $num);
        $problem = checkRow($problem, $num);

        $problem = checkSquare($problem, $num);
        $problem = solveSquare($problem, $num);
        $problem = checkRow($problem, $num);
        $problem = solveColumn($problem, $num);
        $problem = cleanBool($problem, $num);
        $solved = checkSolved($problem);

        $attempt++;
        if ($solved)
        {
            echo "I solved!\n";
            break 2;
        }
    }
    $stuck = ($last_array === $problem);
    if ($stuck) echo "I'm stuck!\n";
    $last_array = $problem;
}


displayProblem($problem);
var_dump($attempt);




















