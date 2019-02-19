<?php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TEST</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid introduce-block" style="background-color: #38863d; height: 100vh; width: 100vw; display: flex; justify-content: center; align-items: center; position: absolute;">
    <p style=""><?php echo $_POST['error'] ?></p>
    <div style="width: 250px; height: 150px; background-color: white; display: flex; justify-content: center; text-align: center; border-radius: 5px; margin-top: calc ( 50% - 150px )">
        <form action="" method="post">
            <p style="margin-top: 10px">Input new password</p>
            <input type="password" name="password">
            <br>
            <button type="submit" style="border-radius: 5px; background-color: #565656; color: white; padding: 5px 15px 5px 15px; margin-top: 17px">Change</button>
        </form>
    </div>
</div>
</body>
</html>