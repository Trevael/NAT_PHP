<?php

$config['atk']['base_path']='./atk4/';
//$config['dsn']='mysql://root:root@localhost/example';

$config['url_postfix']='';
$config['url_prefix']='?page=';

# Agile Toolkit attempts to use as many default values for config file,
# and you only need to add them here if you wish to re-define default
# values. For more options look at:
#
#  http://www.atk4.com/doc/config
$config['atk4']['name']='Game Librarian';

//$config['SierraBravo']['xboxV2']['GameLibrarian']['url']='http://xbox.sierrabravo.net/v2/xbox.wsdl';
$config['SierraBravo']['xboxV2']['GameLibrarian']['url']='http://xbox.sierrabravo.net/v2/xbox.php?wsdl';
$config['SierraBravo']['xboxV2']['GameLibrarian']['key']='7bb7aec6151ecf60370c50c2306efb86';
$config['SierraBravo']['xboxV2']['GameLibrarian']['debug']=false;
