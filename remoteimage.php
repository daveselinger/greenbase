<?php
$handle = fopen('http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/The_Weather_Channel_logo_2005-present.svg/500px-The_Weather_Channel_logo_2005-present.svg.png', 'rb');
$img = new Imagick();
$img->readImageFile($handle);
$img->thumbnailImage(100, 0);
header('Content-type: image/png');
echo $img;
?>
