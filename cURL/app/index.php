<?php
// Get the User-Agent header
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';


$flag_file = '/secret/flag.txt';

if (preg_match('/^curl\/\d+\.\d+\.\d+$/', $user_agent)) {
    if (file_exists($flag_file)) {
        $flag = file_get_contents($flag_file);
        echo $flag;
    } else {
        echo "Flag file missing!\n";
    }
} else {
    echo "I only talk to curl-y agents. Try a different approach!\n";
}
?>
