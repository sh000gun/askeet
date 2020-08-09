
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- ask_question
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `ask_question`;

CREATE TABLE `ask_question`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `title` TEXT,
    `body` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `interested_users` INTEGER DEFAULT 0,
    `stripped_title` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `unique_stripped_title` (`stripped_title`),
    INDEX `ask_question_fi_c9f24d` (`user_id`),
    CONSTRAINT `ask_question_fk_c9f24d`
        FOREIGN KEY (`user_id`)
        REFERENCES `ask_user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- ask_answer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `ask_answer`;

CREATE TABLE `ask_answer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `question_id` INTEGER,
    `user_id` INTEGER,
    `body` TEXT,
    `created_at` DATETIME,
    `relevancy_up` INTEGER DEFAULT 0,
    `relevancy_down` INTEGER DEFAULT 0,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `ask_answer_fi_3a3644` (`question_id`),
    INDEX `ask_answer_fi_c9f24d` (`user_id`),
    CONSTRAINT `ask_answer_fk_3a3644`
        FOREIGN KEY (`question_id`)
        REFERENCES `ask_question` (`id`),
    CONSTRAINT `ask_answer_fk_c9f24d`
        FOREIGN KEY (`user_id`)
        REFERENCES `ask_user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- ask_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `ask_user`;

CREATE TABLE `ask_user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nickname` VARCHAR(50),
    `first_name` VARCHAR(100),
    `last_name` VARCHAR(100),
    `created_at` DATETIME,
    `email` VARCHAR(100),
    `sha1_password` VARCHAR(40),
    `salt` VARCHAR(32),
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- ask_interest
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `ask_interest`;

CREATE TABLE `ask_interest`
(
    `question_id` INTEGER NOT NULL,
    `user_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`question_id`,`user_id`),
    INDEX `ask_interest_fi_c9f24d` (`user_id`),
    CONSTRAINT `ask_interest_fk_3a3644`
        FOREIGN KEY (`question_id`)
        REFERENCES `ask_question` (`id`),
    CONSTRAINT `ask_interest_fk_c9f24d`
        FOREIGN KEY (`user_id`)
        REFERENCES `ask_user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- ask_relevancy
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `ask_relevancy`;

CREATE TABLE `ask_relevancy`
(
    `answer_id` INTEGER NOT NULL,
    `user_id` INTEGER NOT NULL,
    `score` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`answer_id`,`user_id`),
    INDEX `ask_relevancy_fi_c9f24d` (`user_id`),
    CONSTRAINT `ask_relevancy_fk_763f3f`
        FOREIGN KEY (`answer_id`)
        REFERENCES `ask_answer` (`id`),
    CONSTRAINT `ask_relevancy_fk_c9f24d`
        FOREIGN KEY (`user_id`)
        REFERENCES `ask_user` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
