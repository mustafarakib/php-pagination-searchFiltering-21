<?php
require_once("../../../vendor/autoload.php");

$objBookTitle = new \App\BookTitle\BookTitle();
$allData = $objBookTitle->trashed();

use App\Message\Message;
use App\Utility\Utility;

if(!isset($_SESSION)){
    session_start();
}
$msg = Message::getMessage();

######################## pagination code block#1 of 2 start ######################################
$recordCount= count($allData);

if(isset($_REQUEST['Page']))   $page = $_REQUEST['Page'];
else if(isset($_SESSION['Page']))   $page = $_SESSION['Page'];
else   $page = 1;
$_SESSION['Page']= $page;

if(isset($_REQUEST['ItemsPerPage']))   $itemsPerPage = $_REQUEST['ItemsPerPage'];
else if(isset($_SESSION['ItemsPerPage']))   $itemsPerPage = $_SESSION['ItemsPerPage'];
else   $itemsPerPage = 3;
$_SESSION['ItemsPerPage']= $itemsPerPage;

$pages = ceil($recordCount/$itemsPerPage);
$someData = $objBookTitle->trashedPaginator($page,$itemsPerPage);

$serial = (($page-1) * $itemsPerPage) +1;
####################### pagination code block#1 of 2 end #########################################
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Title - Trashed List</title>
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
<div class="container">
    <?php echo "<div style='height: 30px; text-align: center'> <div  class='bg-warning' id='message'> $msg </div> </div>"; ?>

    <div class="navbar">
        <td><a href='index.php' class='btn btn-group-lg btn-info'>Active-List</a> </td>
        <td><a href='create.php' class='btn btn-group-lg btn-info'>Add</a> </td>
    </div>

    <h1 style="text-align: center" ;">Book Title - Trashed List</h1>

    <table class="table table-striped table-bordered" cellspacing="0px">
        <tr>
            <th style='width: 10%; text-align: center'>Serial Number</th>
            <th style='width: 10%; text-align: center'>ID</th>
            <th>Book Name</th>
            <th>Author Name</th>
            <th>Action Buttons</th>
        </tr>

        <?php
        //$serial= 1;  ### We need to remove this to let pagination working
        foreach($someData as $oneData){  ### must  changed to someData instead of allData

            if($serial%2) $bgColor = "#cccccc";
            else $bgColor = "#ffffff";

            echo "
                  <tr  style='background-color: $bgColor'>
                     <td style='width: 10%; text-align: center'>$serial</td>
                     <td style='width: 10%; text-align: center'>$oneData->id</td>
                     <td>$oneData->book_name</td>
                     <td>$oneData->author_name</td>

                     <td>
                       <a href='view.php?id=$oneData->id' class='btn btn-info'>View</a>
                       <a href='recover.php?id=$oneData->id' class='btn btn-success'>Recover</a>
                       <a href='delete.php?id=$oneData->id' class='btn btn-danger'>Delete</a>
                     </td>
                  </tr>
              ";
            $serial++;
        }
        ?>
    </table>

<!--  ######################## pagination code block#2 of 2 start ###################################### -->
<div align="left" class="container">
    <ul class="pagination">

        <?php
        $pageMinusOne  = $page-1;
        $pagePlusOne  = $page+1;

        if($page>$pages) Utility::redirect("trashed.php?Page=$pages");

        if($page>1)  echo "<li><a href='trashed.php?Page=$pageMinusOne'>" . "Previous" . "</a></li>";
        for($i=1;$i<=$pages;$i++)
        {
            if($i==$page) echo '<li class="active"><a href="">'. $i . '</a></li>';
            else  echo "<li><a href='?Page=$i'>". $i . '</a></li>';

        }

        if($page<$pages) echo "<li><a href='trashed.php?Page=$pagePlusOne'>" . "Next" . "</a></li>";
        ?>

        <select  class="form-control"  name="ItemsPerPage" id="ItemsPerPage" onchange="javascript:location.href = this.value;" >
            <?php
            if($itemsPerPage==3 ) echo '<option value="?ItemsPerPage=3" selected >Show 3 Items Per Page</option>';
            else echo '<option  value="?ItemsPerPage=3">Show 3 Items Per Page</option>';

            if($itemsPerPage==4 )  echo '<option  value="?ItemsPerPage=4" selected >Show 4 Items Per Page</option>';
            else  echo '<option  value="?ItemsPerPage=4">Show 4 Items Per Page</option>';

            if($itemsPerPage==5 )  echo '<option  value="?ItemsPerPage=5" selected >Show 5 Items Per Page</option>';
            else echo '<option  value="?ItemsPerPage=5">Show 5 Items Per Page</option>';

            if($itemsPerPage==6 )  echo '<option  value="?ItemsPerPage=6"selected >Show 6 Items Per Page</option>';
            else echo '<option  value="?ItemsPerPage=6">Show 6 Items Per Page</option>';

            if($itemsPerPage==10 )   echo '<option  value="?ItemsPerPage=10"selected >Show 10 Items Per Page</option>';
            else echo '<option  value="?ItemsPerPage=10">Show 10 Items Per Page</option>';

            if($itemsPerPage==15 )  echo '<option  value="?ItemsPerPage=15"selected >Show 15 Items Per Page</option>';
            else    echo '<option  value="?ItemsPerPage=15">Show 15 Items Per Page</option>';
            ?>
        </select>
    </ul>
</div>
<!--  ######################## pagination code block#2 of 2 end ###################################### -->

</div>

<script src="../../../resource/bootstrap/js/jquery.js"></script>
<script>
    jQuery(function($) {
        $('#message').fadeOut (550);
        $('#message').fadeIn (550);
        $('#message').fadeOut (550);
        $('#message').fadeIn (550);
        $('#message').fadeOut (550);
        $('#message').fadeIn (550);
        $('#message').fadeOut (550);
    })
</script>

</body>
</html>
