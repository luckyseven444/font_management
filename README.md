# How to run
1. npm start(at project root)
2. cd server
   php -S localhost:8080

3. In chrome - install "Allow CORS:Access-Control-Allow-Origin"
   (https://chromewebstore.google.com/detail/allow-cors-access-control/lhobafahddgcelffkeicbaginigeejlf?hl=en)

4. run sql
   CREATE TABLE `file` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL
    );

    CREATE TABLE `groups` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        fonts VARCHAR(255) NOT NULL,
        count INT NOT NULL
    );
   
