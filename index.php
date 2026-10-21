<?php
/**
 * Mutases - CodeIgniter 4 Root Front Controller Bridge
 * Allows running from both root and public/ folder.
 */

// If accessed directly at root, forward to public/index.php
require __DIR__ . '/public/index.php';
