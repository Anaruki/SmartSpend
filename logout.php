<?php

session_start();

session_destroy();

header("Location: smartspend_page.php");
exit;