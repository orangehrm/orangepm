CREATE TABLE orangepm_project (id BIGINT AUTO_INCREMENT, name VARCHAR(255), deleted TINYINT(1) DEFAULT '1' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;

CREATE TABLE orangepm_story (id BIGINT AUTO_INCREMENT, project_id BIGINT, estimation BIGINT, name VARCHAR(255), date_added DATE, status VARCHAR(255), accepted_date DATE, deleted TINYINT(1) DEFAULT '1' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX project_id_idx (project_id), PRIMARY KEY(id)) ENGINE = INNODB;

CREATE TABLE orangepm_project_progress (project_id BIGINT, date DATE, work_completed BIGINT, unit_of_work BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(project_id, date)) ENGINE = INNODB;


ALTER TABLE orangepm_story ADD CONSTRAINT orangepm_story_project_id_orangepm_project_id FOREIGN KEY (project_id) REFERENCES orangepm_project(id) ON DELETE CASCADE;
