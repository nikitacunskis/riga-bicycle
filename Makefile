up: download-php-extensions getproject final-instruction

download-php-extensions:
	@sudo apt install php-xml
	@sudo apt install php-curl
	@sudo apt install php-mysql
	@sudo apt install php-dom

getproject:
	@mkdir counter
	@git clone https://github.com/nikitacunskis/riga-bicycle.git ./counter
	@cp ./counter/.env.example ./counter/.env

mysql:
	@wget https://dev.mysql.com/get/mysql-apt-config_0.8.12-1_all.deb
	@sudo dpkg -i mysql-apt-config_0.8.12-1_all.deb
	@sudo apt update
	@sudo apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 467B942D3A79BD29
	@sudo apt update
	@sudo apt-cache policy mysql-server
	@sudo apt install -f mysql-client=5.7* mysql-community-server=5.7* mysql-server=5.7*
	@sudo mysql_secure_installation
	@sudo rm mysql-apt-config_0.8.12-1_all.deb

npm:
	@cd counter
	@sudo apt install npm
	@npm install --save vue@next && npm install --save-dev vue-loader@next
	@curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
	@nvm install 16.0.0
	@npm i

final-instruction:
	@echo
	@echo
	@echo
	@echo "--------------------------------------"
	@echo "|        Instalēšana pabeigta        |"
	@echo "| - make mysql                       |"
	@echo "| - Jāizveido datubāzi               |"
	@echo "| - Jārediģē .env ar DB datiem       |"
	@echo "| - Uzstādīt SSH un FTP              |"
	@echo "| - cd counter, make npm             |"
	@echo "| - composer install                 |"
	@echo "| - php artisan serve                |"
	@echo "| - npm run dev                      |"
	@echo "| - php artisan migrate              |"
	@echo "| - php artisan seed_weather_history |"
	@echo "| - php artisan make:su              |"
	@echo "--------------------------------------"

