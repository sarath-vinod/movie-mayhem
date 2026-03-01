<?php
require "data.php";
require "functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $success = deleteMovie($_POST['id']);

  if (!$success) {
    header("Location: movie.php?id=" . $_POST['id']);
    exit();
  }
}

header("Location: index.php");
