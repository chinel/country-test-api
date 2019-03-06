Please to run this application <br/><br/>
First you have to clone it into your htdocs folder or www folder, no need to install any dependency<br/>
Ensure you have xampp or which ever one you are using on your system<br/>
Create  a database with the same name as the database name in the.env file<br/>
Open the root folder of the application in your command prompt and type<br/>
php -S localhost:9099 -t public to run it, reason being that the api documentation points to this port number and also incase you are using port 8080<br/>
Then run<br/>
 php artisan migrate<br/>
 to import the table to the database you created.<br/>
 To get the api documentation of the application which was done using swagger
 <br/>
 Use this url on your browser
 
 http://localhost/laravel_jwtnew/public/api/documentation<br/>
 
 where laravel_jwtnew is the name of the project folder as long as you have not changed the folder name after you have cloned the project<br>
 
 Please use the api documentation as a guide and test using postman.<br/>
 I was not able to find out why i couldn't run the test directly in the api documentation page because of time
 
 <br/>
 Thanks
 

