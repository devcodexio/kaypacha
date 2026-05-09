<?php
// controladores/auth/logout.php

session_start();
session_unset();
session_destroy();

header("Location: /clientes/index.php");
exit;
