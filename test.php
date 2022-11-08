<?php
require_once 'Sudoku.php';

$sudoku = new Sudoku();
$sudoku->generate();
$sudoku->displayProblem();
