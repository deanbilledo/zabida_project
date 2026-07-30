
<?php
require __DIR__ . '/../config/auth.php';

logout();

// Redirect to login.php within the admin folder
header('Location: login.php');
exit;