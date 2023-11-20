<?php
$path = ltrim($_SERVER['REQUEST_URI'], '/');    // Trim leading slash(es)
$elements = explode('/', $path);                // Split path on slashes
if(empty($elements[0])) {                       // No path elements means home
    //ShowHomepage();
} else switch(array_shift($elements))             // Pop off first item and switch
{
    case 'login':
        header("Location: ./framework/login.php");
        break;
    default:
        header('HTTP/1.1 404 Not Found');
        //Show404Error();
}
?>