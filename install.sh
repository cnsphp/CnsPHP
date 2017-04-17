#!/bin/bash

#install CnsPHP
wget -O CnsPHP.zip https://github.com/cnsphp/CnsPHP/archive/master.zip
unzip CnsPHP.zip 
CnsPHP-master CnsPHP

cd CnsPHP

#install smarty
wget -O smarty.tar.gz "https://github.com/smarty-php/smarty/archive/v3.1.30.tar.gz"
smartyname=$(tar xvf smarty.tar.gz  |tail -1 |  awk -F / '{print $1}')
mv $smartyname/libs  Smarty
rm -fr $smartyname smarty.tar.gz

nginxuser=$(ps -ef |grep "nginx: worker process" | grep -v "grep"|awk '{print $1}')
chown -R $nginxuser tpl/config
chown -R $nginxuser tpl/cache
chown -R $nginxuser tpl/compile
