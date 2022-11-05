<?php
require_once 'Sudoku.php';

$p_array = [
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
$sudoku = new Sudoku();
$sudoku->setProblem($p_array);
$sudoku->solveProblem();

$p_array2 = [
    [5, 0, 0, 0, 0, 6, 0, 4, 0],
    [2, 0, 3, 4, 9, 0, 0, 0, 5],
    [0, 0, 0, 0, 8, 1, 3, 0, 9],
    [1, 0, 4, 0, 6, 0, 7, 0, 2],
    [0, 7, 6, 0, 5, 2, 0, 9, 0],
    [8, 0, 9, 0, 4, 0, 0, 1, 6],
    [0, 0, 0, 0, 7, 0, 2, 0, 0],
    [7, 3, 0, 0, 2, 8, 0, 5, 4],
    [4, 0, 0, 9, 1, 0, 6, 0, 0],
];
$sudoku2 = new Sudoku();
$sudoku2->setProblem($p_array2);
$sudoku2->solveProblem();

// incorrect problem
$p_array3 = [
    [0, 0, 8, 0, 0, 1, 0, 3, 0],
    [0, 4, 0, 0, 3, 0, 1, 0, 5],
    [9, 0, 0, 0, 0, 8, 0, 4, 0],
    [0, 3, 0, 8, 0, 0, 5, 0, 2],
    [1, 0, 0, 0, 7, 0, 0, 6, 0],
    [0, 0, 5, 0, 2, 9, 8, 0, 4],
    [0, 7, 0, 5, 0, 4, 0, 2, 1],
    [3, 1, 1, 2, 0, 7, 4, 0, 0],
    [0, 5, 0, 0, 0, 0, 6, 9, 7],
];
$sudoku3 = new Sudoku();
$sudoku3->setProblem($p_array3);
$sudoku3->solveProblem();

$sudoku4 = new Sudoku();

echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Sudoku.php</title>
    <style>
    body {
    width: auto;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    }
    pre {
    width: auto;
    text-align: center;
    border: 5px dotted black;
    font-size: 18px;
    }
    </style>
    </head>
    <body>
    <pre>
_END;
$sudoku->displayProblem();
echo "</pre><pre>";
$sudoku2->displayProblem();
echo "</pre><pre>";
$sudoku3->displayProblem();
echo "</pre><pre>";
$sudoku4->displayProblem();
echo <<<_END
    </pre>
    </body>
    </html>
    _END;
