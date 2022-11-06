<?php
require_once 'Sudoku.php';

$sudoku = new Sudoku();

/* var_dump($sudoku->is_done(true)); */
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
/* var_dump($_POST); */
/* print_r($sudoku->problem); */
if (!empty($_POST))
{
    $sudoku->importProblem($_POST);
    /* echo "<pre>"; */
    /* var_dump($sudoku->problem); */
    /* echo "</pre>"; */
}
/* else */
/* { */
/*     $sudoku->setProblem($p_array); */
/* } */
/* print_r($sudoku->problem); */
$sudoku->solveProblem();
/* var_dump($sudoku->is_done(true)); */
/* $sudoku->displayProblem(); */
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>template</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="container">
      <form action="/index.php" method="post">
<?php
$sudoku->displayForm();
?>
        <button>Solve</button>
      </form>
      <form action="/index.php" method="get"><button>RESET</button></form>
    </div>
  </body>
</html>
