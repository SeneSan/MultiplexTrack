<?php

if ($_COOKIE['user_role'] == 0 && isset($_POST['login'])) {

    echo $_POST['username'];
}