<?php
require __DIR__ . '/../config/auth.php';
logout();
header('Location: /admin/login.php');
exit;
