DB backup restore
=================

Use this package to quickly backup and restore your database during test execution.

Key features:

- A backup is created before any tests are executed.
-- The database backup process will halt the execution plan if there is an error.

- The backup is restored after all tests have been executed.
-- The restore plan will not execute if the backup has failed.

Supported databases:
- mysql

This project is usually only suitable where the database is small and fairly quick to backup and restore. Using it locally can help when your tests amend the database state while executing and you need the data reset afterwards.

This extension is not yet unit tested so please report any bugs you may find to help the development of this extension. Thanks!
