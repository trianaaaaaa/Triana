<?php
session_start();
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: /admin/dashboard");
    } else {
        header("Location: /user/dashboard");
    }
} else {
    header("Location: /login");
}
exit;
?>
