<?php
session_start();
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()+=+,-./';
$rand_num = substr(str_shuffle($chars), 0, 6);

$_SESSION['CODE'] = $rand_num;
$layer = imagecreatetruecolor(90, 30);
$captcha_bg = imagecolorallocate($layer, rand(0, 255), rand(0, 255), rand(0, 255));
imagefill($layer, 0, 0, $captcha_bg);
$captcha_text_color = imagecolorallocate($layer, 0, 0, 0);
imagestring($layer, 5, 5, 5, $rand_num, $captcha_text_color);
header('Content-Type:image/jpeg');
imagejpeg($layer);

