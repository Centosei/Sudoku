<?php
require_once 'Sudoku.php';

$sudoku = new Sudoku();
if (!empty($_POST))
{
    $sudoku->importProblem($_POST);
}
$sudoku->solveProblem();
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
