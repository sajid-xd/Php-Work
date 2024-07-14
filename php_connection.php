<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Connection</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showUpdateForm(id) {
            // Hide all update forms
            var forms = document.getElementsByClassName('update-form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].style.display = 'none';
            }
            // Show the update form for the selected record
            document.getElementById('update-form-' + id).style.display = 'block';
        }
    </script>
    <style>
        .update-form {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <?php
        include "connection.php";

        // Check the connection
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Handle form submission for adding a new record
        if (isset($_POST['btn'])) {
            $name = $_POST['txtname'];
            $ph = $_POST['txtphone'];
            $add = $_POST['txtadd'];

            $insert_query = "INSERT INTO con (name, phone, address) VALUES ('$name', '$ph', '$add')";

            if (mysqli_query($con, $insert_query)) {
                echo '<div class="alert alert-success" role="alert">New record created successfully</div>';
            }
        }

        // Handle form submission for deleting a record
        if (isset($_POST['delete'])) {
            $id_to_delete = $_POST['id_to_delete'];
            $delete_query = "DELETE FROM con WHERE id = $id_to_delete";
            if (mysqli_query($con, $delete_query)) {
                echo '<div class="alert alert-success" role="alert">Record deleted successfully</div>';
            }
        }

        // Handle form submission for updating a record
        if (isset($_POST['update'])) {
            $id_to_update = $_POST['id_to_update'];
            $new_name = $_POST['new_name'];
            $new_phone = $_POST['new_phone'];
            $new_address = $_POST['new_address'];

            $update_query = "UPDATE con SET name='$new_name', phone='$new_phone', address='$new_address' WHERE id=$id_to_update";
            if (mysqli_query($con, $update_query)) {
                echo '<div class="alert alert-success" role="alert">Record updated successfully</div>';
            }
        }

        // Fetch all records
        $result = mysqli_query($con, "SELECT * FROM con");
        ?>

        <form method="post" class="mb-4">
            <div class="form-group">
                <label for="txtname">Name</label>
                <input type="text" class="form-control" id="txtname" name="txtname" required>
            </div>
            <div class="form-group">
                <label for="txtphone">Phone</label>
                <input type="text" class="form-control" id="txtphone" name="txtphone" required>
            </div>
            <div class="form-group">
                <label for="txtadd">Address</label>
                <input type="text" class="form-control" id="txtadd" name="txtadd" required>
            </div>
            <button type="submit" class="btn btn-primary" name="btn">Submit</button>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td>00<?=$row["id"]?></td>
                    <td><?=$row["name"]?></td>
                    <td><?=$row["phone"]?></td>
                    <td><?=$row["address"]?></td>
                    <td>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="id_to_delete" value="<?=$row["id"]?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="delete">Delete</button>
                        </form>
                        <button class="btn btn-warning btn-sm" onclick="showUpdateForm(<?=$row['id']?>)">Update</button>
                        <form method="post" class="update-form mt-2" id="update-form-<?=$row['id']?>" style="display:none;">
                            <input type="hidden" name="id_to_update" value="<?=$row['id']?>">
                            <div class="form-group">
                                <input type="text" class="form-control" name="new_name" value="<?=$row['name']?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="new_phone" value="<?=$row['phone']?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="new_address" value="<?=$row['address']?>" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm" name="update">Save</button>
                        </form>
                    </td>
                </tr>
                <?php } 
                mysqli_close($con);?>
            </tbody>
        </table>
    </div>
</body>
</html>
