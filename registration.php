<?php
require_once('config.php');

if (!empty($_SESSION['user_id'])) {
//     header('location: /index.php');
}

$errors = [];

if (!empty($_POST)) {
    if (empty($_POST['user_name'])) {
        $errors[] = 'Please enter User Name';
    }
    if (empty($_POST['email'])) {
        $errors[] = 'Please enter email';
    }
    if (empty($_POST['first_name'])) {
        $errors[] = 'Please enter First Name';
    }
    if (empty($_POST['last_name'])) {
        $errors[] = 'Please enter Last Name';
    }
    if (empty($_POST['password'])) {
        $errors[] = 'Please enter password';
    }
    if (empty($_POST['confirm_password'])) {
        $errors[] = 'Please confirm password';
    }
    if (strlen($_POST['user_name']) > 100) {
        $errors[] = 'User Name is too long. Max length is 100 characters';
    }
    if (strlen($_POST['first_name']) > 80) {
        $errors[] = 'First Name is too long. Max length is 80 characters';
    }
    if (strlen($_POST['last_name']) > 150) {
        $errors[] = 'Last Name is too long. Max length is 150 characters';
    }
    if (strlen($_POST['password']) < 6) {
        $errors[] = 'Password should contains at least 6 characters';
    }
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $errors[] = 'Your confirm password is not match password';
    }
    if (empty($errors)) {
        $stmt = $dbConn->prepare("
            INSERT INTO users(username, email, password, first_name, last_name)
            VALUES(:username, :email, :password, :first_name, :last_name)
        ");

        $stmt->execute(array(
            'username' => $_POST['user_name'],
            'email' => $_POST['email'],
            'password' => sha1($_POST['password'].SALT),
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
        ));

        header('location: /login.php?registration=1');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./index.scss">
    <title>My Guest Book</title>
</head>
<body>
    <div class="content">
        <div class="header">
            <h1>Registration Page</h1>
        </div>
        <div id="register-form" class="form">
            <form method="POST">
                <div style="color: red;">
                    <?php foreach ($errors as $error) :?>
                        <p><?php echo $error;?></p>
                    <?php endforeach; ?>
                </div>
                <div class="form-input">
                    <div class="title">
                        <label>User Name:</label>
                    </div>
                    <div>
                        <input class="input" type="text" name="user_name" required="" value="<?php echo(!empty($_POST['user_name']) ? $_POST['user_name'] : '');?>"/>
                    </div>
                </div>
                <div class="form-input">
                    <div class="title">
                    <label>Email:</label>
                    </div>
                    <div>
                        <input class="input" type="email" name="email" required="" value="<?php echo(!empty($_POST['email']) ? $_POST['email'] : '');?>"/>
                    </div>
                </div>
                <div class="form-input">
                    <div class="title">
                        <label>First Name:</label>
                    </div>
                    <div>
                        <input class="input" type="text" name="first_name" required="" value="<?php echo(!empty($_POST['first_name']) ? $_POST['first_name'] : '');?>"/>
                    </div>
                </div>
                <div class="form-input">
                    <div class="title">
                        <label>Last Name:</label>
                    </div>
                    <div>
                        <input class="input" type="text" name="last_name" required="" value="<?php echo(!empty($_POST['last_name']) ? $_POST['last_name'] : '');?>"/>
                    </div>
                </div>
                <div class="form-input">
                    <div class="title">
                        <label>Password:</label>
                    </div>
                    <div>
                        <input class="input" type="password" name="password" required="" value=""/>
                    </div>
                </div>
                <div>
                    <div class="title">
                        <label>Confirm Password:</label>
                    </div>
                    <div>
                        <input class="input" type="password" name="confirm_password" required="" value=""/>
                    </div>
                </div>
                <div>
                    <br/>
                    <input class="form-btn" type="submit" name="submit" value="Register"/>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


