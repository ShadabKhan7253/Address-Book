<?php
require_once('./includes/functions.inc.php');

define('ROWS_PER_PAGE',10);

$rows = db_select("SELECT COUNT(*) AS total_count FROM `contacts`");
if($rows === false) {
    dd(db_error());
}

$total_num_of_contacts = $rows[0]['total_count'];
// dd($total_num_of_contacts); // string(2) "26"
$num_of_pages = ceil($total_num_of_contacts / ROWS_PER_PAGE);

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
// dd($_GET['page']); //string(1) "1"

if($current_page < 1 || $current_page > $num_of_pages) {
    // die("404 NOT FOUND");
    header('Location: 404.html');
}

$offset = ($current_page-1)*ROWS_PER_PAGE;
$rows_per_page = ROWS_PER_PAGE;  

$rows = db_select("SELECT * FROM contacts LIMIT $offset,$rows_per_page");
// dd($rows);

if($rows === false) {
    dd(db_error());
}
?>

<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />

    <!--Import Custom CSS-->
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <!--NAVIGATION BAR-->
    <nav>
        <div class="nav-wrapper">
            <!-- Dropdown Structure -->
            <ul id="dropdown1" class="dropdown-content">
                <li><a href="#!">Profile</a></li>
                <li><a href="#!">Signout</a></li>
            </ul>
            <nav>
                <div class="nav-wrapper">
                    <a href="#!" class="brand-logo center">Contact Info</a>
                    <ul class="right hide-on-med-and-down">

                        <!-- Dropdown Trigger -->
                        <li><a class="dropdown-trigger" href="#!" data-target="dropdown1"><i
                                    class="material-icons right">more_vert</i></a></li>
                    </ul>
                </div>
            </nav>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
    </nav>
    <!--/NAVIGATION BAR-->

    <!-- Add a New Contact Link-->
    <div class="row mt50">
        <div class="col s12 right-align">
            <a class="btn waves-effect waves-light blue lighten-2" href="add-contact.php">
                <i class="material-icons left">add</i>AddNew
            </a>
        </div>
    </div>
    <!-- /Add a New Contact Link-->

    <!-- Table of Contacts -->
    <div class="row">
        <div class="col s12">
            <table class="highlight centered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email ID</th>
                        <th>Date Of Birth</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach($rows as $row):
                    ?>
                    <tr>
                        <td><img class="circle" src="images/users/<?= get_image_name($row['image_name'],$row['id']) ?>" alt="" width="150px" height="150px"></td>
                        <td><?= $row['first_name']. " " . $row['last_name']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['birthdate']; ?></td>
                        <td><?= $row['telephone']; ?></td>
                        <td><?= $row['address']; ?></td>
                        <td><a href="edit-contact.php?id=<?= $row['id'];?>" class="btn btn-floating green lighten-2"><i class="material-icons">edit</i></a></td>
                        <td><a class="btn btn-floating red lighten-2 modal-trigger delete-contact" data-id="<?= $row['id']; ?>" href="#deleteModal"><i class="material-icons">delete_forever</i></a>
                        </td>
                    </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /Table of Contacts -->
    <!-- Pagination -->
    <div class="row">
        <div class="col s12">
            <ul class="pagination">
            <?php
                $left = $current_page == 1 ? "disabled " : "enabled waves-effect";
            ?>
                <li class="<?=$left ?>"><a href="?page=<?=($current_page == 1) ? 1 : $current_page - 1 ?>"><i class="material-icons">chevron_left</i></a></li>
                <?php
                for($i=1;$i<=$num_of_pages;$i++):
                    $styles = $i == $current_page ? "active waves-effect" : "waves-effect";
                ?>
                    <li class="<?= $styles; ?>"><a href="?page=<?=$i;?>"><?= $i; ?></a></li>
                <?php
                endfor;
                ?>
                <!-- <li class="waves-effect"><a href="#!">2</a></li>
                <li class="waves-effect"><a href="#!">3</a></li>
                <li class="waves-effect"><a href="#!">4</a></li>
                <li class="waves-effect"><a href="#!">5</a></li> -->
                <?php
                $right = $current_page == $num_of_pages ? "disabled " : "enabled waves-effect";
                ?>
                <li class=<?=$right ?>>
                    <a href="?page=<?= ($current_page == $num_of_pages) ? $num_of_pages : $current_page + 1  ?>">
                        <i class="material-icons">chevron_right</i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Pagination -->
    <!-- Footer -->
    <footer class="page-footer p0">
        <div class="footer-copyright ">
            <div class="container">
                <p class="center-align">© 2023-24  All rights reserved.</p>
            </div>
        </div>
    </footer>
    <!-- /Footer -->
    <!-- Delete Modal Structure -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h4>Delete Contact?</h4>
            <p>Are you sure you want to delete the record?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close btn blue-grey lighten-2 waves-effect">Cancel</a>
            <a href="#!" class="modal-close btn waves-effect red lighten-2" id="modal-agree">Agree</a>
        </div>
    </div>
    <!-- /Delete Modal Structure -->
    <!--JQuery Library-->
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <!--Include Page Level Scripts-->
    <script src="js/pages/home.js"></script>
    <!--Custom JS-->
    <script src="js/custom.js" type="text/javascript"></script>
</body>

</html>