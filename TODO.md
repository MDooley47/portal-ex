# TODO

This is a TODO list for portal-ex.

- [ ] Create App Module
    - [x] Add indexAction()
        - URL/app will display all the apps on an app launcher grid.
    - [x] Add addAction()
    - [x] Add iconAction()
        - URL/app/icon/:id will display the icon for the app using XSendFile.
    - [ ] Add editAction()
    - [ ] Add deleteAction()
    - [ ] Add Documentation/Comments
    - [ ] Add randomized slug support

## Documentation

- ModuleRouteListener is removed from the skeleton. This won't affect existing
  users, but *will* affect experienced users who originally relied on it being
  active in new skeleton projects.
- The `/[:controller][/:action]]` route was removed from the skeleton. Again, it
  will not affect existing users, but *will* affect experienced users who
  originally relied on it being active in new skeleton projects.
