<?php
if (! defined('IN_PHPBB'))
    define('IN_PHPBB', true);
    // absoulute physical path of the phpbb 3 forum
$phpbb_root_path = $_SERVER['DOCUMENT_ROOT'] . "foros";
$phpEx = "php"; // phpbb used extensions
require_once ($phpbb_root_path . "common." . $phpEx);
echo ($phpbb_root_path);
// Start session management
$user->session_begin();
// End session management
?>