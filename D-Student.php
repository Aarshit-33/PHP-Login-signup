<?php
    include_once("connection.php");
    
    // query to sign up or creating new account
    
    if(isset($_POST['stu_submit_signup'])){

        $acc_create;
        $signup_error;
        $acc_create_error;
        $acc_create;

        $name=$_POST['s_name'];
        $uname=$_POST['s_username'];
        $email=$_POST['s_email'];
        $pass=$_POST['s_pass'];
        $cpass=$_POST['s_cpass'];

echo $name."<br>";
echo $uname."<br>";
echo $email."<br>";
echo $pass."<br>";
echo $cpass;    

        if($pass != $cpass){
            $signup_error=true;
            $acc_create_error=false;
            $acc_create=false; 
        }
        else
        {
            if($pass == $cpass && $signup_error=false){
                $qry="SELECT `user_name` FROM `users` WHERE `user_name`='$uname';";
                $res=mysqli_query($connect, $qry);
                
                if(mysqli_num_rows($res)>0){
                    $acc_create_error=true;
                    $signup_error=true;
                    $acc_create=false; 
                    $email_exist=false;
                }
                
                $qry1="SELECT `user_email` FROM `users` WHERE `user_email`='$email';";
                $res1=mysqli_query($connect, $qry1);
                
                if(mysqli_num_rows($res1)>0){
                    $email_exist=true;
                    $acc_create_error=false;
                    $signup_error=true;
                    $acc_create=false; 
                } 
                
            }
            else{
                $qry="INSERT INTO `users` (`user_full_name`, `user_name`, `user_pass`, `user_email`) VALUES ('$name', '$uname', '$pass', '$email')";
                $res=mysqli_query($connect, $qry);
                $acc_create=true;
            }
        }
    }

    // query to login as student or faculty

    if(isset($_POST['fac_submit_login']) OR isset($_POST['stu_submit_login'])){
        
        $fac_login=false;
        $stu_login=false;

        $name;
        $pass;

        if(isset($_POST['fac_submit_login'])){
            $name = $_POST['fusername'];
            $pass = $_POST['fpass'];
            $qry = "SELECT `faculty_username` FROM `faculty` WHERE `faculty_username`='$name' AND `pass`='$pass';";
            $res = mysqli_query($connect, $qry);
            if(mysqli_num_rows($res)>0){
                $fac_login=true;
                $stu_login=false;
            }
        }
        else{
            $name = $_POST['username'];
            $pass = $_POST['pass'];
            $qry = "SELECT `user_name` FROM `users` WHERE `user_name`='$name' AND `user_pass`='$pass';";
            $res = mysqli_query($connect, $qry);
            if(mysqli_num_rows($res)>0){
                $stu_login=true;
                $fac_login=false;
            }
        }

        // echo $name;
        // echo $pass;     
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D-Student</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">     
        <div class="bg">
            <div class="box log-in">
                <h2>Are You A Student ?</h2>
                <button class="stu_login btn">Student Log In</button>
            </div>
            <div class="box sign-up">
                <h2>Are You A Faculty ?</h2>
                <button class="fac_login btn">Faculty Log In</button>
            </div>
        </div>
        
     
        

        <!-- Login forms -->

        <div class="forms">
                           <!--  Error or success msg -->
            <div class="display-error">    
                <?php                                                       
                    if(isset($signup_error) && $signup_error==true  && $acc_create_error==false && $email_exist==false){
                        echo "Password Not Matching";           
                    }
                    
                    elseif(isset($acc_create_error) && $acc_create_error==true){
                        echo "Username Already Exist";
                    }
                    elseif(isset($acc_create) && $acc_create==true){
                        echo "Account Created Successfully";
                    }
                    elseif(isset($email_exist ) && $email_exist==true){
                        echo $email."<br>This e-mail Already in use, try with another email address";
                    }
                    elseif(isset($fac_login) || isset($stu_login)){
                        if($fac_login==true){
                            echo "Logged in as Faculty";
                        }
                        elseif($stu_login==true){
                            echo "Logged in as Student";
                        }
                        else{
                            echo "Username or Password not matching";
                        }
                    }
                ?>
            </div>   

             <!-- Faculty Login Form -->

            <div class="fac_login_form form_hide">
                <form method="post" class="login-form">
                    
                    <h2>Faculty Login Form</h2>
                    
                    <div class="title">Username</div>
                    <div class="ip"><input type="text" name="fusername" value="<?php if(isset($fac_login) && $fac_login==false){echo $name;} ?>"  required></div>
                    
                    <div class="ip">Password</div>
                     <div class="ip"><input type="password" name="fpass" value="<?php if(isset($fac_login) && $fac_login==false){echo $pass;} ?>" minlength="8" required></div>
                    
                    <div><input type="submit" name="fac_submit_login" ></div> <!-- Enable this button for submit form -->
                
                </form> 
             </div>

            <!-- Student Login Form -->

            <div class="stu_login_form">
               <form method="post" class="login-form">
                   <h2>Student Login Form</h2>
                    <div class="title">Username</div> 
                    <div class="ip"><input type="text" name="username" value="<?php if(isset($stu_login) && $stu_login==false){echo $name;} ?>" required></div>

                    <div class="title">Password</div> 
                    <div class="ip"><input type="password" name="pass" value="<?php if(isset($stu_login) && $stu_login==false){echo $pass;} ?>" minlength="8" required></div>
                    
                    <div><input type="submit" name="stu_submit_login" ></div> <!-- Enable this button for submit form -->
                </form>
                <script>
                    console.log("eoror");
                    </script>
                <div class="signuplink">
                    <h4>Don't Have an Account ?</h4>
                    <a href="#" class="signupbtn">Sign up</a> 
                </div>    
            </div>

             <!-- Student Sign Form -->

            <div class="stu_signup_form form_hide">
                <form method="post" class="login-form">
                    <h2>Student Sign-up Form</h2>  
                    <div class="title">Name</div> 
                    <div class="ip"><input type="text"  name="s_name" value="<?php if(isset($signup_error) && $signup_error==true){echo $name;} ?>" required></div>

                    <div class="title">Username</div> 
                    <div class="ip"><input type="text"  name="s_username" value="<?php if(isset($signup_error) && $signup_error==true){echo $uname;} ?>" required></div>

                    <div class="title">E-mail</div> 
                    <div class="ip"><input type="email"  name="s_email" value="<?php if(isset($signup_error) && $signup_error==true){echo $email;} ?>" required></div>

                    <div class="title">Password</div> 
                    <div class="ip"><input type="password" name="s_pass" value="<?php if(isset($signup_error) && $signup_error==true){echo $pass;} ?>" minlength="8" required></div>
                    
                    <div class="title">Confirm Password</div> 
                    <div class="ip"><input type="password" name="s_cpass" value="<?php if(isset($signup_error) && $signup_error==true){echo $cpass;} ?>" minlength="8" required></div>

                    <div><input type="submit"  name="stu_submit_signup" ></div> <!-- Enable this button for submit form -->
                 </form>
                 
                 <div class="signuplink">
                     <h4>Already Have an Account ?</h4>
                     <a href="#" class="loginbtn">Log in</a> 
                 </div>    
             </div>
        </div>
    </div>

    <!-- JS to slide form panel on click -->

    <script>
        const fac_login = document.querySelector('.fac_login');
        const stu_login = document.querySelector('.stu_login');
        const fac_login_form = document.querySelector('.fac_login_form');
        const stu_login_form = document.querySelector('.stu_login_form');
        const stu_signup_form = document.querySelector('.stu_signup_form');
        const forms = document.querySelector('.forms');
        const signupbtn = document.querySelector('.signupbtn');
        const loginbtn = document.querySelector('.loginbtn');

        signupbtn.onclick = function(){
            stu_login_form.classList.add('form_hide');
            stu_signup_form.classList.remove('form_hide');
        }

        loginbtn.onclick = function(){
            stu_login_form.classList.remove('form_hide');
            stu_signup_form.classList.add('form_hide');
        }

        stu_login.onclick = function(){
            forms.classList.remove('active');
            fac_login_form.classList.add('form_hide');
            stu_login_form.classList.remove('form_hide');
        }

        fac_login.onclick = function(){
            forms.classList.add('active');
            stu_signup_form.classList.add('form_hide');
            stu_login_form.classList.add('form_hide');
            fac_login_form.classList.remove('form_hide');
        }

    </script>   
</body>
</html>