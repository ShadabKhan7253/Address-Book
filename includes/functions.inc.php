<?php
function db_connect()
{
    static $connection; // har baar connection establish krta or naya connection lata agr static nhi deta tu
    if(!isset($connection)) {
        $config = parse_ini_file("./config.ini");

        $connection = mysqli_connect($config['host'],$config['username'],$config['password'],$config['database'],$config['port']);
    }
    if(!$connection) {
        dd(mysqli_connect_error());
    }  
    return $connection;
}

function db_query($query) {
    $connection = db_connect();
    $result = mysqli_query($connection,$query);
    return $result;
}

function db_select($select_query) {
    $result = db_query($select_query);
    if(! $result) {
        return false;
    }

    $rows = array();
    while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function db_error() {
    $connection = db_connect();
    return mysqli_error($connection);
}

function dd($mixed_data) {
    die(var_dump($mixed_data));
}

function get_last_insert_id() {
    $connection = db_connect();
    return mysqli_insert_id($connection);
}

function sanitize($value) {
    $connection = db_connect();
    return trim(mysqli_real_escape_string($connection,$value));
}

function old($collection,$key,$defaultValue="") {
    return trim(isset($collection[$key]) ? $collection[$key] : $defaultValue);
}

function prepare_insert_query($table_name,$data) {
    $values = array_values($data);
    for($i=0;$i<count($values);$i++) {
        $values[$i] = "'".$values[$i]."'";
    }

    $columns = implode(", ",array_keys($data));
    $values = implode(", ",$values);
    $query = "INSERT INTO $table_name($columns) VALUES($values)";
    return $query;
}

// function prepare_update_query($table_name,$data,$id) {
//     $first_name = $data['first_name'];
//     $last_name = $data['last_name'];
//     $birthdate = $data['birthdate'];
//     $telephone = $data['telephone'];
//     $email = $data['email'];
//     $address = $data['address'];
//     $image = $data["image_name"];

//     $query = "UPDATE contacts SET first_name='{$first_name}', last_name='{$last_name}', birthdate='{$birthdate}', telephone='{$telephone}', email='{$email}',  address='{$address}',image_name='{$image}' WHERE id=$id";
//     // $query = "UPDATE contacts SET first_name='{$first_name}', last_name='{$last_name}', birthdate='{$birthdate}', telephone='{$telephone}', email='{$email}',  address='{$address}' WHERE id=$id";
//     // dd($query);
//     return $query;
// }

function prepare_update_query($table_name,$data,$where) {
    // UPDATE table_name SET COl1=values
    $params = "";
    foreach($data as $key=>$value) {
        $params .= "$key = '$value', ";
    }
    $params = rtrim($params,", ");
    $query = "UPDATE $table_name SET $params WHERE $where";
    return $query;
}

function prepare_delete_query($table_name,$where) {
    $query = "DELETE FROM $table_name WHERE $where";
    return $query;
}

function get_image_name($image_name,$id) {
    return strpos($image_name,".") ? $image_name : "$id.$image_name";
}

?>

