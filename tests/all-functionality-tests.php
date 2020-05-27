<?php

echo "**** Iniciando testes. ****\n\n";

$arrayRetorno = [];

/** COMMAND AUTH */
echo "**** Command AUTH. **** \n";
echo "Autenticando usuário (AUTH) test test \n";
exec("php /var/www/src/Application/Console/application.php app:auth test test", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n\n";
unset($arrayRetorno);

/** COMMAND SET */
echo "**** Command SET. **** \n";
echo "Criando um novo conjunto (SET) test1 testSet \n";
exec("php /var/www/src/Application/Console/application.php app:set test1 testSet", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n\n";
unset($arrayRetorno);

/** COMMAND GET */
echo "**** Command GET. **** \n";
echo "Buscando conjunto criado (GET) test1 \n";
exec("php /var/www/src/Application/Console/application.php app:get test1", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n\n";
unset($arrayRetorno);

/** COMMAND DEL */
echo "**** Command DEL. **** \n";
echo "Deletando conjunto criado (DEL) test1 \n";
exec("php /var/www/src/Application/Console/application.php app:del test1", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n\n";
unset($arrayRetorno);

/** COMMAND DEL MULTIPLE */
echo "**** Command DEL MULTIPLE. **** \n";
echo "Criando conjuntos (SET) test1 testSet (SET) test2 testSet (SET) test3 testSet\n";
exec("php /var/www/src/Application/Console/application.php app:set test1 testSet");
exec("php /var/www/src/Application/Console/application.php app:set test2 testSet");
exec("php /var/www/src/Application/Console/application.php app:set test3 testSet");

echo "Deletando conjuntos criados (DEL) test1 test2 test3 \n";
exec("php /var/www/src/Application/Console/application.php app:del test1 test2 test3", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n\n";
unset($arrayRetorno);

/** COMMAND INCR */
echo "**** Command INCR. **** \n";
echo "Criando um novo conjunto (SET) test1 1 \n";
exec("php /var/www/src/Application/Console/application.php app:set test1 1");

echo "Incrementando conjunto (INCR) test1  \n";
exec("php /var/www/src/Application/Console/application.php app:incr test1", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n\n";
unset($arrayRetorno);

/** COMMAND ZADD */
echo "**** Command ZADD. **** \n";
echo "Adicionando membros ao conjunto (ZADD) test10 0 test1 1 test2 2 test3  \n";
exec("php /var/www/src/Application/Console/application.php app:zadd test10 0 test1 1 test2 2 test3", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n\n";
unset($arrayRetorno);
echo "Deletando conjunto criado (DEL) test10 \n";
exec("php /var/www/src/Application/Console/application.php app:del test10");

/** COMMAND ZCARD */
echo "**** Command ZCARD. **** \n";
echo "Adicionando membros ao conjunto (ZADD) test10 0 test1 1 test2 2 test3  \n";
exec("php /var/www/src/Application/Console/application.php app:zadd test10 0 test1 1 test2 2 test3");
echo "Mostrando o numero de membros no conjunto (ZCARD) test10 \n";
exec("php /var/www/src/Application/Console/application.php app:zcard test10", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n\n";
unset($arrayRetorno);
echo "Deletando conjunto criado (DEL) test10 \n";
exec("php /var/www/src/Application/Console/application.php app:del test10");

/** COMMAND ZRANK */
echo "**** Command ZRANK. **** \n";
echo "Adicionando membros ao conjunto (ZADD) test10 0 test1 1 test2 2 test3  \n";
exec("php /var/www/src/Application/Console/application.php app:zadd test10 0 test1 1 test2 2 test3");
echo "Mostrando o rank do membro no conjunto (ZRANK) test10 test2\n";
exec("php /var/www/src/Application/Console/application.php app:zrank test10 test2", $arrayRetorno);
echo "Retorno {$arrayRetorno[0]} \n\n";
unset($arrayRetorno);
echo "Deletando conjunto criado (DEL) test10 \n";
exec("php /var/www/src/Application/Console/application.php app:del test10");

/** COMMAND ZRANGE */
echo "**** Command ZRANGE. **** \n";
echo "Adicionando membros ao conjunto (ZADD) test10 0 test1 1 test2 2 test3  \n";
exec("php /var/www/src/Application/Console/application.php app:zadd test10 0 test1 1 test2 2 test3");
echo "Buscando membros do conjunto (ZRANGE) test10 0 2  \n";
exec("php /var/www/src/Application/Console/application.php app:zrange test10 0 2", $arrayRetorno);
echo "Retorno \n";
foreach ($arrayRetorno as $linha) {
    echo $linha . "\n";
}
unset($arrayRetorno);

echo "Deletando conjunto criado (DEL) test10 \n";
exec("php /var/www/src/Application/Console/application.php app:del test10");

echo "\n**** Testes Finalizados ****\n";