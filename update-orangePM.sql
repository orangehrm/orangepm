ALTER TABLE  `orangepm_story` ADD  `assign_to` VARCHAR( 255 ) NOT NULL AFTER  `estimated_end_date`;

ALTER TABLE  `orangepm_project` ADD  `total_estimated_effort` VARCHAR( 255 ) NOT NULL AFTER  `end_date`;

ALTER TABLE  `orangepm_project` ADD  `current_effort` VARCHAR( 255 ) NOT NULL AFTER  `total_estimated_effort`;

