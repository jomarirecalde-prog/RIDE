-- Separate research and extension approval paths after College Dean

INSERT IGNORE INTO roles (id, slug, name, description) VALUES
(9, 'director_research', 'Director of Research', 'Approves research submissions after College Dean and forwards to VPRIDE'),
(10, 'director_extension', 'Director of Extension', 'Approves extension submissions after College Dean and forwards to VPRIDE');

UPDATE roles SET description = 'Grants final approval after Director of Research or Director of Extension approval'
WHERE slug = 'vpride';

UPDATE roles SET description = 'Approves endorsed submissions and forwards to the Director of Research or Director of Extension'
WHERE slug = 'dean';

INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES
(9, 3),(9, 6),(9, 7),(9, 8),(9, 10),
(10, 3),(10, 6),(10, 7),(10, 8),(10, 10);
