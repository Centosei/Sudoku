<?php
class Sudoku
{
    private $problem = [
        [0, 0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0, 0],
    ];

    public $solved = false;
    public $stuck = false;

    private $attempt = 0;
    private $init_array = array();
    private $last_array = array();

    public function setProblem($p_array)
    {
        $this->problem = $p_array;
    }

    public function is_done(bool $str_result = false)
    {
        if ($str_result)
        {
            if ($this->solved) return "It's solved!";
            else if ($this->stuck) return "I'm stuck:(";
            else return "It's not solved yet!";
        }
        else
        {
            if ($this->solved) return true;
            else if ($this->stuck) return true;
            else return false;
        }
    }

    public function displayProblem()
    {
        if (!count($this->init_array))
        {
            echo "+" . str_repeat("-", 19) . "+";
            echo "\n";
            for ( $r = 0 ; $r < 9 ; ++$r )
            {
                echo "|";
                for ( $c = 0 ; $c < 9 ; ++$c )
                {
                    echo " ";
                    echo $this->problem[$r][$c];
                }
                echo " |";
                echo "\n";
            }
            echo "+" . str_repeat("-", 19) . "+";
            echo "\n";
            echo " " . $this->is_done(true) . "\n";
        }
        else
        {
            echo "+" . str_repeat("-", 19) . "+" . str_repeat(" ", 20) . "+" . str_repeat("-", 19) . "+";
            echo "\n";
            for ( $r = 0 ; $r < 9 ; ++$r )
            {
                echo "|";
                for ( $c = 0 ; $c < 9 ; ++$c )
                {
                    echo " ";
                    echo $this->init_array[$r][$c];
                }
                echo " |";
                if ($r === 3 ) echo str_repeat(" ", 4) . $this->is_done(true) . str_repeat(" ", 16 - strlen($this->is_done(true)));
                else if ($r === 4) echo " " . str_repeat("-", 17) . "> ";
                else if ($r === 5) echo str_repeat(" ", 4) . "# of Attempt:" . str_repeat(" ", 3);
                else if ($r === 6) echo str_repeat(" ", 11 - strlen($this->attempt)) . $this->attempt . str_repeat(" ", 9);
                else echo str_repeat(" ", 20);

                echo "|";
                for ( $c = 0 ; $c < 9 ; ++$c )
                {
                    echo " ";
                    echo $this->problem[$r][$c];
                }
                echo " |";
                echo "\n";
            }
            echo "+" . str_repeat("-", 19) . "+" . str_repeat(" ", 20) . "+" . str_repeat("-", 19) . "+";
            echo "\n";
        }
    }

    // not yet
    public function solveProblem()
    {
        $this->init_array = $this->problem;
        while (!$this->stuck)
        {
            for ( $num = 1 ; $num < 10 ; ++$num )
            {
                $this->attempt++;

                // initial check
                $this->checkRow($num);
                $this->checkColumn($num);
                $this->checkSquare($num);

                // solve row
                $this->solveRow($num);
                $this->checkColumn($num);
                $this->checkSquare($num);

                // solve column
                $this->solveColumn($num);
                $this->checkRow($num);
                $this->checkSquare($num);

                // solve each block
                $this->solveSquare($num);
                $this->checkRow($num);
                $this->checkColumn($num);

                // clean up bools
                $this->cleanBool($num);

                // check if solved
                $this->checkSolved();
                if ($this->solved)
                {
                    break 2;
                }
            }

            // check if stuck
            $this->stuck = ($this->last_array === $this->problem);
            if ($this->stuck) break;

            // save the last result of the attempt
            $this->last_array = $this->problem;
        }
    }

    private function checkRow($num)
    {
        for ( $r = 0 ; $r < 9 ; ++$r )
        {
            if (in_array($num, $this->problem[$r], true))
            {
                for ( $c = 0 ; $c < 9 ; ++$c )
                {
                    if ($this->problem[$r][$c] === 0 )
                        $this->problem[$r][$c] = 'F'; 
                    else if ($this->problem[$r][$c] === 'T')
                        $this->problem[$r][$c] = 'F';
                }
            }
            else
            {
                for ( $c = 0 ; $c < 9 ; ++$c )
                {
                    if ($this->problem[$r][$c] === 0 )
                        $this->problem[$r][$c] = 'T'; 
                }
            }
        }
    }

    private function checkColumn($num)
    {
        for ( $c = 0 ; $c < 9 ; ++$c )
        {
            $column = array_column($this->problem, $c);
            if (in_array($num, $column, true))
            {
                for ( $r = 0 ; $r < 9 ; ++$r )
                {
                    if ($this->problem[$r][$c] === 'T')
                        $this->problem[$r][$c] = 'F';
                }
            }
        }
    }

    private function checkSquare($num)
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
                        $square[] = $this->problem[$r][$c];
                    }
                }
                if ( in_array($num, $square, true))
                {
                    for ( $r = $x * 3 ; $r < $x * 3 + 3 ; ++$r )
                    {
                        for ( $c = $y * 3 ; $c < $y * 3 + 3 ; ++$c )
                        {
                            if ($this->problem[$r][$c] === 'T')
                                $this->problem[$r][$c] = 'F'; 
                        }
                    }
                }
            }
        }
    }

    private function solveRow($num) 
    {
        for ( $r = 0 ; $r < 9 ; ++$r )
        {
            if ((array_key_exists('T', array_count_values($this->problem[$r]))) && (array_count_values($this->problem[$r])['T'] === 1))
            {
                $c = array_search('T', $this->problem[$r]);
                $this->problem[$r][$c] = $num;
            }
        }
    }

    private function solveColumn($num) 
    {
        for ($c = 0 ; $c < 9 ; ++$c )
        {
            $column = array_column($this->problem, $c);
            if ((array_key_exists('T', array_count_values($column))) && (array_count_values($column)['T'] === 1))
            {
                $r = array_search('T', $column);
                $this->problem[$r][$c] = $num;
            }
        }
    }


    private function solveSquare($num) 
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
                        $square[] = $this->problem[$r][$c];
                    }
                }
                if ((array_key_exists('T', array_count_values($square))) && (array_count_values($square)['T'] === 1))
                {
                    $s = array_search('T', $square);
                    $sr = intval($s / 3) + $x * 3;
                    $sc = $s % 3 + $y * 3;
                    $this->problem[$sr][$sc] = $num;
                }
            }
        }
    }

    private function cleanBool($num)
    {
        for ( $r = 0 ; $r < 9 ; ++$r)
        {
            for ( $c = 0 ; $c < 9 ; ++$c )
            {
                if (($this->problem[$r][$c] === 'T' || $this->problem[$r][$c] === 'F'))
                    $this->problem[$r][$c] = 0;
            }
        }
    }

    private function checkSolved()
    {
        $zero = 0;
        for ( $r = 0 ; $r < 9 ; ++$r)
        {
            for ( $c = 0 ; $c < 9 ; ++$c )
            {
                if ($this->problem[$r][$c] === 0)
                {
                    $zero++;
                }
            }
        }
        if ($zero > 0)
            $this->solved = false;
        else
            $this->solved = true;
    }
}
