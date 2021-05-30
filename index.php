<?php
$server = "localhost";
$username = "root";
$pass = "";
$db = "notes";
$conn = mysqli_connect($server, $username, $pass, $db);
$insert = false;
$delete = false;
$update = false;
// checking if the connection was made successfully
if ($conn) {
    // checking if get has returned anything (deletion)
    if(isset($_GET['delete'])){
        $sno = $_GET['delete'];
        $sqld = "DELETE FROM `notes` WHERE `sno`=$sno";
        $result = mysqli_query($conn, $sqld);
            if ($result) {
                $delete = true;
            }
    }
    // checking if post is set/available
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // checking if the note was updated
        if (isset($_POST['snoEdit'])) {
            $sno = $_POST['snoEdit'];
            $title = $_POST['titleEdit'];
            $desc = $_POST['descEdit'];
            global $update;
            $sqlu = "UPDATE `notes` SET `title`='$title' , `desc`='$desc' WHERE `notes`.`sno`= $sno";
            $result = mysqli_query($conn, $sqlu) or die(mysqli_error($conn));
            if ($result) {
                $update = true;
            }
        }
        elseif (isset($_POST['title']) && isset($_POST['desc'])) {
            $title = $_POST['title'];
            $desc = $_POST['desc'];
            global $insert;
            $sqlc = "INSERT INTO `notes` (`title`, `desc`) VALUES ('$title', '$desc')";
            $result = mysqli_query($conn, $sqlc);
            if ($result) {
                $insert = true;
            }
        } 
            
        
        
        
    }
} else {
    die(mysqli_connect_error());
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <title>iNotes - Notes taking made easy!</title>
</head>

<body>
    <!-- Button trigger modal
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Launch demo modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit this note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container">
                    <form action="/crud/index.php" method="POST">
                        <div class="mb-3">
                            <input type="hidden" name="snoEdit" id="snoEdit">
                            <label for="titleEdit" class="form-label">Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit">
                        </div>
                        <div class="mb-3">
                            <label for="descEdit" class="form-label">Description</label>
                            <textarea class="form-control" id="descEdit" name="descEdit" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand ml-5" href="/crud/index.php"><img src="/crud/PHP-logo.svg" height="28px" alt="PHP Logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <?php
        if ($insert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your note has been added successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        } elseif ($delete) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your note has been deleted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        } elseif ($update) {
            echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your note has been updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        ?>
    </div>
    <div class="container my-3">
        <h2>Add a Note to iNotes</h2>
        <form action="/crud/index.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Give your note a title.">
            </div>
            <div class="mb-3">
                <label for="desc" class="form-label">Description</label>
                <textarea class="form-control" id="desc" name="desc" rows="3" placeholder="Type the description here!!"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>
    <div class="container my-4">
        <table class="table table-hover" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.No.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqls = "SELECT * FROM `notes`";
                $results = mysqli_query($conn, $sqls);
                $no = 1;
                while ($row = mysqli_fetch_assoc($results)) {
                    echo '<tr>
                        <th scope="row">' . $no . '</th>
                        <td>' . $row['title'] . '</td>
                        <td class="col-sm-7">' . $row['desc'] . '</td>
                        <td><button class="btn btn-sm btn-primary edit display-inline" id=' . $row['sno'] . ' name="edit">Edit</button>
                        <button class="btn btn-sm btn-primary delete display-inline" id =d'. $row['sno'].' name="delete">Delete</button>
                        </td>
                    </tr>';
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        edit = document.getElementsByClassName('edit');
        Array.from(edit).forEach(element => {
            element.addEventListener("click", (e) => {
                tr = e.target.parentNode.parentNode;
                let title = tr.getElementsByTagName('td')[0].innerText;
                let desc = tr.getElementsByTagName('td')[1].innerText;
                titleEdit.value = title;
                descEdit.value = desc;
                snoEdit.value = e.target.id;
                $('#editModal').modal('toggle');
            })
        });

        del = document.getElementsByClassName('delete');
        Array.from(del).forEach(element => {
            element.addEventListener("click", function(e){
                sno = e.target.id.substr(1,); // or we may use sno=e.target.parentNode.childNodes[0].id;
                dlt = confirm('Are you sure you want to delete this note');
                if(dlt){
                    window.location = `/crud/index.php?delete=${sno}`;
                }
            })
        });
    </script>
</body>

</html>