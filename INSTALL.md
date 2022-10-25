PHP:
PHP 8.1.2
sudo apt-get install php-xml
sudo apt get ext-curl
sudo apt-get install php-mysql
sudo apt-get install php-dom

Laravel:
composer create-project --prefer-dist laravel/laravel counter

Mysql:
wget https://dev.mysql.com/get/mysql-apt-config_0.8.12-1_all.deb
sudo dpkg -i mysql-apt-config_0.8.12-1_all.deb
Ubuntu Bionic
MySQL Server & Cluster
mysql-5.7
sudo apt update
sudo apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 467B942D3A79BD29
sudo apt update
sudo apt-cache policy mysql-server
sudo apt install -f mysql-client=5.7* mysql-community-server=5.7* mysql-server=5.7*
sudo mysql_secure_installation
mysql -u root -p
CREATE USER 'user'@'localhost' IDENTIFIED BY 'password';
GRANT CREATE, SELECT ON *.* TO 'user'@'localhost';
CREATE DATABASE laravel;

NPM and Vue.js:
sudo apt install npm
npm install --save vue@next && npm install --save-dev vue-loader@next
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
nvm install 16.0.0
npm i

Laravel Mix:
npm init -y
npm install laravel-mix --save-dev
touch webpack.mix.js

