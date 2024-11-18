<?php
function check_format($userInput) {
    $pattern='/^[A-Za-z0-9_-]{3,20}$/';
    if (preg_match($pattern, $userInput)) { 
        return true;
    }
    echo 'lỗi tên đăng nhập';
    return false;
}

function remove_xss($input)
{
    $pattern='/<.*?>/';
    return preg_replace($pattern,'',$input);
}

function detect_sql($input)
{
    $pattern='/delete|insert|select|--/i';
    if(preg_match($pattern,$input))
    {
        return true;
    }
}
?>