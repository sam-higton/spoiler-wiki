
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- artist
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `artist`;

CREATE TABLE `artist`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `bio` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- canon
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `canon`;

CREATE TABLE `canon`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `primary_artist_id` INTEGER NOT NULL,
    `work_type_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `canon_fi_701e81` (`work_type_id`),
    INDEX `canon_fi_2233f1` (`primary_artist_id`),
    CONSTRAINT `canon_fk_701e81`
        FOREIGN KEY (`work_type_id`)
        REFERENCES `work_type` (`id`),
    CONSTRAINT `canon_fk_2233f1`
        FOREIGN KEY (`primary_artist_id`)
        REFERENCES `artist` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- work
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `work`;

CREATE TABLE `work`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `order` INTEGER DEFAULT 0 NOT NULL,
    `primary_artist_id` INTEGER NOT NULL,
    `canon_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `work_fi_2233f1` (`primary_artist_id`),
    INDEX `work_fi_eeb6b0` (`canon_id`),
    CONSTRAINT `work_fk_2233f1`
        FOREIGN KEY (`primary_artist_id`)
        REFERENCES `artist` (`id`),
    CONSTRAINT `work_fk_eeb6b0`
        FOREIGN KEY (`canon_id`)
        REFERENCES `canon` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- work_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `work_type`;

CREATE TABLE `work_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `work_label` VARCHAR(255) NOT NULL,
    `milestone_label` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- milestone
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `milestone`;

CREATE TABLE `milestone`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `order` INTEGER DEFAULT 0 NOT NULL,
    `label` VARCHAR(255),
    `work_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `milestone_fi_9cf5e3` (`work_id`),
    CONSTRAINT `milestone_fk_9cf5e3`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- topic
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `topic`;

CREATE TABLE `topic`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `canon_id` INTEGER NOT NULL,
    `introduced_at` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `topic_fi_eeb6b0` (`canon_id`),
    INDEX `topic_fi_75f046` (`introduced_at`),
    CONSTRAINT `topic_fk_eeb6b0`
        FOREIGN KEY (`canon_id`)
        REFERENCES `canon` (`id`),
    CONSTRAINT `topic_fk_75f046`
        FOREIGN KEY (`introduced_at`)
        REFERENCES `milestone` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- snippet
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `snippet`;

CREATE TABLE `snippet`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `topic_id` INTEGER NOT NULL,
    `introduced_at` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `snippet_fi_5f1143` (`topic_id`),
    INDEX `snippet_fi_75f046` (`introduced_at`),
    CONSTRAINT `snippet_fk_5f1143`
        FOREIGN KEY (`topic_id`)
        REFERENCES `topic` (`id`),
    CONSTRAINT `snippet_fk_75f046`
        FOREIGN KEY (`introduced_at`)
        REFERENCES `milestone` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- summary
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `summary`;

CREATE TABLE `summary`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `topic_id` INTEGER NOT NULL,
    `introduced_at` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `summary_fi_5f1143` (`topic_id`),
    INDEX `summary_fi_75f046` (`introduced_at`),
    CONSTRAINT `summary_fk_5f1143`
        FOREIGN KEY (`topic_id`)
        REFERENCES `topic` (`id`),
    CONSTRAINT `summary_fk_75f046`
        FOREIGN KEY (`introduced_at`)
        REFERENCES `milestone` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- content_area
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `content_area`;

CREATE TABLE `content_area`
(
    `content` TEXT NOT NULL,
    `active_version` INTEGER DEFAULT 1,
    `id` INTEGER NOT NULL,
    `version` INTEGER DEFAULT 0,
    `version_created_at` DATETIME,
    `version_created_by` VARCHAR(100),
    `version_comment` VARCHAR(255),
    PRIMARY KEY (`id`),
    CONSTRAINT `content_area_fk_6eb46d`
        FOREIGN KEY (`id`)
        REFERENCES `snippet` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `content_area_fk_c186ba`
        FOREIGN KEY (`id`)
        REFERENCES `summary` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- auth_token
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `auth_token`;

CREATE TABLE `auth_token`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `auth_token_fi_29554a` (`user_id`),
    CONSTRAINT `auth_token_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- role
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- assigned_role
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `assigned_role`;

CREATE TABLE `assigned_role`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `canon_id` INTEGER NOT NULL,
    `role_id` INTEGER NOT NULL,
    `assigned_by` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `assigned_role_fi_29554a` (`user_id`),
    INDEX `assigned_role_fi_5e026c` (`assigned_by`),
    INDEX `assigned_role_fi_eeb6b0` (`canon_id`),
    CONSTRAINT `assigned_role_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `assigned_role_fk_5e026c`
        FOREIGN KEY (`assigned_by`)
        REFERENCES `user` (`id`),
    CONSTRAINT `assigned_role_fk_eeb6b0`
        FOREIGN KEY (`canon_id`)
        REFERENCES `canon` (`id`),
    CONSTRAINT `assigned_role_fk_213711`
        FOREIGN KEY (`user_id`)
        REFERENCES `role` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- content_area_version
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `content_area_version`;

CREATE TABLE `content_area_version`
(
    `content` TEXT NOT NULL,
    `active_version` INTEGER DEFAULT 1,
    `id` INTEGER NOT NULL,
    `version` INTEGER DEFAULT 0 NOT NULL,
    `version_created_at` DATETIME,
    `version_created_by` VARCHAR(100),
    `version_comment` VARCHAR(255),
    PRIMARY KEY (`id`,`version`),
    CONSTRAINT `content_area_version_fk_560284`
        FOREIGN KEY (`id`)
        REFERENCES `content_area` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
