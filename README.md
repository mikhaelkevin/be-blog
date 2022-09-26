<div id="top"></div>

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <h3 align="center">Alba Technology - Blog</h3>

  <p align="center">
    <a href="https://github.com/mikhaelkevin/be-blog/issues">Report Bug</a>
    ·
    <a href="https://github.com/mikhaelkevin/be-blog/issues">Request Feature</a>
  </p>
</div>

<!-- TABLE OF CONTENTS -->

## Table of Contents

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
        <li><a href="#setup-env-example">Setup .env example</a></li>
      </ul>
    </li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#related-project">Related Project</a></li>
    <li><a href="#license">License</a></li>
  </ol>
</details>

<!-- ABOUT THE PROJECT -->

## About The Project

This project have a goals which helps people to share what they want, without any limit of categories. User can share anything in this blog, following: Technology, Culinary, Viral/Hype thing, etc.

### Built With

This app was built with some technologies below:

-   [PHP](https://www.php.net/downloads.php)
-   [Lumen V.9](https://lumen.laravel.com/docs/9.x)
-   [PostgreSQL](https://www.postgresql.org/)

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- GETTING STARTED -->

## Getting Started

### Prerequisites

Before going to the installation stage there are some software that must be installed first.

-   [PHP](https://www.php.net/downloads.php)
-   [PostgreSQL](https://www.postgresql.org/)
-   [Postman](https://www.postman.com/downloads/)

<p align="right">(<a href="#top">back to top</a>)</p>

### Installation

If you want to run this project locally, I recommend you to configure the <a href="#setup-env">.env</a> first before configuring this repo front-end.

-   Clone the repo

```
git clone https://github.com/mikhaelkevin/be-blog.git
```

-   Go To Folder Repo

```
cd be-blog
```

-   Install Module

```
composer update
```

Before you start the backend, make sure check the application db backup and find a file named <b>db_blog_alba_technology.psql</b>.

Follow the other step bellow to continue settings up the application.

-   Open CLI
-   Get in to your database

```
psql postgres postgres
```

<i>Note: you can use your own way to get in to psql</i>

-   Create new database

```
create database db_blog_alba_technology
```

-   Import database

```
psql -U postgres -p 5432 -h localhost -d db_blog_alba_technology -f db_blog_alba_technology.psql
```

<i>Note: you can use your own way to import the database</i>

-   Import our [API Documentation](https://documenter.getpostman.com/view/22931710/2s83S88WTL) on Postman (Don't forget to setup the baseurl on postman environtments > Globals > variable : baseURL, initialvalue and currentvalue : http://localhost:8000)
-   After all the step is done you ready to go
-   Open the backend folder with your IDE
-   Open the IDE teriminal and run below command

```
php artisan serve
```

<i>Note: since lumen doesn't come with artisan command, make sure you running composer update before launch this command.</i>

<p align="right">(<a href="#top">back to top</a>)</p>

### Setup .env example

Rename file called .env.example to .env and change exampl value to your own configs.

```
APP_NAME=Lumen
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_alba_technology
DB_USERNAME=#####
DB_PASSWORD=#####

CACHE_DRIVER=file
QUEUE_CONNECTION=sync

CLOUDINARY_API_KEY=#####
CLOUDINARY_API_SECRET=#####
CLOUDINARY_CLOUD_NAME=#####

JWT_SECRET=#####
```

<p align="right">(<a href="#top">back to top</a>)</p>

## Contributing

Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b someFeature-features`)
3. Commit your Changes (`git commit -m 'add(someFeature): what kind of feature'`)
4. Push to the Branch (`git push origin someFeature-features`)
5. Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>

## Related Project

<center>
<table> 
    <tr>
    <th>Backend</th>
    <th>Frontend</th>
    <th>API Collection</th>
    </tr>
    <tr>
    <td>
    <a href="https://github.com/mikhaelkevin/be-blog"> 
    <img src="https://img.shields.io/badge/github-%23121011.svg?style=for-the-badge&logo=github&logoColor=white" alt="be-github"/>
    </a>
    </td>
    <td> 
    <a href="https://github.com/aldoBangun/joshy-app/tree/main"> 
    <img src="https://img.shields.io/badge/github-%23121011.svg?style=for-the-badge&logo=github&logoColor=white" alt="fe-github">
    <a/>
    </td>
    <td> 
    <a href="https://documenter.getpostman.com/view/22931710/2s83S88WTL"> 
    <img src="https://img.shields.io/badge/Postman-FF6C37?style=for-the-badge&logo=postman&logoColor=white" alt="postman">
    <a/>
    </td>
    </tr>
</table>
</center>

<p align="right">(<a href="#top">back to top</a>)</p>

## License

Distributed under the [MIT](/LICENSE) License.

<p align="right">(<a href="#top">back to top</a>)</p>
