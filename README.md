ToDoList
========

This project is a ToDoList application built with **Symfony 3.4**. It's the 8th [OpenClassRooms][1] PHP/Symfony Developer project which aims to **improve an [existing project][2]**. A working demo can be found [here][3].

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f8298a5cef654259b15c9e8abc6b14bf)](https://www.codacy.com/app/bhalexx/todo-and-co?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=bhalexx/todo-and-co&amp;utm_campaign=Badge_Grade)
[![Maintainability](https://api.codeclimate.com/v1/badges/27589e36ac087f3c6f98/maintainability)](https://codeclimate.com/github/bhalexx/todo-and-co/maintainability)
[![Build Status](https://travis-ci.org/bhalexx/todo-and-co.svg?branch=master)](https://travis-ci.org/bhalexx/todo-and-co)

---

Instructions
-------------------

### Prerequisites
- PHP >=5.5.9
- MySQL
- PHPUnit 6.5
- [Composer][4] to install Symfony 3.4 and project dependencies

### Dependencies
This project uses [PHPUnit][5] for unit and functional tests. This dependency is included in composer.json.

This project also uses:
- [Bootstrap 4][6] - CSS framework
- [Font Awesome 5][7] - beautiful icons
- [jQuery][8] - javascript frameword
- [Isotope][9] - for filtering tasks

Those dependencies are included from CDN in layout and concerned views.

### Installation
1. Clone this repository on your local machine by using this command line in your folder `git clone https://github.com/bhalexx/todo-and-co.git`.
2. In project folder open a new terminal window and execute command line `composer install`.
3. Rename `todo-and-co/app/config/parameters.yml.dist` in `todo-and-co/app/config/parameters.dist` and edit database parameters with yours.
4. Execute command line `php bin/console todolist:fixtures:load`. This command will create database and load some fixtures.
5. Your project is now up to date!

### Code quality and performances

#### Quality & maintainability
[Codacy][10] and [CodeClimate][11] are used to check code quality on each pull request.

#### Tests
Continuous integration has been implemented with [Travis CI][12].

To run tests manually you only have run this command: `vendor/bin/phpunit.bat`.

A custom bootstrap process file has been created to drop and recreate test database. Read [this][13] for more informations.

#### Code coverage
Coverage files can be found in [`audit/coverage`][14] folder.

#### Audit
*Some words about application performances...*

### Documentation
In [`documentation`][15] folder you can find:
- a [technical documentation][16] about *authentication*,
- a [full review][17] of *what have been done* on this project.

### Contribution
To contribute to this project read [How to contribute instructions][18].

---

Thanks for reading!

[1]: https://openclassrooms.com
[2]: https://github.com/saro0h/projet8-TodoList
[3]: #
[4]: https://getcomposer.org
[5]: https://phpunit.de
[6]: https://getbootstrap.com
[7]: https://fontawesome.com
[8]: https://jquery.com
[9]: https://isotope.metafizzy.co
[10]: https://codacy.com
[11]: https://codeclimate.com
[12]: https://travis-ci.org
[13]: https://symfony.com/doc/3.4/testing/bootstrap.html
[14]: https://github.com/bhalexx/todo-and-co/tree/master/audit/coverage
[15]: https://github.com/bhalexx/todo-and-co/tree/master/documentation
[16]: https://github.com/bhalexx/todo-and-co/tree/master/documentation/Authentication.md
[17]: https://github.com/bhalexx/todo-and-co/tree/master/documentation/WhatHaveBeenDone.md
[18]: https://github.com/bhalexx/todo-and-co/tree/master/CONTRIBUTING.md

