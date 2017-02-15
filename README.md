**Test task: Boardroom Booker.**
In this task I do not use any 3rd-party libraries. 

1. download
2. Create db for this project
3. Configure web server like this:
 I use this configuration of nginx:
	<pre>
	server {
	    listen 80;
	    #server_name booker.yur www.booker.yur;
	    server_name localhost;

	    fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
	    include        fastcgi_params;
	    error_log /var/log/nginx/vg.error;
	    access_log /var/log/nginx/vg.access;

	    root /home/yura/wwwserver/booker;
	    charset utf-8;
	    client_max_body_size 100m;

	    location / {
		index index.php;
		try_files /$uri /$uri/ @indexhandler;
	    }

	    location @indexhandler {
		rewrite (.*) /index.php?$uri;
	    }
	    location ~ \.php$ {
		fastcgi_pass unix:/run/php/php7.0-fpm.sock;
		break;
	    }
	}
	</pre>

	It also in file "nginxtestconfig", just change line with root   like 
	<pre>
	  root /home/yura/wwwserver/booker;
	</pre>
	to your path;
	also may be you need change server_name.

4. in browser enter localhost/start.php

5. There must be load a simple form, where you need to fill created before DBname, mysql user and password(if it needs).

6. If 5th instruction is successfull then modify connection.php for your dbname and mysql access.

7. Now you can enter localhost and use Boardroom Booker. (by default there only one user(login/password): admin/123).

