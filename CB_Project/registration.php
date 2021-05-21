<?php include('server.php') ?>
<!DOCTYPE html>
<html>

<head>

    <title>To Do</title>
    <link rel="stylesheet" href="registration-style.css" type="text/css">
</head>

<body>

    <div class="container">

        <form action="registration.php" method="post">

            <div>
                <img src="to-do.svg" class="todo" alt="To-Do Logo">
            </div>

            <div>

                <label class="label" for="username">Username : </label>
                <input type="text" class="input-field" name="username" required>

            </div>

            <div>

                <label class="label" for="email">Email : </label>
                <input type="text" class="input-field" name="email" required>

            </div>

            <div>

                <label class="label" for="password">Password : </label>
                <input type="text" class="input-field" name="password" required>

            </div>

            <div>

                <label class="label" for="confirm_password">Confirm Password : </label>
                <input type="text" class="input-field" name="confirm_password" required>

            </div>

            <button type="submit" name="register" class="signup-btn">Sign Up</button>

        </form>

    </div>
</body>

</html>