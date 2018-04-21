What have been done
-------------------

The To Do List app [previous version][1] had some anomalies and was missing important features.

Read below the full review of what have been done to improve this project.

---

#### **Anomaly #1**

*Previously*

Tasks were not linked to users.

*Now*

Tasks are linked to current user on creation.

Older tasks without author may be updated with the `php bin/console todolist:oldtasks:update` command.

#### **Anomaly #2**

*Previously*

We were not able to choose a role for user on creation.

*Now*

Two roles have been implemented and may now be assigned to user on creation:

- ROLE_USER
- ROLE_ADMIN

ROLE_USER is user default role on creation but role may be changed on update.

Changed `Form\UserType` form type by adding `roles` field.


#### **New feature #1**

*Previously*

All users were allowed to access users management area.

*Now*

Users management area is restricted to admin users (ROLE_ADMIN).

Only authors may delete their own tasks.

Tasks with anonymous author may only be deleted by admin users (ROLE_ADMIN).

#### Automated tests

*Previously*

No test done.

*Now*

Unit and functional tests have been done.

Code coverage :

Coverage | Lines | Functions and Methods | Classes and Traits
-- | -- | -- | --
Command | 98.81 (83/84) | 92.86 (13/14) | 50% (1/2)
Controller | 96.97% (64/66) | 84.62% (11/13) | 75% (3/4)
DoctrineListener | 100.00% (11/11) | 100.00% (5/5) | 100.00% (1/1)
Entity | 100.00% (46/46) | 100.00% (29/29) | 100.00% (2/2)
Form | 100.00% (15/15) | 100.00% (4/4) | 100.00% (2/2)
Security |  83.33% (15/18) | 60.00% (3/5) | 0.00% (0/1)
Service | 100.00% (6/6) | 100.00% (2/2) | 100.00% (1/1)
**Total** | **97.56% (240/246)** |  **93.06% (67/72)** | **76.92% (10/13)**

See also [here][2] to read more informations about code coverage.

Continuous integration has been implemented with [Travis CI][13].

A command was created to load fixtures to the application for tests but this command is also available in dev mode: `php bin/console todolist:fixtures:load`.

#### Audit
Application performances were analyzed with [BlackFire][3] tool.

Performances graphs and full code audit can be found in [this documentation][4].

#### Documentation

##### UML
UML diagrams have been created and can be found in [`documentation\diagrams`][14] folder.

##### How to contribute
A [full guide for contribution][5] has been written to help future collaborators.

##### Authentication
A [technical documentation][6] has been written about authentication. This documentation will be useful for the next junior developers who will join the team.

In this documentation, it is possible for a beginner with the Symfony framework to understand:

- which file(s) to edit and why
- how authentication works
- and where are the users stored.


#### Various other improvements
While working on this application some other various things have been improved:

##### Design & UX

- French labels have been added on task form.
- Improved application's design and ergonomy.
- Changed [OpenClassRooms][7] logo with the new one.
- Improved workflow (by adding breadcrumb, links, ...).
- Logged in user name is now displayed.
- Improved task flag state.
- Created Twig service to display date in french human format.
- Customized error pages (404, 500).

##### Features

- Created `Security\TaskVoter` for task edition and deletion.
- Task edition: only authors may edit their own tasks.
- Implemented JS library [Isotope][8] to display done tasks (missing feature), but also to filter current user tasks.
- Fixed toggling task flag feedback (previously: same feedback for done and undone tasks on toggling).

##### Assets

- Fixed the two 404 errors in console.
- Changed CSS file name from uncoherent `shop-homepage.css` to `main.css`.
- Removed non used anymore `web\img\todolist_homepage.jpg`.
- Renamed `web\img\todolist_content.jpg` to `web\img\todolist.jpg`.
- Upgraded Bootstrap 3.3.7 to use new [Bootstrap 4.0.0][9] release.
- Removed old glyphicons files since Bootstrap 4 does not include fonts anymore. [FontAwesome 5.0.9][10] is now used to display icons.
- Front libraries are now included by CDN.
  - [Bootstrap][9]
  - [jQuery][11]
  - [FontAwesome][10]
  - [Isotope][8]


##### Cleaning and refactoring

- Changed authorization security for users management: not anymore in `config\security.yml` `access_control` parameter but with the `@Security` annotation on `Controller\UserController` (as mentionned in [Symfony security best practices][12]).
- In `Form\UserType` roles are retrieved dynamically from `security.role_hierarchy.roles` (by a service to inject properly dependency). That way we still have to edit only one file when we want to edit roles.
- User password encoding has been moved from `Controller\UserController` to `DoctrineListener\UserSubscriber`.
- User deletion method was created in `Controller\UserController`.
- User unicity constraints on username and email were changed in `Entity\User` entity.
- [PHP-CS][15] tool was used to fix code and follow standards.

---

Thanks for reading!

[1]: https://github.com/saro0h/projet8-TodoList
[2]: https://github.com/bhalexx/todo-and-co/tree/master/documentation/coverage
[3]: https://blackfire.io
[4]: https://github.com/bhalexx/todo-and-co/tree/master/documentation/Audit.pdf
[5]: https://github.com/bhalexx/todo-and-co/blob/master/CONTRIBUTING.md
[6]: https://github.com/bhalexx/todo-and-co/tree/master/documentation/Authentication.md
[7]: https://openclassrooms.com
[8]: https://isotope.metafizzy.co
[9]: https://getbootstrap.com/docs/4.0/getting-started/introduction
[10]: https://fontawesome.com
[11]: https://jquery.com
[12]: https://symfony.com/doc/3.4/best_practices/security.html#authorization-i-e-denying-access
[13]: https://travis-ci.org
[14]: https://github.com/bhalexx/todo-and-co/tree/master/documentation/diagrams
[15]: https://cs.sensiolabs.org