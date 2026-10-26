<?php
require_once dirname(__DIR__) . '/includes/init.php';
header('Location: ' . url(is_admin() ? 'admin/dashboard.php' : 'admin/login.php'));
exit;