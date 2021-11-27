<?php
require_once('config.php');

if (!empty($_SESSION['user_id'])) {
//     header('location: /index.php');
}

$errors = [];
$isRegistered = 0;

if (!empty($_GET['registration'])) {
    $isRegistered = 1;
}

if (!empty($_POST)) {
    if (empty($_POST['user_name'])) {
        $errors[] = 'Please enter User Name / Email';
    }
    if (empty($_POST['password'])) {
        $errors[] = 'Please enter password';
    }
    if (empty($errors)) {
        $stmt = $dbConn->prepare("
            SELECT id
            FROM users
            WHERE (username = :username or email = :username) and password = :password
        ");

        $stmt->execute(array(
            'username' => $_POST['user_name'],
            'password' => sha1($_POST['password'].SALT)
        ));

        $id = $stmt->fetchColumn();
        if (!empty($id)) {
            $_SESSION['user_id'] = $id;
            die('Вы успешно авторизованы');
        } else {
            $errors[] = 'Please enter valid credentials';
        }
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./index.scss">
    <title>My Guest Book</title>
</head>
<body>
<?php if(!empty($isRegistered)) :?>
    <h2>Вы успешно зарегистрировались! Используйте свои данные для входа на сайт</h2>
<?php endif; ?>
    <div class="content">
        <div class="header">
            <h1>Log In Page</h1>
        </div>
        <div id="login-form" class="form">
            <form method="POST">
                <div style="color: red;">
                    <?php foreach ($errors as $error) :?>
                        <p><?php echo $error;?></p>
                    <?php endforeach; ?>
                </div>
                <div class="form-input">
                    <div class="title">
                        <label>User Name / Email:</label>
                    </div>
                    <div>
                        <input class="input" type="text" name="user_name" required="" value="<?php echo (!empty($_POST['user_name']) ? $_POST['user_name'] : '');?>"/>
                    </div>
                </div>
                <div>
                    <div class="title">
                        <label>Password:</label>
                    </div>
                    <div>
                        <input class="input" type="password" name="password" required="" value=""/>
                    </div>
                </div>
                <div>
                    <br/>
                    <input class="form-btn" type="submit" name="submit" value="Log In"/>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

