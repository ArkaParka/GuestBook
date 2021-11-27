<?php
require_once('config.php');

if (empty($_SESSION['user_id'])) {
    header('location: login.php');
}

if (!empty($_POST['comment'])) {
    $stmt = $dbConn->prepare('INSERT INTO comments(user_id, comment) VALUES(:user_id, :comment)');
    $stmt-> execute(array('user_id' => $_SESSION['user_id'], 'comment' => $_POST['comment']));

    header("Location: /index.php");
}

$stmt = $dbConn->prepare('SELECT * FROM comments ORDER BY id DESC');
$stmt->execute();
$comments = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Guest Book</title>
    <link rel="stylesheet" href="./index.scss">
</head>
<body>
    <div class="content">
        <div class="header">
            <h1>Comments Page</h1>
        </div>
        <div id="comments-form" class="form">
            <form method="POST">
                <textarea name="comment" placeholder="Enter your comment..."></textarea>
                <input class="form-btn" type="submit" name="submit" value="Save"/>
            </form>
        </div>
        <div id="comments-panel">
            <h3>Comments:</h3>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="avatar">
                        <img src="./sources/icon2.jpg">
                    </div>
                    <div class="comment-content">
                        <div class="comment-username">
                            Anon
                        </div>
                        <div class="comment-date">
                            <?php
                                $date = $comment['created_at'];
                                $d1 = strtotime($date); // переводит из строки в дату
                                echo date("d-m-Y", $d1); // переводит в новый формат
                            ?>
                        </div>
                        <div class="comment-text" <?php if($comment['user_id'] == $_SESSION['user_id']) echo 'style="font-weight: bold;"';?>>
                            <?php echo $comment['comment']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

