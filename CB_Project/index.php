<!-- Protected Route to ensure this screen only open if user is logged in -->
<?php include('server.php');

if ($_SESSION['success'] == false) {
    header('location: login.php');
}


if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['Username']);
    header('location: login.php');
}

?>


<!DOCTYPE html>
<html>

<head>

    <title>To Do</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="index-style.css" type="text/css">


    <style>
        .btn-active {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .label-tags
        {
            border-radius: 5px; 
            background-color: #3B5998; 
            color: white; 
            padding: 6px; 
            min-width: 5px; 
            margin-right: 10px;
        }
    </style>


</head>

<body>

    <?php if (isset($_SESSION['success'])) : ?>
        <!-- check if user is logged in then display the homepage -->

        <div class="container">

            <!-- Bootstrap Modal to Add New Task -->
            <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
                        </div>
                        <form action="index.php" method="post">
                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="task-title">Task Title</label>
                                    <input type="text" class="form-control" id="task-title" name="task_title" placeholder="Enter Task Title" required>
                                </div>

                                <div class="button_container"> <!-- Priority Buttons -->
                                    <div class="text-left">
                                        <input id="priority" type="hidden" value="high" name="priority">
                                        <button type="button" class="btn btn-outline-info priority-class btn-active" data-name="high">High</button>
                                        <button type="button" class="btn btn-outline-info priority-class" data-name="medium">Medium</button>
                                        <button type="button" class="btn btn-outline-info priority-class" data-name="low">Low</button>
                                    </div>
                                </div>
                                <br>

                                <label for="label">Labels</label>

                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Enter Label" name="label">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="button" name="add_label">Add</button>
                                    </div>
                                </div>

                                <!-- <span style="border-radius: 5px; background-color: #3B5998; color: white; padding: 6px; min-width: 5px; margin-right: 10px; ">
                                    entertainment
                                </span> -->

                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info btn-lg btn-block" name="add_task">Create Task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div>
                <div class="logout">
                    <a href="index.php?logout='1'">Sign Out</a>
                </div>
                <span class="title" style="font-size: 40px; font-weight:bolder;">

                    Welcome to your to-do list!
                </span>

                <div style="float: right; position:relative; margin-top: 20px;">

                    <button type="button" class="btn btn-info" name="add_btn" data-toggle="modal" data-target="#add">
                        Add New Task
                    </button>

                </div>


            </div>


            <?php

            $user = $_SESSION['username'];
            $query = "SELECT * from tasks JOIN user_task ON tasks.TaskId = user_task.TaskId where Username = '$user'";
            $result = mysqli_query($db, $query);


            ?>

            <!-- Bootstrap Table to display User Tasks -->
            <table class="table" style="margin-top: 50px;">
                <tbody>
                    <?php while ($row = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td style="width: 16.66%"><?php echo $row['Task'] ?></td>

                            <?php
                            $index = $row['TaskId'];
                            $query_2 = "SELECT * from tasks JOIN labels ON tasks.TaskId = labels.TaskId where tasks.TaskId = '$index'";
                            $result_2 = mysqli_query($db, $query_2);
                            while ($row2 = mysqli_fetch_array($result_2)) { ?>
                                <td style="width: 50%;">
                                    <span class="label-tags">
                                    <?php
                                    echo $row2['Label'];
                                    ?>

                                    </span>
                                </td>
                            <?php } ?>

                            <td><b><?php echo $row['_Priority'] ?></b></td>
                            <td style="width: 50px;"><a href="modalEdit" class="modalEdit" data-toggle="modal" data-target="#modalEdit" data-id="<?php echo $row['TaskId'] ?>"><img src="edit.svg" alt="Kiwi standing on oval"></a></td>
                            <td style="width: 50px;"><a href="index.php?del_task=<?php echo $row['TaskId'] ?>"><img src="delete.svg" alt="Kiwi standing on oval"></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>



            <!-- Bootstrap Modal to Edit a Task -->
            <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Task</h5>
                        </div>
                        <form action="index.php" method="post">
                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="edit_task_title">Task Title</label>
                                    <input type="text" class="form-control" id="edit_task_title" name="edit_task_title" placeholder="Enter Task Title" required>
                                </div>

                                <div class="button_container">  <!-- Priority Buttons -->
                                    <div class="text-left">
                                        <input id="edit_priority" type="hidden" value="high" name="edit_priority">
                                        <button type="button" class="btn btn-outline-info edit-priority-class btn-active" data-name="high">High</button>
                                        <button type="button" class="btn btn-outline-info edit-priority-class" data-name="medium">Medium</button>
                                        <button type="button" class="btn btn-outline-info edit-priority-class" data-name="low">Low</button>
                                    </div>
                                </div>
                                <div class="modal-taskid">
                                    <input type="hidden" name="bookId" id="bookId" value="" />
                                </div>
                                <br>
                                <label>Labels</label>

                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Enter Label" name="edit_label">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="button" name="add_label">Add</button>
                                    </div>
                                </div>

                                <!-- <span style="border-radius: 5px; background-color: #3B5998; color: white; padding: 6px; min-width: 5px; margin-right: 10px; ">
                                    entertainment
                                </span> -->



                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info btn-lg btn-block" name="edit_task">Edit Task</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

            <!-- JQuery to manage values of priority buttons -->
            <script>  
                $(function() {
                    $('.priority-class').click(function() {
                        activate(this);
                        let priority = $(this).data('name');
                        $('#priority').val(priority);
                    });
                })

                function inactivate() {
                    $('.priority-class').each(function() {
                        $(this).removeClass('btn-active');
                    });
                    $('.btn-active').removeClass('btn-active');

                }

                function activate(obj) {
                    inactivate();
                    $(obj).addClass('btn-active');
                }

                $(function() {
                    $('.edit-priority-class').click(function() {
                        activate(this);
                        let priority = $(this).data('name');
                        $('#edit_priority').val(priority);
                    });
                })

                function inactivate() {
                    $('.edit-priority-class').each(function() {
                        $(this).removeClass('btn-active');
                    });
                    $('.btn-active').removeClass('btn-active');

                }

                $(document).on("click", ".modalEdit", function() {
                    var myBookId = $(this).data('id');
                    $(".modal-taskid #bookId").val(myBookId);
                });
            </script>

        </div>

    <?php endif ?>

</body>

</html>