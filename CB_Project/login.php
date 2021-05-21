<?php include('server.php') ?>
<!DOCTYPE html>
<html>

<head>

    <title>To Do</title>
    <link rel="stylesheet" href="login-style.css" type="text/css">
</head>

<body class="body">

    <div class="container">

        <form action="login.php" method="post">

            <div>
                <img src="to-do.svg" class="todo" alt="To-Do Logo">
            </div>

            <div>

                <label class="label" for="username">Username : </label>
                <input type="text" class="input-field" name="username">

            </div>

            <div>

                <label class="label" for="password">Password : </label>
                <input type="text" class="input-field" name="password">

            </div>

            <button type="submit" name="login" class="login-btn">SIGN IN</button>
            <p class="register-link">A New User? <a href="registration.php">Sign up</a> </p>

        </form>

    </div>
</body>

</html>