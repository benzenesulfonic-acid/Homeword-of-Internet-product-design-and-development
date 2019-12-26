<?php
$week_english = date('l');
$week_number2chinese = array(
    1 => "星期一",
    2 => "星期二",
    3 => "星期三",
    4 => "星期四",
    5 => "星期五",
    6 => "星期六",
    0 => "星期日",
);
switch (date('w')) {
    case 1:
        $week_chinese = $week_number2chinese[1];
        break;
    case 2:
        $week_chinese = $week_number2chinese[2];
        break;
    case 3:
        $week_chinese = $week_number2chinese[3];
        break;
    case 4:
        $week_chinese = $week_number2chinese[4];
        break;
    case 5:
        $week_chinese = $week_number2chinese[5];
        break;
    case 6:
        $week_chinese = $week_number2chinese[6];
        break;
    case 0:
        $week_chinese = $week_number2chinese[0];
        break;
}
?>

    <!DOCTYPE html>
    <html>
        <title>看星期</title>
    <head>

    </head>
    <body>
        <p>看看今天星期几</p>
        <p><span>english: </span><span><?php print($week_english); ?></span></p>
        <p><span>汉语: </span><span><?php print($week_chinese); ?></span></p>
    </body>
    </html>