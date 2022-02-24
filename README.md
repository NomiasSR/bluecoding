Instructions for configuration the system:
Pre-requisites:
Composer (latest version);
Mysql 5.7 or superior;

1 - Create a folder named BLUECODING;

* - Clone the project from url into the project folder: 
    https://github.com/NomiasSR/bluecoding

* - After that, go to the project folder and install laravel's dependencies: 
    composer install;

* - Run also the commands:
    cp .env.example .env
	  php artisan key:generate --ansi;
	  php artisan cache:clear;
	  php artisan config:clear;
	  composer dump-autoload;    

* - Rename the .env_default to .env;

* - In the file 'app\Models\UrlShorteners.php', change the location of your PEM file, in order
    to run Guzzle Http Client to crawling the websites saved on your database:

    On line 34, change 'YOUR_DIRECTORY' for the driver where your application is located:
    Ex.: $client = new \GuzzleHttp\Client(['verify' => 'YOUR_DIRECTORY/bluecoding/backend/cacert.pem']);

* - Execute php artisan migrate:fresh from command prompt in order to create all your database;


After all configurations, go to the command prompt on project folder and run the command 
to start the server:
php artisan serve


To run the crawler job, execute the following command:
php artisan schedule:work


To add new sites, run the command from your preferable API tool:
http://127.0.0.1:8000/api/urlshortener/createcode
with the parameter as form-data:
KEY: url       VALUE: a valid url address


To list the top100 sites of the application, run the command from your preferable API tool:
http://127.0.0.1:8000/api/urlshortener/top100


For testing url shortener, run the command from url browser:
http://localhost:8000/YOUR_SHORT_CODE

