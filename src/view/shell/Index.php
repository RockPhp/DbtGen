<?php
echo "\nGerador de ORM DbTools\n";
echo " parametro --keepCase opcional\n";
echo "\nDrivers disponiveis: \n";
echo " - MySql\n";
echo " - Sqlite\n";
echo " - PgSql\n";
echo " - Oci8\n";
echo " - MsSql\n";
echo "\nExemplo de uso:\n\n";
echo "  php dbtgen.php Gera --dbproj NomeProj --host 127.0.0.1 \\ \n";
echo "  --db nome_banco --user root --pass 123 --driver PgSql \\ \n";
echo "  --keppCase true \n\n";
