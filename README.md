# PHP Server for Edmonton Central Korean Presbyterian Church

PHP server retrieves data from mysql and send back as json response. It serves the church website [Edmonton Central Korean Presbyterian Church](https://edmontoncc.net/#/)

## Following apis are avaialble.

- **apis/event**: event information
- **apis/gallery**: all gallery photos addresses
- **apis/mainsetting**: main setting information for the main page
- **apis/news**: news information
- **apis/sermon**: sermon information with video address
- **apis/sermons**: all sermon videos addressess

## Project structure

- **apis**: each php files serves each routes
- **common**: database.php will connect to the mysql. response.php will make the response into json.
