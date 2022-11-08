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

    private $exec_time = 0;
    private $attempt = 0;
    private $init_array = array();
    private $last_array = array();
    private $num_array = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    
    public function setProblem($p_array)
    {
        $this->problem = $p_array;
    }

    public function importProblem($single_array)
    {
        $this->export = array();
        for ( $r = 0 ; $r < 9 ; ++$r )
        {
            $this->row = array();
            for ( $c = 0 ; $c < 9 ; ++$c )
            {
                $this->keyRC = strval($r) . strval($c);
                $this->val = $single_array[$this->keyRC];
                if ($this->val === '')
                {
                    $this->row[] = 0;
                }
                else
                {
                    $this->row[] = intval($this->val);
                }
            }
            $this->export[] = $this->row;
        }
        $this->setProblem($this->export);
    }

    public function displayResult()
    {
        echo "<div>Attempt: " . strval($this->attempt) .  "</div>";
        echo "<div>Result: " . strval($this->solved) . "</div>";
        echo "<div>Time: " . strval(number_format($this->exec_time, 4)) . "s.</div>";
    }

    public function displayForm()
    {
        for ( $r = 0 ; $r < 9 ; ++$r )
        {
            for ( $c = 0 ; $c < 9 ; ++$c )
            {
                if ($this->problem[$r][$c] === 0) $this->temp = '';
                else $this->temp = intval($this->problem[$r][$c]);
                echo "<div><input type='number' min='1' max='9' name='"
                    . strval($r) . strval($c) . "' value='" . $this->temp . "'></div>";
            }
        }
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
                if ($r === 2 ) echo str_repeat(" ", 4) . $this->is_done(true) . str_repeat(" ", 16 - strlen($this->is_done(true)));
                else if ($r === 3) echo str_repeat(" ", 4) . "In " . number_format($this->exec_time, 4) . "sec" . str_repeat(" ", 4);
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

    public function solveProblem()
    {
        $start_time = microtime(true);
        $this->init_array = $this->problem;
        $this->attempt = 0;
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

            // check if there's only one number possible for a square
            $this->checkPossible();

            // check if stuck
            $this->stuck = ($this->last_array === $this->problem);
            if ($this->stuck) break;

            // save the last result of the attempt
            $this->last_array = $this->problem;
        }
        $end_time = microtime(true);
        $this->exec_time = $end_time - $start_time;
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

    private function checkPossible()
    {
        for ( $r = 0 ; $r < 9 ; ++$r)
        {
            for ( $c = 0 ; $c < 9 ; ++$c )
            {
                if ($this->problem[$r][$c] === 0)
                {
                    $rs = intval($r / 3) * 3;
                    $cs = intval($c / 3) * 3;
                    $bucket = array_merge($this->problem[$r], array_column($this->problem, $c));
                    for ( $x = 0 ; $x < 3 ; ++$x )
                    {
                        for ( $y = 0 ; $y < 3 ; ++$y )
                        {
                            array_push($bucket, $this->problem[$rs+$x][$cs+$y]);
                        }
                    }
                    $bucket = array_unique($bucket);
                    $bucket = array_values(array_diff($this->num_array, $bucket));
                    if (count($bucket) === 1)
                        $this->problem[$r][$c] = $bucket[0];
                }
            }
        }
    }

    // problem generation

    public $root_problem = [
        [8, 3, 5, 4, 1, 6, 9, 2, 7],
        [2, 9, 6, 8, 5, 7, 4, 3, 1],
        [4, 1, 7, 2, 9, 3, 6, 5, 8],
        [5, 6, 9, 1, 3, 4, 7, 8, 2],
        [1, 2, 3, 6, 7, 8, 5, 4, 9],
        [7, 4, 8, 5, 2, 9, 1, 6, 3],
        [6, 5, 2, 7, 8, 1, 3, 9, 4],
        [9, 8, 1, 3, 4, 5, 2, 7, 6],
        [3, 7, 4, 9, 6, 2, 8, 1, 5],
    ];

    public $mixed_array = array();

    public function generate()
    {
        $this->mixProblem(10);
        $this->mixed_array = $this->root_problem;
        $this->popProblem();
        $this->setProblem($this->root_problem);
        $this->solveProblem();
        if (!$this->solved || ($this->attempt < 50))
        {
            $this->stuck = false;
            $this->solved = false;
            $this->root_problem = $this->mixed_array;
            $this->generate();
        }
    }

    public function swapSqr($square, $num1, $num2)
    {
        //
        $or = intval($square / 3) * 3;
        $oc = ($square % 3) * 3;
        for ( $i = 0 ; $i < 9 ; ++$i )
        {
            $sr = $or + intval($i / 3);
            $sc = $oc + $i % 3;
            if ($this->root_problem[$sr][$sc] === $num1)
            {
                $r1 = $sr;
                $c1 = $sc;
            }
            else if ($this->root_problem[$sr][$sc] === $num2)
            {
                $r2 = $sr;
                $c2 = $sc;
            }
        }
        $this->root_problem[$r1][$c1] = $num2;
        $this->root_problem[$r2][$c2] = $num1;
    }

    public function swapFlow()
    {
        $rand_keys = array_rand($this->num_array, 2);
        for ( $i = 0 ; $i < 9 ; ++$i )
        {
            $this->swapSqr($i, $this->num_array[$rand_keys[0]], $this->num_array[$rand_keys[1]]);
        }
    }

    public function mixProblem($times)
    {
        for ( $t = 0 ; $t < $times ; ++$t )
            $this->swapFlow();
    }

    public function popNum($square, $num)
    {
        //
        $or = intval($square / 3) * 3;
        $oc = ($square % 3) * 3;
        for ( $i = 0 ; $i < 9 ; ++$i )
        {
            $sr = $or + intval($i / 3);
            $sc = $oc + $i % 3;
            if ($this->root_problem[$sr][$sc] === $num)
            {
                $this->root_problem[$sr][$sc] = 0;
                break;
            }
        }
    }

    public function popProblem()
    {
        for ( $n = 1 ; $n < 10 ; ++$n )
        {
            $s_array = [0, 1, 2, 3, 4, 5, 6, 7, 8];
            shuffle($s_array);
            for ( $f = 0 ; $f < rand(5, 7) ; ++$f )
            {
                $sq = array_pop($s_array);
                $this->popNum($sq, $n);
            }
        }
    }
}
