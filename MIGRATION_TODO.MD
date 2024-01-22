# Migration todo's to replace old mensa system.
Rename 'lidnummer' to 'membership_number' in the database.

Add SequelizeMeta table to the database with the rows of migrations that should have been run.
This is so that migrations work from there on out.