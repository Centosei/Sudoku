<?php
require_once 'Sudoku.php';

$sudoku = new Sudoku();
if (!empty($_POST))
{
    $sudoku->importProblem($_POST);
    $sudoku->solveProblem();
}
else if (isset($_GET['gen']))
{
    $sudoku->generate();
    $sudoku->setProblem($sudoku->root_problem);
}
else
{
    $sudoku->solveProblem();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>template</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
  <div id="score">
<?php
$sudoku->displayResult();
?>
  </div>
    <div class="container">
      <form id="solve" action="/index.php" method="post">
<?php
$sudoku->displayForm();
?>
      </form>
      <div class="btns">
        <button  form="solve">Solve</button>
        <button  form="reset">RESET</button>
        <button  form="generate">GENERATE</button>
      </div>
      <form id="reset" action="/index.php" method="get"></form>
      <form id="generate" action="/index.php" method="get">
        <input name="gen" type="hidden" value="true">
      </form>
    </div>
  </body>
</html>
