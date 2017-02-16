<?php
namespace App\BookTitle;

use App\Message\Message;
use App\Model\Database as DB;
use App\Utility\Utility;
use PDO;

class BookTitle extends DB
{
    private $id;
    private $book_name;
    private $author_name;

    public function setData($allPostData=null){

        if(array_key_exists("id",$allPostData)){
            $this->id= $allPostData['id'];
        }

        if(array_key_exists("bookName",$allPostData)){
            $this->book_name= $allPostData['bookName'];
        }

        if(array_key_exists("authorName",$allPostData)){
            $this->author_name= $allPostData['authorName'];
        }
    }

    public function store(){
       $arrData  =  array($this->book_name,$this->author_name);

       $query= 'INSERT INTO book_title (book_name, author_name) VALUES (?,?)';

       $STH = $this->DBH->prepare($query);
       $result = $STH->execute($arrData);

       if($result){
           Message::setMessage("Success! Data has been stored successfully!");
       }
       else{
           Message::setMessage("Failed! Data has not been stored!");
       }

        Utility::redirect('index.php');
    }

    public function view(){
        $sql = "Select * from book_title where id=".$this->id;
        $STH = $this->DBH->query($sql);
        $STH->setFetchMode(PDO::FETCH_OBJ);
        return $STH->fetch();
    }

    public function index(){
        $sql = "Select * from book_title where soft_deleted='No'";
        $STH = $this->DBH->query($sql);
        $STH->setFetchMode(PDO::FETCH_OBJ);
        return $STH->fetchAll();
    }

    public function trashed(){
        $sql = "Select * from book_title where soft_deleted='Yes'";
        $STH = $this->DBH->query($sql);
        $STH->setFetchMode(PDO::FETCH_OBJ);
        return $STH->fetchAll();
    }

    public function update(){
        $arrData  =  array($this->book_name,$this->author_name);
        $query= "UPDATE book_title SET book_name  = ?, author_name= ?  WHERE id=".$this->id;

        $STH = $this->DBH->prepare($query);
        $result = $STH->execute($arrData);

        if($result){
            Message::setMessage("Success! Data has been updated successfully!");
        }
        else{
            Message::setMessage("Failed! Data has not been update!");
        }

        Utility::redirect('index.php');
    }

    public function trash(){
        $arrData  =  array("Yes");
        $query= "UPDATE book_title SET soft_deleted  = ? WHERE id=".$this->id;

        $STH = $this->DBH->prepare($query);
        $result = $STH->execute($arrData);

        if($result){
            Message::setMessage("Success! Data has been Trashed successfully!");
        }
        else{
            Message::setMessage("Failed! Data has not been Trashed!");
        }

        Utility::redirect('trashed.php');
    }

    public function recover(){
        $arrData  =  array("No");
        $query= "UPDATE book_title SET soft_deleted  = ? WHERE id=".$this->id;

        $STH = $this->DBH->prepare($query);
        $result = $STH->execute($arrData);

        if($result){
            Message::setMessage("Success! Data has been Recovered successfully!");
        }
        else{
            Message::setMessage("Failed! Data has not been Recovered!");
        }

        Utility::redirect('index.php');
    }

    public function delete(){
        $sql = "DELETE from book_title where id=".$this->id;
        $result = $this->DBH->exec($sql);

        if($result){
            Message::setMessage("Success! Data has been Deleted successfully!");
        }
        else{
            Message::setMessage("Failed! Data has not been Deleted!");
        }

        Utility::redirect('index.php');
    }

    public function indexPaginator($page=1,$itemsPerPage=3){
        $start = (($page-1) * $itemsPerPage);
        $sql = "SELECT * from book_title  WHERE soft_deleted = 'No' LIMIT $start,$itemsPerPage";

        $STH = $this->DBH->query($sql);
        $STH->setFetchMode(PDO::FETCH_OBJ);
        $arrSomeData  = $STH->fetchAll();
        return $arrSomeData;
    }

    public function trashedPaginator($page=1,$itemsPerPage=3){
        $start = (($page-1) * $itemsPerPage);
        $sql = "SELECT * from book_title  WHERE soft_deleted = 'Yes' LIMIT $start,$itemsPerPage";

        $STH = $this->DBH->query($sql);
        $STH->setFetchMode(PDO::FETCH_OBJ);
        $arrSomeData  = $STH->fetchAll();
        return $arrSomeData;
    }

    public function search($requestArray){
        $sql = "";

        if( isset($requestArray['byTitle']) && isset($requestArray['byAuthor']) )
            $sql = "SELECT * FROM `book_title` WHERE `soft_deleted` ='No' AND (`book_name` LIKE '%".
                $requestArray['search']."%' OR `author_name` LIKE '%".$requestArray['search']."%')";

        if(isset($requestArray['byTitle']) && !isset($requestArray['byAuthor']) )
            $sql = "SELECT * FROM `book_title` WHERE `soft_deleted` ='No' AND `book_name` LIKE '%".
                $requestArray['search']."%'";

        if(!isset($requestArray['byTitle']) && isset($requestArray['byAuthor']) )
            $sql = "SELECT * FROM `book_title` WHERE `soft_deleted` ='No' AND `author_name` LIKE '%".
                $requestArray['search']."%'";

        $STH  = $this->DBH->query($sql);
        $STH->setFetchMode(PDO::FETCH_OBJ);
        $someData = $STH->fetchAll();
        return $someData;
    }

    public function getAllKeywords(){
        $_allKeywords = array();
        $WordsArr = array();

        // for each search field block start
        $allData = $this->index();

        foreach ($allData as $oneData) {
            $_allKeywords[] = trim($oneData->book_name);
        }
        $allData = $this->index();

        foreach ($allData as $oneData) {
            $eachString= strip_tags($oneData->book_name);
            $eachString=trim( $eachString);
            $eachString= preg_replace( "/\r|\n/", " ", $eachString);
            $eachString= str_replace("&nbsp;","",  $eachString);
            $WordsArr = explode(" ", $eachString);

            foreach ($WordsArr as $eachWord){
                $_allKeywords[] = trim($eachWord);
            }
        }
        // for each search field block end


        // for each search field block start
        $allData = $this->index();

        foreach ($allData as $oneData) {
            $_allKeywords[] = trim($oneData->author_name);
        }
        $allData = $this->index();

        foreach ($allData as $oneData) {
            $eachString= strip_tags($oneData->author_name);
            $eachString=trim( $eachString);
            $eachString= preg_replace( "/\r|\n/", " ", $eachString);
            $eachString= str_replace("&nbsp;","",  $eachString);
            $WordsArr = explode(" ", $eachString);

            foreach ($WordsArr as $eachWord){
                $_allKeywords[] = trim($eachWord);
            }
        }
        // for each search field block end

        return array_unique($_allKeywords);
    }// get all keywords end
}
