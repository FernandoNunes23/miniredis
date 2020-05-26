<?php

$arrayRetorno = [];

/** COMMAND AUTH */
echo "Autenticando usuário (AUTH) \n";
exec("php /var/www/src/Application/Console/application.php app:auth test test", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n";
unset($arrayRetorno);

/** COMMAND SET */
echo "Criando um novo conjunto (SET) test1 testSet \n";
exec("php /var/www/src/Application/Console/application.php app:set test1 testSet", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n";
unset($arrayRetorno);

/** COMMAND GET */
echo "Buscando conjunto criado (GET) test1 \n";
exec("php /var/www/src/Application/Console/application.php app:get test1", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n";
unset($arrayRetorno);

/** COMMAND DEL */
echo "Deletando conjunto criado (DEL) test1 \n";
exec("php /var/www/src/Application/Console/application.php app:del test1", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n";
unset($arrayRetorno);

/** COMMAND DEL MULTIPLE */
echo "Criando conjuntos (SET) test1 testSet (SET) test2 testSet (SET) test3 testSet\n";
exec("php /var/www/src/Application/Console/application.php app:set test1 testSet");
exec("php /var/www/src/Application/Console/application.php app:set test2 testSet");
exec("php /var/www/src/Application/Console/application.php app:set test3 testSet");

echo "Deletando conjuntos criados (DEL) test1 test2 test3 \n";
exec("php /var/www/src/Application/Console/application.php app:del test1 test2 test3", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n";
unset($arrayRetorno);

/** COMMAND INCR */
echo "Criando um novo conjunto (SET) test1 1 \n";
exec("php /var/www/src/Application/Console/application.php app:set test1 1");

echo "Incrementando conjunto (INCR) test1  \n";
exec("php /var/www/src/Application/Console/application.php app:incr test1", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n";
unset($arrayRetorno);

/** COMMAND ZADD */
echo "Adicionando membros ao conjunto (ZADD) test10 0 test1 1 test2 2 test3  \n";
exec("php /var/www/src/Application/Console/application.php app:zadd test10 0 test1 1 test2 2 test3", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n";
unset($arrayRetorno);

/** COMMAND ZCARD */
echo "Mostrando o numero de membros no conjunto (ZCARD) test10 \n";
exec("php /var/www/src/Application/Console/application.php app:zcard test10", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n";
unset($arrayRetorno);

/** COMMAND ZRANK */
echo "Mostrando o rank do membro no conjunto (ZRANK) test10 test2\n";
exec("php /var/www/src/Application/Console/application.php app:zrank test10 test2", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n";
unset($arrayRetorno);

/** COMMAND ZRANGE */