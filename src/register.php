
<?php
    include("config.php");

    if($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $username = mysqli_real_escape_string($db,$_POST['username']);
        $password = mysqli_real_escape_string($db,$_POST['password']); 

        $confirm_password = mysqli_real_escape_string($db,$_POST['confirm_password']);
        $email = mysqli_real_escape_string($db,$_POST['email']); 

        $name = mysqli_real_escape_string($db,$_POST['name']);
        $age = mysqli_real_escape_string($db,$_POST['age']); 

        $city = mysqli_real_escape_string($db,$_POST['city']);
        $biography = mysqli_real_escape_string($db,$_POST['biography']);
        //$username = $password = $confirm_password = $email = $name = $age = $city = $biography = "";
        //$username_err = $password_err = $confirm_password_err = $email_err = $name_err = $age_err = $city_err = $biography_err = "";

        // Validate username
        if(empty(trim($_POST["username"])))
        {
            $username_err = "Please enter a username";
        }
        else
        {
            $sql = "SELECT id FROM users WHERE username = '$username'";
            $result = mysqli_query($db,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $active = $row['active'];
            $count = mysqli_num_rows($result);

            if($count == 1)
             { 
                 $username_err = "This username is already taken.";
             } 
             else
             {
                 $username = trim($_POST["username"]);
             }
         }

             //Validate password
     if(empty(trim($_POST["password"])))
     {
         $password_err = "Please enter a password.";     
     } 
     elseif(strlen(trim($_POST["password"])) < 6)
     {
         $password_err = "Password must have atleast 6 characters.";
     } 
     else
     {
         $password = trim($_POST["password"]);
     }

            // Validate confirm password
    if(empty(trim($_POST["confirm_password"])))
    {
        $confirm_password_err = "Please confirm password.";     
    } 
    else
    {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password))
        {
            $confirm_password_err = "Password did not match.";
        }
    }

            //Validate email
    if (empty(trim($_POST["email"])))
    {
        $email_err = "Please enter an email";
    }
    else
    {
        $email = trim($_POST["email"]);
    }

            //Validate name
    if (empty(trim($_POST["name"])))
    {
        $name_err = "Please enter a name";
    }
    else
    {
        $name = trim($_POST["name"]);
    }

            //Validate age
    if (empty(trim($_POST["age"])))
    {
        $age_err = "Please enter an age";
    }
    else
    {
        $age = trim($_POST["age"]);
    }

            //Validate city
    if (empty(trim($_POST["city"])))
    {
        $city_err = "Please enter a city";
    }
    else
    {
        $city = trim($_POST["city"]);
    }

            //Validate biography
    if (empty(trim($_POST["biography"])))
    {
        $biography_err = "Please enter a biography";
    }
    else
    {
        $biography = trim($_POST["biography"]);
    }


    $sql = "INSERT INTO users (email, username, password, name, age, city, biography) VALUES ('$email', '$username', '$password', '$name', '$age', '$city', '$biography')";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $active = $row['active'];

    if($result)
    {
        echo "Success";
        header("Location: index.php");

    }   
    else
    {
        echo "Error";

    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="txt/css" href="style.css">
    <style type="text/css">
        body
        { 
            font: 14px sans-serif;
            margin: 0;
            padding: 0;
            background: url(nyc.jpg);
            background-size: cover;
            background-position: center;
        }
        
        .loginbox
        {
            width: 800px;
            height: 600px;
            background:rgba(50,50,50,0.8);
            color: #fff;
            top: 50%;
            left: 50%;
            position: absolute;
            transform: translate(-50%, -50%);
            box-sizing: border-box;
            padding: 70px 30px;
            border-radius: 20px;
        }
        .loginbox input[type="submit"]
        {
            border:none;
            outline: none;
            height: 40px;
            width: 745px;
            background: #fb2525;
            color: #fff;
            font-size: 18px;
            border-radius: 20px;
        }
        table, td
        {
            width: 720px;
            transform: translate(0%, -6%);
        }
    </style>
</head>
<body>
    <div class="loginbox">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table>
                <tr>
                    <td colspan="2" align="center">
                        <h2>Sign Up</h2>
                    </td>
                </tr>
                <tr align="center">
                    <td>
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="username" class="form-control" placeholder="Enter Username" value="<?php echo $username; ?>">
                            <label>Username</label>
                            <span class="help-block"><?php echo $username_err; ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="name" class="form-control" placeholder="Enter Name" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err; ?></span>
                            <label>Name</label>
                        </div>
                    </td>
                </tr>
                <tr align="center">
                    <td>   
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <input type="password" name="password" class="form-control" placeholder="Enter Password" value="<?php echo $password; ?>">
                            <span class="help-block"><?php echo $password_err; ?></span>
                            <label>Password</label>
                        </div>
                    </td>
                    <td>
                        <div class="form-group <?php echo (!empty($age_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="age" class="form-control" placeholder="Enter Age" value="<?php echo $age; ?>">
                            <span class="help-block"><?php echo $age_err; ?></span>
                            <label>Age</label>
                        </div>
                    </td>
                </tr>
                <tr align="center">
                    <td>
                        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>">
                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                            <label>Confirm Password</label>
                        </div>
                    </td>
                    <td>
                        <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="city" class="form-control" placeholder="Enter City" value="<?php echo $city; ?>">
                            <span class="help-block"><?php echo $city_err; ?></span>
                            <label>City</label>
                        </div>
                    </td>
                </tr>
                <tr align="center">
                    <td>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="email" class="form-control" placeholder="Enter Email" value="<?php echo $email; ?>">
                            <span class="help-block"><?php echo $email_err; ?></span>
                            <label>Email</label>
                        </div>
                    </td>
                    <td>
                        <div class="form-group <?php echo (!empty($biography_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="biography" class="form-control" placeholder="Enter Biography" value="<?php echo $biography; ?>">
                            <span class="help-block"><?php echo $biography_err; ?></span>
                            <label>Biography</label>
                        </div>
                    </td>
                </tr>
                <tr align="center">
                    <td colspan="2">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <!-- <input type="reset" class="btn btn-default" value="Reset"> -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <p>Already have an account? <a href="index.php">Login here</a>.</p>
                    </td>
                </tr>
            </table>
        </form>
    </div> 
</body>
</html>