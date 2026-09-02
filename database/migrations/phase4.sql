-- Deactivate demo accounts for roles removed from the three-role monitoring workflow

DELETE ur FROM user_roles ur
INNER JOIN users u ON u.id = ur.user_id
WHERE u.email IN (
    'reporter@ride.local',
    'ethics@ride.local',
    'partner@ride.local',
    'leader.inn@ride.local'
);

UPDATE users SET is_active = 0
WHERE email IN (
    'reporter@ride.local',
    'ethics@ride.local',
    'partner@ride.local',
    'leader.inn@ride.local'
);
