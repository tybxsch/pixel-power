<?php
require_once '../config.php';

// Destruir todas as sessões
session_destroy();

// Redirecionar para a página inicial
redirect('../index.php');
?> 