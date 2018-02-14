Installation
------------

Simply create a directory in the 'www' folder of Apache and copy project 
files there.

Then you should create a .conf file in 'sites-available' folder of Apache 
and add to it:

```
<VirtualHost *:80>
	ServerAdmin admin@example.com
	ServerName example.com
	ServerAlias www.example.com
	DocumentRoot /var/www/example.com
	
	<Directory /var/www/example.com>
		Options Indexes FollowSymLinks
		AllowOverride All
	</Directory>
	
	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Then run this command in root directory of the project (to do this, you 
need to install the composer globally):

```
composer install
```

Settings
--------

You need to add the required parameters to the configuration file 
(configs/search_configs.json), and make some settings:

- For Google there are two parameters:
    
    - `api_key` - to get this key, use this link: https://goo.gl/ZPbSQz ,
        click on CET A KEY button, create new project, if you need.
        
        Then you can get your key.
    
    - `custom_search_cx` - to get this cx, go to the Control Panel
        (https://goo.gl/oFcDgm), add new search engine.
        
        In 'Sites to be searched' field you can enter any site you want.
        
        In 'Language' dropdown choose 'All languages'.
        
        Then go to the Control Panel (https://goo.gl/oFcDgm) again, click
        on your search engine.
         
        In 'Sites to be searched' remove sites that you created and change
        'Search only for included sites' option to 'Search the entire 
        internet'.
        
        Then in the 'Details' click on 'Search Engine Identity',
        there you get your cx code.
        
    More info: https://goo.gl/Z6X3Rg .
         
- For Yandex there are two parameters:
    
    - `api_key`;
    - `user_name`.
    
    To get those parameters, go to https://goo.gl/UrUWAZ .
    
    In 'Primary IP Address' field enter your current IP.
    
    In 'Search Type' dropdown choose 'world (yandex.com)'.
    
    Copy your user_name and key from 'Request URL'.
  
  More info: https://goo.gl/rAfah8

**IMPORTANT** For the normal operation of search engines, you need to 
complete all the points listed above!

Also in the configuration file there is a parameter `number_of_results`,
which is responsible for the number of search results.

Used technologies
-----------------

- Apache: 2.4.18;
- php: 7.2.2;
    - php-curl: 7.2.2;
- Bootstrap: 3.3.7;
- jQuery: 3.2.1.



