CREATE DATABASE IF NOT EXISTS jobyaari_blog_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jobyaari_blog_db;

DROP TABLE IF EXISTS blogs;
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    short_description TEXT NULL,
    content LONGTEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NULL,
    published_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO admins (name, email, password) VALUES
('JobYaari Admin', 'admin@jobyaari.com', '$2y$12$Du1ftc3VXIX.0Er26zBiieKX.KXNDza/fzY/OK2gHgR4x36inSjWW');

INSERT INTO blogs (title, short_description, content, category, image_path, published_date) VALUES
(
    'Railway Recruitment Admit Card 2026 Released',
    'Candidates can download their admit card from the official portal using their registration number and date of birth.',
    'The Railway Recruitment Admit Card 2026 has been released for candidates appearing in the upcoming examination. Applicants should download the admit card before the examination date and verify details such as name, roll number, examination center, reporting time, and instructions. Candidates must carry a printed copy of the admit card along with a valid photo ID proof. It is recommended to reach the exam center early to avoid last-minute issues. Any mismatch in details should be reported to the concerned authority immediately.',
    'Admit Card',
    'assets/images/admit-card.svg',
    '2026-06-01'
),
(
    'State Police Constable Result 2026 Announced',
    'The written examination result is now available. Shortlisted candidates can check the next stage details online.',
    'The State Police Constable Result 2026 has been announced for candidates who appeared in the written test. Candidates can check their marks and selection status through the official result link. Those who qualify will move to the physical efficiency test and document verification rounds. Candidates should keep their application number ready while checking the result. The final selection will depend on performance in all required stages and successful verification of documents.',
    'Result',
    'assets/images/result.svg',
    '2026-05-28'
),
(
    'SSC Exam Notification 2026: Application Dates and Eligibility',
    'SSC has released a new notification including application dates, eligibility criteria, and important instructions.',
    'The latest SSC Exam Notification 2026 includes important details regarding eligibility, application dates, fee payment, correction window, and selection process. Interested candidates should read the notification carefully before applying. The application form must be submitted before the last date, and candidates should upload the required documents in the prescribed format. This recruitment update is useful for students preparing for government job opportunities across different departments.',
    'Notification',
    'assets/images/notification.svg',
    '2026-05-22'
),
(
    'University Semester Exam Time Table Published',
    'The semester examination schedule has been published for undergraduate students. Students should check subject-wise dates.',
    'The University Semester Exam Time Table has been published for undergraduate courses. Students should check the complete schedule and prepare according to the subject-wise exam dates. The timetable includes examination day, timing, subject code, and important instructions. Students are advised to carry their hall ticket and college ID card during exams. Any updates or changes in the timetable will be notified through the official university website.',
    'Exam Notice',
    'assets/images/exam.svg',
    '2026-05-18'
);
