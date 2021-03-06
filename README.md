DB backup restore
=================

Use this package to quickly backup and restore your database during test execution.

Key features:

- A backup is created before any tests are executed.
- The database backup process will halt the execution plan if there is an error.
- The backup is restored after all tests have been executed.
- The restore plan will not execute if the backup has failed.

Supported databases:
- mysql
- Feel free to submit a PR, its pretty easy to add support for one.

This project is usually only suitable where the database is small and fairly quick to backup and restore. Using it locally can help when your tests amend the database state while executing and you need the data reset afterwards.

This extension is not yet unit tested so please report any bugs you may find to help the development of this extension. Thanks!

Sample behat.yml file

```yml
default:
  formatters:
        pretty: true
  suites:
    default:
      contexts:
        - FeatureContext
  extensions:
        Genesis\DBBackup\Extension:
            autoBackup: true
            autoRestore: true
            autoRemove: true
            backupPath: ./backups # You'll need to create this folder.
            keepClean: false # Will remove all backups test suite ends, can get rid of lingering files.
            connections:
                mysql:
                    engine: mysql
                    host: 127.0.0.1
                    port: 3307
                    dbname: cms
                    username: cms
                    password: cms
                    schema: 
                    prefix: 
```
