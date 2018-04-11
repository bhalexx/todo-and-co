# How to contribute

TodoList App is a collaborative project. Follow those steps to help and contribute properly to this project.

## Requirements
First of all be sure your environment:

- [Git][1]
- [PHPunit][2]

## Instructions
### 1. Fork this repository
A *fork* is a copy of a repository. Forking a repository lets you to make changes to your copy without affecting any of the original code.

Fork master branch of this project to your GitHub account by clicking on the **Fork** button in the top-right corner of this repo.

### 2. Clone your fork
A *clone* is a downloaded version of a repository. Cloning our fork lets you download a copy of the repository to your computer.

Clone the fork on your locale machine by running this command line: `git clone https://github.com/bhalexx/todo-and-co.git`. Your remote repo on GitHub is called origin.

In your new `todo-and-co` folder add the original repository as a remote called upstream by running this command: `git remote add upstream https://github.com/bhalexx/todo-and-co.git`. Now your local project has 2 remotes:

- **origin**: points to your GitHub fork of the project. You are able to read and write to this remote.
- **upstream**: points to main project repository. You're only able to read this remote.


**Be careful!** If you created your fork a while ago be sure to pull upstream changes into your local repository with this command: `git pull upstream master && git push origin master`.


### 3. Install project
Refer to [README.md][3] to correctly install application.

### 4. Work on project

#### Create a new branch
Create a new branch to work on by running this command: `git checkout -b [prefix]/[name]`

Use our conventional prefixes according to your contribution:

- `hotfix/` : for updates, fixes
- `feature/` : for new features

#### Follow code styles
Please make sure your code respects:

- [PHP Standards Recommendations (PSR)][4]
  - [PSR-1][5]
  - [PSR-2][6]
  - [PSR-4][7]
- [Symfony Coding Standards][8]
- [Symfony Best Practices][9]

#### Tests
This project has unit and functional tests so run them regularly during your dev to check everything is still fine.

Implement your own tests. Attention to not decrease code coverage! You can find code coverage in [`audit/coverage`][10] folder.


Useful documentation:

- [PHPUnit documentation][11]
- [Symfony Testing][12]

### 5. Create a pull request
Push your work and branch to your fork on GitHub (remote origin) by running this command: `git push origin [branch]`

Go to your fork on your GitHub account to open a pull request. Provide clear title and concise explanations about your work in your pull request description. Add any issue reference in case of.

Now you're sure about your work you can click on "Create pull request" and target project `dev` branch.

Travis CI will automatically run tests and code will be checked by tools such as Codacy and CodeClimate. Your code must succeed on Travis to have a chance to be merged.
Now you just have to wait our review before merging your work.

## Useful resources

- [GitHub help][14] - GitHub full documentation
- [Try Git][15] - Learn Git in 15 minutes from within your browser for free
- [GitHub Git CheatSheet][16] - PDF

Feel free to contact me in case of need: [@bhalexx][13]

Thanks for reading and contributing!

[1]: https://git-scm.com/downloads
[2]: https://phpunit.de
[3]: https://github.com/bhalexx/todo-and-co/blob/master/README.md
[4]: https://www.php-fig.org/psr
[5]: https://www.php-fig.org/psr/psr-1
[6]: https://www.php-fig.org/psr/psr-2
[7]: https://www.php-fig.org/psr/psr-4
[8]: https://symfony.com/doc/3.4/contributing/code/standards.html
[9]: https://symfony.com/doc/3.4/best_practices/index.html
[10]: https://github.com/bhalexx/todo-and-co/tree/master/audit/coverage
[11]: https://phpunit.de/documentation.html
[12]: https://symfony.com/doc/3.4/testing.html
[13]: https://github.com/bhalexx
[14]: https://help.github.com
[15]: https://try.github.io
[16]: https://education.github.com/git-cheat-sheet-education.pdf