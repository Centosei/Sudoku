<?php
require_once 'Sudoku.php';

$sudoku = new Sudoku();

$tough_problem = [
    [0, 0, 7, 0, 6, 0, 0, 3, 0],
    [5, 0, 0, 0, 0, 2, 0, 4, 0],
    [0, 0, 4, 0, 1, 0, 0, 0, 0],
    [0, 0, 3, 0, 0, 0, 0, 0, 0],
    [1, 0, 0, 3, 0, 5, 0, 9, 8],
    [0, 0, 5, 0, 0, 0, 0, 0, 0],
    [0, 8, 0, 0, 9, 0, 1, 0, 0],
    [0, 4, 0, 0, 0, 0, 2, 0, 0],
    [2, 0, 0, 7, 0, 0, 0, 0, 6],
];

$sudoku->setProblem($tough_problem);
$sudoku->solveProblem();
$sudoku->displayProblem();
