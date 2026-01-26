DROP DATABASE IF EXISTS u739712079_somsystem;
CREATE DATABASE u739712079_somsystem;
USE u739712079_somsystem;

-- 1. USERS
CREATE TABLE users (
    user_id VARCHAR(20) PRIMARY KEY,
    school_id VARCHAR(20) UNIQUE, 
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    account_type ENUM('Student','Faculty', 'Admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);

-- 2. ORGANIZATIONS
CREATE TABLE organizations (
    org_id VARCHAR(20) PRIMARY KEY,
    org_name VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. MEMBERSHIPS
CREATE TABLE memberships (
    membership_id VARCHAR(20) PRIMARY KEY,
    user_id VARCHAR(20) NOT NULL,
    org_id VARCHAR(20) NOT NULL,
    academic_year VARCHAR(20) NOT NULL,
    membership_role ENUM('Officer', 'Member') DEFAULT 'Member',
    joined_at DATE DEFAULT (CURRENT_DATE),
    status ENUM('Pending', 'Active', 'Rejected', 'Alumni') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (org_id) REFERENCES organizations(org_id) ON DELETE CASCADE,
    UNIQUE (user_id, org_id)
);

-- 4. ORG ADVISERS
CREATE TABLE org_advisers (
    adviser_id VARCHAR(20) PRIMARY KEY,
    org_id VARCHAR(20) NOT NULL,
    user_id VARCHAR(20) NOT NULL, 
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (org_id) REFERENCES organizations(org_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(org_id, user_id)
);

-- 5. ORG OFFICERS
CREATE TABLE org_officers (
    officer_id VARCHAR(20) PRIMARY KEY,
    membership_id VARCHAR(20) NOT NULL,
    org_id VARCHAR(20) NOT NULL,
    position VARCHAR(50) NOT NULL,
    term_start DATE,
    term_end DATE,
    FOREIGN KEY (membership_id) REFERENCES memberships(membership_id) ON DELETE CASCADE,
    FOREIGN KEY (org_id) REFERENCES organizations(org_id) ON DELETE CASCADE,
    UNIQUE (membership_id)
);

-- 6. EVENTS
CREATE TABLE events (
    event_id VARCHAR(20) PRIMARY KEY,
    org_id VARCHAR(20) NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    event_duration INT DEFAULT 4,
    venue VARCHAR(100),
    status ENUM('Pending', 'Upcoming', 'Ongoing', 'Done', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by VARCHAR(20),
    FOREIGN KEY (org_id) REFERENCES organizations(org_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL
); 

ALTER TABLE events
ADD COLUMN image_path VARCHAR(255) NULL AFTER status;


-- 7. EVENT LIKES
CREATE TABLE event_likes (
    like_id VARCHAR(20) PRIMARY KEY,
    event_id VARCHAR(20) NOT NULL,
    user_id VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE (event_id, user_id)
);

-- 8. EVENT ATTENDANCE
CREATE TABLE event_attendance (
    attendance_id VARCHAR(20) PRIMARY KEY,
    event_id VARCHAR(20) NOT NULL,
    user_id VARCHAR(20) NOT NULL,
    status ENUM('RSVP', 'Walk-in', 'Present', 'Absent', 'Excused') DEFAULT 'RSVP',
    remarks TEXT,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (event_id, user_id)
);

-- PAST OFFICERS
CREATE TABLE officer_history (
    history_id VARCHAR(20) PRIMARY KEY,
    user_id VARCHAR(20) NOT NULL,
    org_id VARCHAR(20) NOT NULL,
    position VARCHAR(50),
    term_start DATE,
    term_end DATE,
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (org_id) REFERENCES organizations(org_id) ON DELETE CASCADE
);

-- NOTIFICATIONS
CREATE TABLE notifications (
    notification_id VARCHAR(20) PRIMARY KEY,
    user_id VARCHAR(20) NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(150) NOT NULL,
    message TEXT,
    link VARCHAR(255),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- PROCEDURES & TRIGGERS

DELIMITER $$

CREATE TRIGGER trg_user_id_gen
BEFORE INSERT ON users FOR EACH ROW
BEGIN
    DECLARE max_seq INT;

    SELECT IFNULL(MAX(CAST(SUBSTRING(user_id, 5) AS UNSIGNED)), 0) INTO max_seq FROM users;
    
    SET NEW.user_id = CONCAT('PUP-', LPAD(max_seq + 1, 8, '0'));
END $$


CREATE TRIGGER trg_org_id_gen BEFORE INSERT ON organizations FOR EACH ROW
BEGIN
    DECLARE max_seq INT;
    SELECT IFNULL(MAX(CAST(SUBSTRING(org_id, 5) AS UNSIGNED)), 0) INTO max_seq FROM organizations;
    SET NEW.org_id = CONCAT('ORG-', LPAD(max_seq + 1, 8, '0'));
END $$

CREATE TRIGGER trg_mem_id_gen BEFORE INSERT ON memberships FOR EACH ROW
BEGIN
    DECLARE max_seq INT;
    SELECT IFNULL(MAX(CAST(SUBSTRING(membership_id, 5) AS UNSIGNED)), 0) INTO max_seq FROM memberships;
    SET NEW.membership_id = CONCAT('MEM-', LPAD(max_seq + 1, 8, '0'));
END $$

CREATE TRIGGER trg_adv_id_gen BEFORE INSERT ON org_advisers FOR EACH ROW
BEGIN
    DECLARE max_seq INT;
    SELECT IFNULL(MAX(CAST(SUBSTRING(adviser_id, 5) AS UNSIGNED)), 0) INTO max_seq FROM org_advisers;
    SET NEW.adviser_id = CONCAT('ADV-', LPAD(max_seq + 1, 8, '0'));
END $$

CREATE TRIGGER trg_off_id_gen BEFORE INSERT ON org_officers FOR EACH ROW
BEGIN
    DECLARE max_seq INT;
    SELECT IFNULL(MAX(CAST(SUBSTRING(officer_id, 5) AS UNSIGNED)), 0) INTO max_seq FROM org_officers;
    SET NEW.officer_id = CONCAT('OFF-', LPAD(max_seq + 1, 8, '0'));
END $$

CREATE TRIGGER trg_evt_id_gen BEFORE INSERT ON events FOR EACH ROW
BEGIN
    DECLARE max_seq INT;
    SELECT IFNULL(MAX(CAST(SUBSTRING(event_id, 5) AS UNSIGNED)), 0) INTO max_seq FROM events;
    SET NEW.event_id = CONCAT('EVT-', LPAD(max_seq + 1, 8, '0'));
END $$

CREATE TRIGGER trg_like_id_gen BEFORE INSERT ON event_likes FOR EACH ROW
BEGIN
    DECLARE max_seq INT;
    SELECT IFNULL(MAX(CAST(SUBSTRING(like_id, 6) AS UNSIGNED)), 0) INTO max_seq FROM event_likes;
    SET NEW.like_id = CONCAT('LIKE-', LPAD(max_seq + 1, 8, '0'));
END $$

CREATE TRIGGER trg_att_id_gen BEFORE INSERT ON event_attendance FOR EACH ROW
BEGIN
    DECLARE max_seq INT;
    SELECT IFNULL(MAX(CAST(SUBSTRING(attendance_id, 5) AS UNSIGNED)), 0) INTO max_seq FROM event_attendance;
    SET NEW.attendance_id = CONCAT('ATT-', LPAD(max_seq + 1, 8, '0'));
END $$

CREATE TRIGGER trg_his_id_gen BEFORE INSERT ON officer_history FOR EACH ROW
BEGIN
    DECLARE max_seq INT;
    SELECT IFNULL(MAX(CAST(SUBSTRING(history_id, 5) AS UNSIGNED)), 0) INTO max_seq FROM officer_history;
    SET NEW.history_id = CONCAT('HIS-', LPAD(max_seq + 1, 8, '0'));
END $$

CREATE TRIGGER trg_notif_id_gen BEFORE INSERT ON notifications FOR EACH ROW
BEGIN
    DECLARE max_seq INT;
    SELECT IFNULL(MAX(CAST(SUBSTRING(notification_id, 5) AS UNSIGNED)), 0) INTO max_seq FROM notifications;
    SET NEW.notification_id = CONCAT('NTF-', LPAD(max_seq + 1, 8, '0'));
END $$

DELIMITER $$

CREATE PROCEDURE MoveToAlumni(
    IN p_org_id VARCHAR(20), 
    IN p_academic_year VARCHAR(20)
)
BEGIN
    UPDATE memberships
    SET status = 'Alumni'
    WHERE org_id = p_org_id 
      AND academic_year = p_academic_year
      AND status = 'Active';
END $$

CREATE PROCEDURE former_officers()
BEGIN
    INSERT INTO officer_history (user_id, org_id, position, term_start, term_end)
    SELECT m.user_id, o.org_id, o.position, o.term_start, o.term_end
    FROM org_officers o
    JOIN memberships m ON o.membership_id = m.membership_id
    WHERE o.term_end < CURDATE(); 

    DELETE FROM org_officers
    WHERE term_end < CURDATE();
END $$

CREATE TRIGGER check_event_creator_role
BEFORE INSERT ON events
FOR EACH ROW
BEGIN
    DECLARE creator_type VARCHAR(20);
    
    SELECT account_type INTO creator_type 
    FROM users 
    WHERE user_id = NEW.created_by LIMIT 1;

    IF creator_type = 'Faculty' THEN
        SET NEW.status = 'Upcoming';
    ELSE
        SET NEW.status = 'Pending';
    END IF;
END $$


DELIMITER $$
CREATE TRIGGER trg_validate_new_officer
BEFORE INSERT ON org_officers
FOR EACH ROW
BEGIN
    DECLARE real_org_id VARCHAR(20); 
    DECLARE current_status VARCHAR(20);
    DECLARE member_role VARCHAR(20);  -- Changed from current_role
    
    SELECT org_id, status, membership_role 
    INTO real_org_id, current_status, member_role
    FROM memberships
    WHERE membership_id = NEW.membership_id;
    
    IF real_org_id <> NEW.org_id THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'ERROR: Membership ID does not belong to this Organization.';
    END IF;
    
    IF current_status <> 'Active' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'ERROR: User is not an ACTIVE member. Please approve membership first.';
    END IF;
    
    IF member_role <> 'Officer' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'ERROR: User role is currently "Member". Update membership role to "Officer" first.';
    END IF;
END $$
DELIMITER ;

DELIMITER $$

CREATE TRIGGER trg_check_adviser_role
BEFORE INSERT ON org_advisers
FOR EACH ROW
BEGIN
    DECLARE user_role VARCHAR(20);

    SELECT account_type INTO user_role
    FROM users
    WHERE user_id = NEW.user_id;

    IF user_role <> 'Faculty' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'ERROR: Only users with "Faculty" account type can be assigned as Advisers.';
    END IF;

END $$

DELIMITER ;

-- AUTO EVENT STATUS UPDATER
-- Automatically updates event status based on date and duration
DELIMITER $$

CREATE EVENT auto_event_status_updater
ON SCHEDULE EVERY 1 MINUTE
DO
BEGIN
    UPDATE events
    SET status = 'Ongoing'
    WHERE status = 'Upcoming'
      AND event_date <= NOW();

    UPDATE events
    SET status = 'Done'
    WHERE status = 'Ongoing'
      AND NOW() >= DATE_ADD(event_date, INTERVAL event_duration HOUR);
END $$

DELIMITER ;

SELECT * FROM users;
SELECT * FROM organizations;
SELECT * FROM memberships;
SELECT * FROM org_advisers;
SELECT * FROM org_officers;
SELECT * FROM events;
SELECT * FROM event_likes;
SELECT * FROM event_attendance;
SELECT * FROM officer_history;



-- All passwords are: password123
-- Students: kagamayow@gmail.com (SN-00000123), evansg@gmail.com (SN-00000101), sisramiez@gmail.com (SN-00001110)
-- Faculty: jkvelayo@gmail.com (FN-00001010), cfcayona@gmail.com (FN-00022121), kennyken@gmail.com (FN-00022322)
-- Admin: admin@admin.com (ADMIN-001)
INSERT INTO `u739712079_somsystem`.`users` (`school_id`, `full_name`, `email`, `password`, `account_type`) VALUES ('SN-00000123', 'Kelia Gamayo', 'kagamayow@gmail.com', '$2y$12$EfZ9H7saMVGKl8E/0aEt9.NYaYglF3CPSlU.iM.6AH21LIpUPyJpW', 'Student');
INSERT INTO `u739712079_somsystem`.`users` (`school_id`, `full_name`, `email`, `password`, `account_type`) VALUES ('FN-00001010', 'Josef Velayo', 'jkvelayo@gmail.com', '$2y$12$EfZ9H7saMVGKl8E/0aEt9.NYaYglF3CPSlU.iM.6AH21LIpUPyJpW', 'Faculty');
INSERT INTO `u739712079_somsystem`.`users` (`school_id`, `full_name`, `email`, `password`, `account_type`) VALUES ('FN-00022121', 'Franzel Cayona', 'cfcayona@gmail.com', '$2y$12$EfZ9H7saMVGKl8E/0aEt9.NYaYglF3CPSlU.iM.6AH21LIpUPyJpW', 'Faculty');
INSERT INTO `u739712079_somsystem`.`users` (`school_id`, `full_name`, `email`, `password`, `account_type`) VALUES ('SN-00000101', 'Evans Gutierrez', 'evansg@gmail.com', '$2y$12$EfZ9H7saMVGKl8E/0aEt9.NYaYglF3CPSlU.iM.6AH21LIpUPyJpW', 'Student');
INSERT INTO `u739712079_somsystem`.`users` (`school_id`, `full_name`, `email`, `password`, `account_type`) VALUES ('SN-00001110', 'Siska Ramirez', 'sisramiez@gmail.com', '$2y$12$EfZ9H7saMVGKl8E/0aEt9.NYaYglF3CPSlU.iM.6AH21LIpUPyJpW', 'Student');
INSERT INTO `u739712079_somsystem`.`users` (`school_id`, `full_name`, `email`, `password`, `account_type`) VALUES ('FN-00022322', 'Ken Mondragon', 'kennyken@gmail.com', '$2y$12$EfZ9H7saMVGKl8E/0aEt9.NYaYglF3CPSlU.iM.6AH21LIpUPyJpW', 'Faculty');
INSERT INTO `u739712079_somsystem`.`users` (`school_id`, `full_name`, `email`, `password`, `account_type`) VALUES ('ADMIN-001', 'System Admin', 'admin@admin.com', '$2y$12$EfZ9H7saMVGKl8E/0aEt9.NYaYglF3CPSlU.iM.6AH21LIpUPyJpW', 'Admin');

INSERT INTO `u739712079_somsystem`.`organizations` (`org_name`, `description`, `status`, `created_at`) VALUES ('AWS PUP', 'CCIS Students are welcome', 'Active', '2020-01-20');
INSERT INTO `u739712079_somsystem`.`organizations` (`org_name`, `description`, `status`, `created_at`) VALUES ('GDG PUP', 'CCIS Students are welcome', 'Active', '2021-05-12');
INSERT INTO `u739712079_somsystem`.`organizations` (`org_name`, `description`, `status`, `created_at`) VALUES ('Sintang Pusa', 'PUP Organization for cats', 'Active', '2015-02-14');

INSERT INTO memberships (user_id, org_id, academic_year, membership_role, joined_at, status) VALUES
('PUP-00000001', 'ORG-00000002', '2025-2026', 'Member', '2024-01-20', 'Active'),
('PUP-00000004', 'ORG-00000001', '2025-2026', 'Member', '2024-02-20', 'Active'),
('PUP-00000005', 'ORG-00000003', '2025-2026', 'Officer', '2024-03-20', 'Active'),
('PUP-00000001', 'ORG-00000001', '2025-2026', 'Member', '2024-01-20', 'Active'),
('PUP-00000005', 'ORG-00000002', '2025-2026', 'Officer', '2024-03-20', 'Active');

INSERT INTO org_advisers (org_id, user_id) VALUES
('ORG-00000001', 'PUP-00000003'),
('ORG-00000002', 'PUP-00000002'),
('ORG-00000003', 'PUP-00000006');

INSERT INTO `u739712079_somsystem`.`org_officers` (`membership_id`, `org_id`, `position`, `term_start`, `term_end`) VALUES ('MEM-00000003', 'ORG-00000003', 'President', '2026-01-05', '2027-01-05');

-- INSERT EVENTS DATA
-- Based on your existing organizations: ORG-00000001 (AWS PUP), ORG-00000002 (GDG PUP), ORG-00000003 (Sintang Pusa)

INSERT INTO events (org_id, title, description, event_date, event_duration, venue, created_by) VALUES 
-- AWS PUP Events
('ORG-00000001', 'AWS Workshop: Cloud Computing Basics', 'Introduction to AWS services and cloud infrastructure', '2026-02-15 14:00:00', 3, 'CCIS Room 301', 'PUP-00000003'),
('ORG-00000001', 'AWS Certification Bootcamp', 'Intensive training for AWS Certified Solutions Architect', '2026-03-10 09:00:00', 8, 'CCIS Lab 1', 'PUP-00000003'),
('ORG-00000001', 'Cloud Security Webinar', 'Best practices for securing cloud infrastructure', '2026-01-28 16:00:00', 2, 'Online - Zoom', 'PUP-00000003'),
('ORG-00000001', 'AWS Community Day', 'Networking and knowledge sharing for AWS enthusiasts', '2026-04-05 09:00:00', 6, 'Main Auditorium', 'PUP-00000003'),

-- GDG PUP Events  
('ORG-00000002', 'GDG Tech Talk: AI and Machine Learning', 'Latest trends in AI/ML and practical applications', '2026-02-20 10:00:00', 4, 'Main Auditorium', 'PUP-00000002'),
('ORG-00000002', 'Flutter Development Workshop', 'Build your first mobile app with Flutter', '2026-03-05 13:00:00', 5, 'CCIS Room 205', 'PUP-00000002'),
('ORG-00000002', 'Google Cloud Study Jam', 'Hands-on labs for Google Cloud Platform', '2026-01-25 15:00:00', 4, 'CCIS Lab 2', 'PUP-00000002'),
('ORG-00000002', 'DevFest 2026 PUP', 'Annual developer festival with speakers and workshops', '2026-04-15 08:00:00', 10, 'PUP Gymnasium', 'PUP-00000002'),
('ORG-00000002', 'Web Development Series: React', 'Build modern web apps with React', '2026-02-25 14:00:00', 4, 'CCIS Room 401', 'PUP-00000002'),

-- Sintang Pusa Events
('ORG-00000003', 'Cat Adoption Drive', 'Community cat adoption event with free vet check-up', '2026-03-01 09:00:00', 6, 'Campus Grounds', 'PUP-00000006'),
('ORG-00000003', 'Feline Care Seminar', 'Proper cat care, nutrition, and health management', '2026-02-10 14:00:00', 3, 'Student Center Hall', 'PUP-00000006'),
('ORG-00000003', 'Cat Photography Workshop', 'Learn to capture the perfect cat moments', '2026-01-30 10:00:00', 4, 'Media Arts Room', 'PUP-00000006'),
('ORG-00000003', 'Stray Cat Feeding Program Launch', 'Initiative to feed stray cats around campus', '2026-02-05 08:00:00', 3, 'Campus Garden', 'PUP-00000006');


-- INSERT EVENT ATTENDANCE DATA
-- Using your existing user IDs: PUP-00000001 to PUP-00000006

-- Event 1: AWS Workshop (EVT-00000001)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000001', 'PUP-00000001', 'RSVP', 'Looking forward to learning AWS'),
('EVT-00000001', 'PUP-00000004', 'RSVP', 'Want to get certified');

-- Event 2: AWS Certification Bootcamp (EVT-00000002)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000002', 'PUP-00000001', 'RSVP', 'Preparing for certification exam'),
('EVT-00000002', 'PUP-00000004', 'RSVP', 'Need this for career advancement');

-- Event 3: Cloud Security Webinar (EVT-00000003) - Already happened
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000003', 'PUP-00000001', 'Present', 'Very informative session'),
('EVT-00000003', 'PUP-00000004', 'Present', 'Learned best practices');

-- Event 4: AWS Community Day (EVT-00000004)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000004', 'PUP-00000001', 'RSVP', 'Excited to network'),
('EVT-00000004', 'PUP-00000004', 'RSVP', 'Looking forward to it');

-- Event 5: GDG Tech Talk (EVT-00000005)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000005', 'PUP-00000001', 'RSVP', 'Interested in AI/ML'),
('EVT-00000005', 'PUP-00000005', 'RSVP', 'Want to learn more about AI');

-- Event 6: Flutter Workshop (EVT-00000006)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000006', 'PUP-00000001', 'RSVP', 'Want to build mobile apps'),
('EVT-00000006', 'PUP-00000005', 'RSVP', 'Flutter looks interesting');

-- Event 7: Google Cloud Study Jam (EVT-00000007) - Already happened
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000007', 'PUP-00000001', 'Present', 'Completed 3 labs successfully'),
('EVT-00000007', 'PUP-00000005', 'Present', 'Got hands-on GCP experience');

-- Event 8: DevFest 2026 (EVT-00000008)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000008', 'PUP-00000001', 'RSVP', 'Cannot wait for DevFest!'),
('EVT-00000008', 'PUP-00000005', 'RSVP', 'Registered early');

-- Event 9: Web Development Series (EVT-00000009)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000009', 'PUP-00000001', 'RSVP', 'Want to learn React'),
('EVT-00000009', 'PUP-00000005', 'RSVP', 'Building a project with React');

-- Event 10: Cat Adoption Drive (EVT-00000010)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000010', 'PUP-00000005', 'RSVP', 'Planning to adopt a kitten');

-- Event 11: Feline Care Seminar (EVT-00000011)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000011', 'PUP-00000005', 'RSVP', NULL);

-- Event 12: Cat Photography Workshop (EVT-00000012) - Already happened
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES 
('EVT-00000012', 'PUP-00000005', 'Present', 'Fun and creative workshop');

-- Event 13: Stray Cat Feeding Program (EVT-00000013)
INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES
('EVT-00000013', 'PUP-00000005', 'RSVP', 'Happy to help');

-- Past event for testing events attended
INSERT INTO events (org_id, title, description, event_date, event_duration, venue, status, created_by) VALUES
('ORG-00000001', 'AWS Welcome Orientation', 'Welcome session for new AWS PUP members', '2025-09-20 10:00:00', 3, 'CCIS Room 301', 'Done', 'PUP-00000002');

INSERT INTO event_attendance (event_id, user_id, status, remarks) VALUES
('EVT-00000014', 'PUP-00000001', 'Present', 'Great orientation'),
('EVT-00000014', 'PUP-00000002', 'Present', 'Welcomed new members'),
('EVT-00000014', 'PUP-00000004', 'Present', 'Learned about AWS'),
('EVT-00000014', 'PUP-00000005', 'Present', 'Excited to join');

-- ================================================================================
-- USEFUL QUERIES TO VIEW EVENT ATTENDANCE DATA
-- ================================================================================

-- 1. VIEW ALL EVENTS WITH ATTENDANCE SUMMARY
SELECT 
    e.event_id,
    o.org_name,
    e.title,
    DATE_FORMAT(e.event_date, '%Y-%m-%d %H:%i') AS event_date,
    e.venue,
    e.status,
    COUNT(ea.attendance_id) AS total_registered,
    SUM(CASE WHEN ea.status = 'Present' THEN 1 ELSE 0 END) AS present_count,
    SUM(CASE WHEN ea.status = 'RSVP' THEN 1 ELSE 0 END) AS rsvp_count,
    SUM(CASE WHEN ea.status = 'Absent' THEN 1 ELSE 0 END) AS absent_count
FROM events e
LEFT JOIN organizations o ON e.org_id = o.org_id
LEFT JOIN event_attendance ea ON e.event_id = ea.event_id
GROUP BY e.event_id, o.org_name, e.title, e.event_date, e.venue, e.status
ORDER BY e.event_date;


-- 2. VIEW ATTENDANCE FOR A SPECIFIC EVENT
SELECT 
    e.title AS 'Event Name',
    DATE_FORMAT(e.event_date, '%M %d, %Y %h:%i %p') AS 'Event Date',
    e.venue,
    u.full_name AS 'Attendee Name',
    u.school_id AS 'School ID',
    u.account_type,
    ea.status AS 'Attendance Status',
    ea.remarks
FROM event_attendance ea
JOIN events e ON ea.event_id = e.event_id
JOIN users u ON ea.user_id = u.user_id
WHERE e.event_id = 'EVT-00000001'  -- Change this to view different events
ORDER BY ea.status, u.full_name;


-- 3. VIEW A SPECIFIC USER'S EVENT ATTENDANCE HISTORY
SELECT 
    u.full_name,
    u.school_id,
    e.title AS 'Event Title',
    o.org_name AS 'Organization',
    DATE_FORMAT(e.event_date, '%Y-%m-%d') AS 'Event Date',
    e.venue,
    ea.status AS 'Attendance Status',
    ea.remarks
FROM event_attendance ea
JOIN users u ON ea.user_id = u.user_id
JOIN events e ON ea.event_id = e.event_id
JOIN organizations o ON e.org_id = o.org_id
WHERE u.user_id = 'PUP-00000001'  -- Change this to view different users
ORDER BY e.event_date DESC;


-- 4. VIEW EVENTS BY ORGANIZATION WITH STATS
SELECT 
    o.org_name,
    COUNT(DISTINCT e.event_id) AS total_events,
    COUNT(ea.attendance_id) AS total_registrations,
    SUM(CASE WHEN ea.status = 'Present' THEN 1 ELSE 0 END) AS total_attended,
    SUM(CASE WHEN ea.status = 'RSVP' THEN 1 ELSE 0 END) AS upcoming_rsvps
FROM organizations o
LEFT JOIN events e ON o.org_id = e.org_id
LEFT JOIN event_attendance ea ON e.event_id = ea.event_id
GROUP BY o.org_id, o.org_name
ORDER BY total_events DESC;


-- 5. VIEW UPCOMING EVENTS WITH RSVP COUNT
SELECT 
    e.event_id,
    o.org_name,
    e.title,
    DATE_FORMAT(e.event_date, '%M %d, %Y %h:%i %p') AS event_datetime,
    e.venue,
    COUNT(ea.attendance_id) AS rsvp_count
FROM events e
JOIN organizations o ON e.org_id = o.org_id
LEFT JOIN event_attendance ea ON e.event_id = ea.event_id AND ea.status = 'RSVP'
WHERE e.event_date > NOW()
GROUP BY e.event_id, o.org_name, e.title, e.event_date, e.venue
ORDER BY e.event_date;


-- 6. VIEW ATTENDANCE RATE PER EVENT (for completed events)
SELECT 
    e.event_id,
    e.title,
    o.org_name,
    COUNT(ea.attendance_id) AS total_registered,
    SUM(CASE WHEN ea.status = 'Present' THEN 1 ELSE 0 END) AS attended,
    CONCAT(ROUND((SUM(CASE WHEN ea.status = 'Present' THEN 1 ELSE 0 END) / COUNT(ea.attendance_id)) * 100, 2), '%') AS attendance_rate
FROM events e
JOIN organizations o ON e.org_id = o.org_id
JOIN event_attendance ea ON e.event_id = ea.event_id
WHERE e.status = 'Done'
GROUP BY e.event_id, e.title, o.org_name
HAVING total_registered > 0
ORDER BY attendance_rate DESC;


-- 7. VIEW MOST ACTIVE STUDENTS (by event participation)
SELECT 
    u.user_id,
    u.full_name,
    u.school_id,
    COUNT(ea.attendance_id) AS events_registered,
    SUM(CASE WHEN ea.status = 'Present' THEN 1 ELSE 0 END) AS events_attended,
    SUM(CASE WHEN ea.status = 'RSVP' THEN 1 ELSE 0 END) AS upcoming_events
FROM users u
JOIN event_attendance ea ON u.user_id = ea.user_id
WHERE u.account_type = 'Student'
GROUP BY u.user_id, u.full_name, u.school_id
ORDER BY events_registered DESC;


-- 8. VIEW EVENT ATTENDANCE BY STATUS
SELECT 
    e.event_id,
    e.title,
    o.org_name,
    DATE_FORMAT(e.event_date, '%Y-%m-%d %H:%i') AS event_date,
    SUM(CASE WHEN ea.status = 'RSVP' THEN 1 ELSE 0 END) AS rsvp,
    SUM(CASE WHEN ea.status = 'Present' THEN 1 ELSE 0 END) AS present,
    SUM(CASE WHEN ea.status = 'Absent' THEN 1 ELSE 0 END) AS absent,
    SUM(CASE WHEN ea.status = 'Walk-in' THEN 1 ELSE 0 END) AS walk_in,
    SUM(CASE WHEN ea.status = 'Excused' THEN 1 ELSE 0 END) AS excused
FROM events e
JOIN organizations o ON e.org_id = o.org_id
LEFT JOIN event_attendance ea ON e.event_id = ea.event_id
GROUP BY e.event_id, e.title, o.org_name, e.event_date
ORDER BY e.event_date;

-- sino mga members ng org
SELECT 
    u.school_id,
    u.full_name,
    m.membership_role, 
    m.status           
FROM memberships m
JOIN users u ON m.user_id = u.user_id
WHERE m.org_id = 'ORG-00000002';

-- sino adviser ng orgs
SELECT 
    u.full_name AS 'Adviser Name',
    u.email AS 'Contact Email',
    u.school_id AS 'Faculty ID',
    oa.assigned_at
FROM org_advisers oa
JOIN users u ON oa.user_id = u.user_id
WHERE oa.org_id = 'ORG-00000001';