<?php

 // cookie = used to store the user preferences , theire behaviour
 // session = used to store sensitive data like password, user token 

 setcookie('educational','maths',time()+86400,'/');
 setcookie('sports','football',time()+86400,'/');
 setcookie('movies','action',time()+86400,'/');

 foreach($_COOKIE as $key =>$values){
    // echo"<br>$key = $values <br>"; 
    if(isset($_COOKIE [$key])){
    echo"You like  " .$values ."<br>";
    }
    }


?>