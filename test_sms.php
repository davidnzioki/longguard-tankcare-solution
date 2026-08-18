<?php
require_once 'includes/sms.php';
$result = sms_send('0140021361', 'Test from Lonnguard!');
echo $result['success'] ? 'SUCCESS!' : 'FAILED: ' . $result['message'];
?>