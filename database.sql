-- Database Schema for RKS Temple Of Education

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Table structure for table `admins`
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin: admin / password123 (MD5 hash)
INSERT INTO `admins` (`username`, `password`) VALUES
('admin', '482c811da5d5b4bc6d497ffa98491e38'); 

-- Table structure for table `teachers`
CREATE TABLE `teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `courses`
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon_class` varchar(100) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `gallery`
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `upload_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `notes`
CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `leads`
CREATE TABLE `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `class` varchar(50) NOT NULL,
  `request_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `blogs`
CREATE TABLE `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `author` varchar(100) DEFAULT 'Admin',
  `image_path` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `achievements`
CREATE TABLE `achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `exam_details` varchar(255) NOT NULL,
  `score` varchar(100) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `pages`
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `h1` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `faq` text NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pages` (`slug`, `category`, `class_name`, `subject`, `title`, `meta_title`, `meta_description`, `h1`, `content`, `faq`) VALUES
('class-5-foundation-program-in-ganaur', 'Foundation Program', 'Class 5', '', 'Class 5 Foundation Program in Ganaur', 'Best Class 5 Foundation Program in Ganaur', 'Join the best Class 5 foundation program in Ganaur at RKS Temple of Education.', 'Class 5 Foundation Program in Ganaur', '<p>Welcome to our Class 5 Foundation Program.</p>', '[{"question":"What is the fee?","answer":"Please contact us for fee details."}]'),
('class-9-math-coaching-in-ganaur', 'Science Coaching', 'Class 9', 'Math', 'Class 9 Math Coaching in Ganaur', 'Best Class 9 Math Coaching in Ganaur', 'Top Class 9 Math tuition in Ganaur.', 'Class 9 Math Coaching in Ganaur', '<p>Expert Math coaching for Class 9 students.</p>', '[{"question":"Do you provide study material?","answer":"Yes, we provide comprehensive study material."}]'),
('class-10-physics-tuition-in-ganaur', 'Science Coaching', 'Class 10', 'Physics', 'Class 10 Physics Tuition in Ganaur', 'Best Class 10 Physics Tuition in Ganaur', 'Enroll in Class 10 Physics tuition at RKS Temple of Education.', 'Class 10 Physics Tuition in Ganaur', '<p>Master Class 10 Physics with our expert faculty.</p>', '[]'),
('class-12-chemistry-coaching-in-ganaur', 'Science Coaching', 'Class 12', 'Chemistry', 'Class 12 Chemistry Coaching in Ganaur', 'Best Class 12 Chemistry Coaching in Ganaur', 'Top Chemistry coaching for Class 12 in Ganaur.', 'Class 12 Chemistry Coaching in Ganaur', '<p>Excel in your board exams with our Class 12 Chemistry coaching.</p>', '[]');

COMMIT;