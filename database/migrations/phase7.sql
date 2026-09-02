-- Rename project leader role to faculty for account management and UI

UPDATE roles
SET slug = 'faculty',
    name = 'Faculty',
    description = 'Faculty researcher and proposal submitter'
WHERE slug = 'project_leader';
