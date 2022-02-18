<?php


require 'dbconnection.php';
require 'helper.php';

// to fetch data from another table that related forign key
$sql='select * from tasks';
$tasks_op=mysqli_query($con,$sql);


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $name     = Clean($_POST['name']);
    $password = Clean($_POST['password'], 1);
    $email    = Clean($_POST['email']);
    $id_tasks = Clean($_POST['id_tasks']);


    # Validate ...... 

    $errors = [];

    # validate name .... 
    if (empty($name)) {
        $errors['name'] = "Field Required";
    }


    # validate email 
    if (empty($email)) {
        $errors['email'] = "Field Required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['Email']   = "Invalid Email";
    }


    # validate password 
    if (empty($password)) {
        $errors['password'] = "Field Required";
    } elseif (strlen($password) < 6) {
        $errors['Password'] = "Length Must be >= 6 chars";
    }

    # validate id_tasks 
    if (empty($id_tasks)) {
        $errors['ERROR id_tasks'] = "Field Required";
    } elseif (!filter_var($id_tasks,FILTER_VALIDATE_INT)) {
        $errors['ERROR id_tasks'] = "in valid tasks id";
    }


   

    # Check ...... 
    if (count($errors) > 0) {
        
        foreach ($errors as $key => $value) {
            
         echo '* ' . $key . ' => ' . $value . '<br>';
        }
    } else {

        # Hash Password .... 
       $password = md5($password);

        $sql = "insert into user (name,email,password,id_tasks) values ('$name','$email','$password',$id_tasks)";

        $op  =  mysqli_query($con,$sql);

        mysqli_close($con);

        if($op){
            echo 'Raw Inserted';
        }else{
            echo 'Error Try Again '.mysqli_error($con);
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

        <form action="<?php echo  htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="exampleInputName">Name</label>
                <input type="text" class="form-control" required id="exampleInputName" aria-describedby="" name="name" placeholder="Enter Name">
            </div>



            <div class="form-group">
                <label for="exampleInputEmail">Email </label>
                <input type="email" class="form-control" required id="exampleInputEmail1" aria-describedby="" name="email" placeholder="Enter email">
            </div>

            <div class="form-group">
                <label for="exampleInputPassword">Password</label>
                <input type="password" class="form-control" required id="exampleInputPassword1" aria-describedby="" name="password" placeholder="Password">
            </div>

            <div class="form-group">
                <label for="exampleInputTasks">Tasks</label>
                <select class="form-control" name="id_tasks" value='select'>
                   <?php while($tasks_data=mysqli_fetch_assoc($tasks_op)){?>

                    <option value="<?php echo $tasks_data['id'];?>">
                    <?php echo $tasks_data['title'];?>
                    </option>

                   <?php }?>
                </select>
            </div>



            <button type="submit" class="btn btn-primary">submit</button>
        </form>
    </div>


</body>

</html>