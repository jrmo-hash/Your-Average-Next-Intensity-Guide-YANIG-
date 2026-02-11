<?php
session_start();
session_destroy(); // Burahin lahat ng login data
header("Location: index.html"); // Balik sa login page
exit();
?>