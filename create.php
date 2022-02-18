<?php


require 'dbconnection.php';
require 'helper.php';



if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $title     = Clean($_POST['title']);
    $content   = Clean($_POST['content']);
    $startdate = $_POST['startdate'];
    $enddate   = $_POST['enddate'];


$errors=[];
#validate >>title 
if(empty($title)){
    $errors['errortitle']='required title';
}elseif(strlen($title)<5){
    $errors['errortitle']='title must < 5 letter';
}

#validate >> content
if(empty($content))
{
    $errors['errorcontent']='required content';
}elseif(strlen($content)<50){
    $errors['errorcontent']='length must be >50 character';  

}


#validate >>stat. date
if(empty($startdate)){
    $errors['errorstardate'] = "Field Required"; 

}else{

    if(strtotime($startdate)>strtotime($enddate)){
        $errors['errorstardate'] = "invalid date"; 
    }else{
        $arr_startdate=explode('-',$startdate);
       // print_r($arr_startdate);
        //exit();
    if(checkdate($arr_startdate[1],$arr_startdate[2],$arr_startdate[0])==1){
        //echo checkdate($arr_startdate[1],$arr_startdate[2],$arr_startdate[0]).'<br>';
       // echo 'vaild date'.'<br>';
    }else{
            $errors['errorstardate'] = "invalid date arrangement"; 
         }  
}
}
#validate >>end . date
if(empty($enddate)){
    $errors['errorenddate'] = "Field Required"; 

}else{
    if(strtotime($startdate)>strtotime($enddate)){
        $errors['errorstardate'] = "invalid date"; 
    }else{
        $arr_enddate=explode('-',$enddate);
        //print_r($arr_enddate);
        if(checkdate($arr_enddate[1],$arr_enddate[2],$arr_enddate[0])){
           // echo 'vaild date'.'<br>';
        }else{
            $errors['errorstardate'] = "invalid date arrangement"; 
        } 
   
}
}
#image

    # Validate Image ..... 
    if (empty($_FILES['image']['name'])) {
       
        $errors['errorimage']   = "Field Required";
   
   }else{

       $imgName  = $_FILES['image']['name'];
       $imgTemp  = $_FILES['image']['tmp_name'];
       $imgType  = $_FILES['image']['type'];  

       $nameArray =  explode('.', $imgName);
       $imgExtension =  strtolower(end($nameArray));
       $imgFinalName = time() . rand() . '.' . $imgExtension;
       $allowedExt = ['png', 'jpg'];

       if (!in_array($imgExtension, $allowedExt)) {
           $errors['errorimage']   = "Not Allowed Extension";
       }

   }


//////////////////////////
#check
if(count($errors)>0){
    foreach($errors as $key=>$value){
        echo $key.' = '.$value .'<br>';
    }

}else{
   
 //db=todo , table=tasks


 $disPath = 'uploads/' . $imgFinalName;

        if (move_uploaded_file($imgTemp, $disPath)) {

    
        $sql = "insert into tasks (title,content,image,startdate,enddate) values ('$title','$content','$imgFinalName','$startdate','$enddate')";

        $op  =  mysqli_query($con,$sql);

        mysqli_close($con);

        if($op){
            echo 'Raw Inserted';
        }else{
            echo 'Error Try Again => '.mysqli_error($con);
        }
    }else{
        echo 'Error  in upload image Try Again... ';
    }

    }
}

   
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Register</h2>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" enctype="multipart/form-data" >

            <div class="form-group">
                <label for="exampleInputTitle">title</label>
                <input type="text" class="form-control" id="exampleInputTitle"   required aria-describedby=""   name="title" placeholder="Enter Title">
            </div>


            <div class="form-group">
                <label for="exampleInputContent">content </label>
                <input type="text" class="form-control" id="exampleInputContent"  required aria-describedby="" name="content" placeholder="Enter content">
            </div>


            <div class="form-group">
                <label for="exampleInputSdate">startdate</label>
                <input type="date" class="form-control" id="exampleInputSdate"  required aria-describedby="" name="startdate" placeholder="Enter  start date">
            </div>


            <div class="form-group">
                <label for="exampleInputEdate">enddate</label>
                <input type="date" class="form-control" id="exampleInputEdate"  required aria-describedby="" name="enddate" placeholder="Enter  end date">
            </div>

            <div class="form-group">
                <label for="exampleInputImage">Image</label>
                <input type="file" name="image">
            </div>

            <button type="submit" class="btn btn-primary">submit</button>
        </form>
    </div>


    <br>

</body>

</html>  
 

    