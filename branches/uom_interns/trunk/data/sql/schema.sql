CREATE TABLE orangepm_project (id BIGINT AUTO_INCREMENT, name VARCHAR(255), project_status_id BIGINT, deleted TINYINT(1) DEFAULT '1' NOT NULL, user_id BIGINT, description LONGTEXT, start_date DATE NOT NULL, end_date DATE, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX project_status_id_idx (project_status_id), INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE orangepm_project_log (id BIGINT AUTO_INCREMENT, project_id BIGINT NOT NULL, added_by BIGINT, description LONGTEXT, logged_date DATETIME NOT NULL, INDEX project_id_idx (project_id), INDEX added_by_idx (added_by), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE orangepm_project_member (id BIGINT AUTO_INCREMENT, user_id BIGINT, project_id BIGINT, INDEX user_id_idx (user_id), INDEX project_id_idx (project_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE orangepm_project_progress (project_id BIGINT, accepted_date DATE, work_completed BIGINT, unit_of_work BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(project_id, accepted_date)) ENGINE = INNODB;
CREATE TABLE orangepm_project_status (id BIGINT AUTO_INCREMENT, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE orangepm_story (id BIGINT AUTO_INCREMENT, project_id BIGINT, estimation BIGINT, name VARCHAR(255), date_added DATE, status VARCHAR(255), accepted_date DATE, deleted_date DATE, deleted TINYINT(1) DEFAULT '1' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE orangepm_task (id BIGINT AUTO_INCREMENT, name VARCHAR(255), story_id BIGINT, effort DECIMAL(18, 2), description LONGTEXT, owned_by VARCHAR(255), status BIGINT, INDEX story_id_idx (story_id), INDEX status_idx (status), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE orangepm_task_status (id BIGINT AUTO_INCREMENT, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE orangepm_user (id BIGINT AUTO_INCREMENT, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255), is_active TINYINT(1) DEFAULT '1' NOT NULL, username VARCHAR(255) NOT NULL UNIQUE, user_type BIGINT NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE orangepm_project ADD CONSTRAINT orangepm_project_user_id_orangepm_user_id FOREIGN KEY (user_id) REFERENCES orangepm_user(id) ON DELETE SET NULL;
ALTER TABLE orangepm_project ADD CONSTRAINT orangepm_project_project_status_id_orangepm_project_status_id FOREIGN KEY (project_status_id) REFERENCES orangepm_project_status(id) ON DELETE SET NULL;
ALTER TABLE orangepm_project_log ADD CONSTRAINT orangepm_project_log_project_id_orangepm_project_id FOREIGN KEY (project_id) REFERENCES orangepm_project(id) ON DELETE CASCADE;
ALTER TABLE orangepm_project_log ADD CONSTRAINT orangepm_project_log_added_by_orangepm_user_id FOREIGN KEY (added_by) REFERENCES orangepm_user(id) ON DELETE SET NULL;
ALTER TABLE orangepm_project_member ADD CONSTRAINT orangepm_project_member_user_id_orangepm_user_id FOREIGN KEY (user_id) REFERENCES orangepm_user(id);
ALTER TABLE orangepm_project_member ADD CONSTRAINT orangepm_project_member_project_id_orangepm_project_id FOREIGN KEY (project_id) REFERENCES orangepm_project(id);
ALTER TABLE orangepm_task ADD CONSTRAINT orangepm_task_story_id_orangepm_story_id FOREIGN KEY (story_id) REFERENCES orangepm_story(id) ON DELETE CASCADE;
ALTER TABLE orangepm_task ADD CONSTRAINT orangepm_task_status_orangepm_task_status_id FOREIGN KEY (status) REFERENCES orangepm_task_status(id) ON DELETE SET NULL;
