<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Title - Active List</title>
    <link rel="stylesheet" href="../../../resource/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../resource/bootstrap/css/bootstrap-theme.min.css">
    <script src="../../../resource/bootstrap/js/bootstrap.min.js"></script>
    <style>
        td{
            border: 0px;
        }
        table{
            border: 1px;
        }
        tr{
            height: 30px;
        }
    </style>
</head>
<body>

<?php
require_once("../../../vendor/autoload.php");

$objBookTitle = new \App\BookTitle\BookTitle();

if(isset($_GET['id'])) $objBookTitle->setData($_GET);
$id = $_GET['id'];

echo "Are You Sure?";
echo "
 <a href='delete.php?Yes=1&id=$id' class='btn btn-info'>Yes</a>
 <a href='index.php' class='btn btn-info'>No</a>
";

if(isset($_GET['Yes']) && $_GET['Yes']){
    $objBookTitle->delete();
}
?>

</body>
</html>
