<?php
require_once dirname(__DIR__) . '/includes/init.php';
$_SESSION = [];
session_destroy();
header('Location: ' . url('admin/login.php'));
exit;