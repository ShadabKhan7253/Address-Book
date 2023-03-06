<!DOCTYPE html>
<html>
<?php
require_once('./includes/functions.inc.php');

$error = false;
if(isset($_POST['action'])) {
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $birthdate = date('Y-m-d',strtotime(sanitize($_POST['birthdate'])));
    $telephone = sanitize($_POST['telephone']);
    $address = sanitize($_POST['address']);
    $id = $_POST['contact_id'];
    
    $query = "SELECT image_name FROM contacts WHERE id = $id";
    // dd($query);
    $rows = db_select($query)[0];
    $old_image_name = $rows['image_name'];
    // dd($old_image_name);

    if(!$first_name || !$last_name || !$email || !$birthdate || !$telephone || !$address || !isset($_FILES['pic']['name'])) {
        $error = true;
    } else {
        // we have validate values, which we can directly insert in database

        // $_FILES = array(1) { ["pic"]=> array(5) { ["name"]=> string(19) "antonio-freeman.jpg" ["type"]=> string(10) "image/jpeg" ["tmp_name"]=> string(39) "D:\Programe Files\Xampp\tmp\phpB4C1.tmp" ["error"]=> int(0) ["size"]=> int(5004) } }
        if(!empty($_FILES['pic']['name'])) {
            $tmp_file_nmae = $_FILES['pic']['name']; // antonio-freeman.jpg
            $tmp_file_path = $_FILES['pic']['tmp_name']; // D:\Programe Files\Xampp\tmp\php696A.tmp
            $file_name_as_array = explode(".",$tmp_file_nmae); // convert string to array ie file_name_as_array[0] = antonio-freeman and file_name_as_array[0] = jpg
            // dd($file_name_as_array);
            $ext = end($file_name_as_array);
            $data['image_name'] = $ext;
        }


        $data['first_name'] = $first_name;
        $data['last_name'] = $last_name;
        $data['birthdate'] = $birthdate;
        $data['telephone'] = $telephone;
        $data['email'] = $email;
        $data['address'] = $address;
        $query = prepare_update_query("contacts",$data,"id = $id");
        // dd($query);
        db_query($query);
        if(isset($data['image_name'])) {
            // DELETE THE OLD IMAGE
            $old_image_name = get_image_name($old_image_name,$id);
            unlink("images/users/$old_image_name");

            $file_name = "$id.$ext";
            move_uploaded_file($tmp_file_path,"images/users/$file_name");
            header("Location: index.php?op=edit&status=success");
        }
    }
} 

if(!isset($_GET['id']))
{
    dd('404! Page Not Found');
}
$id = $_GET['id'];
$query = "SELECT * FROM contacts WHERE id = $id";
$rows = db_select($query)[0];
?>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />

    <!--Import Csutom CSS-->
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Edit Contact</title>
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
    <div class="container">
        <div class="row mt50">
            <h2>Edit Contact</h2>
        </div>
        <?php
        if($error):
        ?>
        <div class="row">
            <div class="materialert error">
                <div class="material-icons">error_outline</div>
                Oh! What a beautiful alert :)
                <button type="button" class="close-alert">×</button>
            </div>
        </div>
        <?php
        endif;
        ?>
        <div class="row">
            <form class="col s12 formValidate" action="<?=$_SERVER['PHP_SELF']; ?>" id="add-contact-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="contact_id" value="<?= $rows['id'];?>">
                <div class="row mb10">
                    <div class="input-field col s6">
                        <input id="first_name" name="first_name" type="text" class="validate" data-error=".first_name_error" value="<?=old($_POST,'first_name',$rows['first_name']);?>">
                        <label for="first_name">First Name</label>
                        <div class="first_name_error "></div>
                    </div>
                    <div class="input-field col s6">
                        <input id="last_name" name="last_name" type="text" class="validate" data-error=".last_name_error" value="<?=old($_POST,'last_name',$rows['last_name']);?>">
                        <label for="last_name">Last Name</label>
                        <div class="last_name_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s6">
                        <input id="email" name="email" type="email" class="validate" data-error=".email_error" value="<?=old($_POST,'email',$rows['email'])?>">
                        <label for="email">Email</label>
                        <div class="email_error "></div>
                    </div>
                    <div class="input-field col s6">
                        <input id="birthdate" name="birthdate" type="text" class="datepicker" data-error=".birthday_error" value="<?=old($_POST,'birthdate',$rows['birthdate'])?>">
                        <label for="birthdate">Birthdate</label>
                        <div class="birthday_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s12">
                        <input id="telephone" name="telephone" type="tel" class="validate" data-error=".telephone_error" value="<?=old($_POST,'telephone',$rows['telephone']);?>">
                        <label for="telephone">Telephone</label>
                        <div class="telephone_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                    <div class="input-field col s12">
                        <textarea id="address" name="address" class="materialize-textarea" data-error=".address_error"><?=old($_POST,'address',$rows['address']);?></textarea>
                        <label for="address">Addess</label>
                        <div class="address_error "></div>
                    </div>
                </div>
                <div class="row mb10">
                <div class="col s2">
                        <img id="temp_pic" width="150px" height="150px" class="circle" src="./images/users/<?= get_image_name($rows['image_name'],
                        $id)?>" 
                        alt="<?= $rows['first_name']." ". $rows['last_name'];?>" >
                    </div>
                    <div class="file-field input-field col s10">
                        <div class="btn">
                            <span>Image</span>
                            <input type="file" name="pic" id="pic" data-error=".pic_error" >
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload Your Image">
                        </div>
                        <div class="pic_error "></div>
                    </div>
                </div>
                <button class="btn waves-effect waves-light right" type="submit" name="action">Update
                        <i class="material-icons right">send</i>
                    </button>
            </form>
        </div>
    </div>
    <footer class="page-footer p0">
        <div class="footer-copyright ">
            <div class="container">
                <p class="center-align">© 2023-24  All rights reserved.</p>
            </div>
        </div>
    </footer>
    <!--JQuery Library-->
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <!--JQuery Validation Plugin-->
    <script src="vendors/jquery-validation/validation.min.js" type="text/javascript"></script>
    <script src="vendors/jquery-validation/additional-methods.min.js" type="text/javascript"></script>
    <!--Include Page Level Scripts-->
    <script src="js/pages/edit-contact.js"></script>
    <!--Custom JS-->
    <script src="js/custom.js" type="text/javascript"></script>
</body>

</html>