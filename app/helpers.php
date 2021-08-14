<?php
function gravatar_url($email)
{
    $email=md5($email);
    return "https://gravatar.com/avatar/{$email}".http_build_Query([
        's'=>60,
        'd'=>'https://d31i9b8skgubvn.cloudfront.net/assets/img/hr-tech-conf/speakers/emmanuel.png'
    ] );
}
