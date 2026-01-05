# journal-cms
# Journal CMS

A lightweight, custom-built blogging platform designed for speed and security.

## Features
* **Dynamic Dashboard:** Manage posts, categories, and users without page reloads using Axios.
* **Secure Authentication:** Password hashing with `bcrypt` and session-based security.
* **Flexible Media:** Supports both local image uploads and external Google Image URLs.
* **Rich Text Editing:** Integrated text customization and emoji support.
* **Responsive Design:** Fully mobile-friendly admin panel and blog feed.

## Tech Stack
* **Backend:** PHP 8.x, MySQL
* **Frontend:** Vanilla JavaScript, Axios, CSS3
* **Editor:** Quill.js / Custom Rich Text

## Installation
1. Clone the repo: `git clone https://github.com/yourusername/journal-cms.git`
2. Move files to your XAMPP `htdocs` folder.
3. Import the `database.sql` file into phpMyAdmin.
4. Update `db.php` with your database credentials.
5. Open `http://localhost/journal-cms` in your browser.
