# Database Backup Instructions (Movie Mayhem Secured)

## Create a backup with mysqldump

From the command line (replace `USER` with your MySQL username):

```bash
mysqldump -u USER -p movie_mayhem > backup.sql
```

To include the database creation:

```bash
mysqldump -u USER -p --databases movie_mayhem > backup.sql
```

## Restore from backup

```bash
mysql -u USER -p < backup.sql
```

## Recommended

- Run backups before major changes.
- Store backups in a secure location (not in the web root).
- Consider automated daily backups (cron/scheduled task).
