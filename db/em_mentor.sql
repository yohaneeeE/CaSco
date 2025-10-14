-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 09:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `em_mentor`
--

-- --------------------------------------------------------

--
-- Table structure for table `careers`
--

CREATE TABLE `careers` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `skills` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `careers`
--

INSERT INTO `careers` (`id`, `title`, `category`, `description`, `skills`) VALUES
(1, 'Software Developer', 'Software & Web Development', 'Designs and builds software applications for various platforms.', 'Programming, Problem Solving, OOP, Version Control'),
(2, 'Web Developer', 'Software & Web Development', 'Develops and maintains websites and web applications.', 'HTML, CSS, JavaScript, PHP, React, Node.js'),
(3, 'Game Developer', 'Software & Web Development', 'Creates interactive video games for consoles, PCs, and mobile.', 'Unity, C#, Game Design, 3D Modeling'),
(4, 'Mobile App Developer', 'Software & Web Development', 'Builds applications for Android and iOS platforms.', 'Java, Kotlin, Swift, Flutter, React Native'),
(5, 'Full Stack Developer', 'Software & Web Development', 'Works on both client-side and server-side of web development.', 'HTML, CSS, JavaScript, Node.js, SQL, MongoDB'),
(6, 'Frontend Developer', 'Software & Web Development', 'Focuses on the visual and interactive parts of websites.', 'React, Angular, Vue.js, UX Design'),
(7, 'Backend Developer', 'Software & Web Development', 'Handles server-side logic, databases, and APIs.', 'PHP, Python, Node.js, SQL, API Integration'),
(8, 'UI/UX Designer', 'Software & Web Development', 'Designs intuitive user interfaces and ensures excellent user experiences.', 'Figma, Adobe XD, Prototyping, Wireframing'),
(9, 'Data Scientist', 'Data & Artificial Intelligence', 'Analyzes complex data to uncover patterns and build predictive models.', 'Python, R, Machine Learning, Statistics'),
(10, 'Data Analyst', 'Data & Artificial Intelligence', 'Uses data to generate insights that help businesses make decisions.', 'Excel, SQL, Power BI, Tableau'),
(11, 'AI Engineer', 'Data & Artificial Intelligence', 'Designs and develops artificial intelligence systems.', 'Deep Learning, Neural Networks, Python, TensorFlow'),
(12, 'Machine Learning Engineer', 'Data & Artificial Intelligence', 'Builds machine learning pipelines and production-ready models.', 'Scikit-learn, MLops, Data Engineering'),
(13, 'Business Intelligence Analyst', 'Data & Artificial Intelligence', 'Analyzes business data and builds dashboards to support decision-making.', 'Power BI, SQL, Excel, KPIs'),
(14, 'Cybersecurity Analyst', 'Cybersecurity & Networking', 'Protects systems and networks from cyber threats.', 'Network Security, Threat Detection, SIEM, Firewalls'),
(15, 'Network Administrator', 'Cybersecurity & Networking', 'Maintains and troubleshoots computer networks and infrastructure.', 'TCP/IP, Cisco, Windows Server, Network Monitoring'),
(16, 'Information Security Specialist', 'Cybersecurity & Networking', 'Implements policies and tools to secure sensitive data.', 'Risk Management, Encryption, ISO Standards'),
(17, 'Ethical Hacker', 'Cybersecurity & Networking', 'Performs authorized testing to find and fix vulnerabilities.', 'Penetration Testing, Kali Linux, Metasploit'),
(18, 'Cloud Engineer', 'Cloud & Systems Administration', 'Designs and manages cloud infrastructure and services.', 'AWS, Azure, GCP, DevOps, Docker'),
(19, 'Systems Administrator', 'Cloud & Systems Administration', 'Ensures reliable operation of computer systems and servers.', 'Linux, Windows Server, Bash, Monitoring Tools'),
(20, 'DevOps Engineer', 'Cloud & Systems Administration', 'Bridges software development and IT operations to improve deployment.', 'CI/CD, Jenkins, Git, Kubernetes'),
(21, 'IT Support Specialist', 'Cloud & Systems Administration', 'Resolves technical issues for hardware and software users.', 'Helpdesk, Troubleshooting, Windows, Communication'),
(22, 'Blockchain Developer', 'Emerging Technologies', 'Builds decentralized apps and smart contracts using blockchain.', 'Solidity, Ethereum, Web3, Cryptography'),
(23, 'AR/VR Developer', 'Emerging Technologies', 'Creates immersive augmented and virtual reality experiences.', 'Unity, Unreal Engine, C#, 3D Modeling'),
(24, 'IoT Specialist', 'Emerging Technologies', 'Develops connected devices and manages sensor data.', 'Embedded Systems, MQTT, Arduino, Raspberry Pi'),
(25, 'IT Project Manager', 'IT Management & Other Specializations', 'Leads IT projects from planning to delivery, ensuring success.', 'Agile, Scrum, Jira, Communication');

-- --------------------------------------------------------

--
-- Table structure for table `career_certificates`
--

CREATE TABLE `career_certificates` (
  `id` int(11) NOT NULL,
  `career_id` int(11) NOT NULL,
  `certificate_title` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `skills` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `career_certificates`
--

INSERT INTO `career_certificates` (`id`, `career_id`, `certificate_title`, `provider`, `description`, `skills`) VALUES
(1, 1, 'Oracle Certified Java Programmer', 'Oracle', 'Certification in Java programming for enterprise applications.', 'Java, OOP, Debugging'),
(2, 1, 'Microsoft Certified: Azure Developer Associate', 'Microsoft', 'Validates cloud application development skills using Azure.', 'Cloud Development, APIs, C#'),
(3, 1, 'Certified Software Development Professional (CSDP)', 'IEEE', 'Professional-level certification in software engineering and development practices.', 'SDLC, Design Patterns, Testing'),
(4, 2, 'FreeCodeCamp Responsive Web Design', 'FreeCodeCamp', 'Covers HTML, CSS, and responsive design principles.', 'HTML, CSS, Flexbox, Grid'),
(5, 2, 'Meta Front-End Developer Certificate', 'Coursera/Meta', 'Teaches building websites with React and modern front-end technologies.', 'React, JavaScript, UI'),
(6, 2, 'W3C Front-End Web Developer Certificate', 'edX/W3C', 'Covers HTML5, CSS, and JavaScript fundamentals.', 'Web Standards, Accessibility, JS'),
(7, 3, 'Unity Certified Associate: Game Developer', 'Unity', 'Official certification for building games with Unity engine.', 'Unity, C#, Game Mechanics'),
(8, 3, 'Unreal Engine Developer Certification', 'Epic Games', 'Covers Unreal Engine game design and development.', 'Unreal, C++, Blueprints'),
(9, 3, 'Certified Game Designer', 'IGDA', 'Validates skills in storytelling, mechanics, and design principles.', 'Level Design, Storytelling, UX'),
(10, 4, 'Google Associate Android Developer', 'Google', 'Certification for building Android apps in Java/Kotlin.', 'Android SDK, Kotlin, UI'),
(11, 4, 'Apple Certified iOS App Developer', 'Apple', 'Professional certificate for building iOS apps.', 'Swift, iOS Frameworks, Xcode'),
(12, 4, 'Flutter Developer Certificate', 'Google/Udemy', 'Covers cross-platform app development using Flutter.', 'Dart, Widgets, Cross-Platform'),
(13, 5, 'MongoDB Certified Developer Associate', 'MongoDB', 'Certification in database development with MongoDB.', 'NoSQL, Databases, CRUD'),
(14, 5, 'The Odin Project Full Stack Certificate', 'The Odin Project', 'Covers front-end and back-end web development.', 'HTML, CSS, Node.js, SQL'),
(15, 5, 'Meta Full Stack Developer Certificate', 'Coursera/Meta', 'Full stack certificate program for web developers.', 'React, Node.js, API, SQL'),
(16, 6, 'Frontend Developer Nanodegree', 'Udacity', 'Covers advanced frontend development with JavaScript.', 'HTML, CSS, JavaScript, DOM'),
(17, 6, 'Google UX Design Certificate', 'Google', 'Focuses on UX principles and UI design.', 'Wireframes, Prototyping, UX Research'),
(18, 6, 'React Developer Certificate', 'Codecademy', 'Certification in React front-end development.', 'React, JSX, Hooks'),
(19, 7, 'Node.js Developer Certificate', 'OpenJS Foundation', 'Certification in Node.js server-side development.', 'Node.js, APIs, Express'),
(20, 7, 'PHP Zend Certified Engineer', 'Zend', 'Validates PHP and backend development skills.', 'PHP, SQL, Security'),
(21, 7, 'Python Backend Developer Certificate', 'Udemy', 'Backend development certification using Python.', 'Flask, Django, APIs'),
(22, 8, 'Adobe Certified Professional: UX Design', 'Adobe', 'Professional UI/UX certificate.', 'Adobe XD, Figma, UX'),
(23, 8, 'Google UX Design Professional Certificate', 'Google/Coursera', 'Foundational course in UX/UI.', 'User Research, Prototyping'),
(24, 8, 'Interaction Design Certificate', 'Interaction Design Foundation', 'Covers human-centered design.', 'UX, Usability, Prototyping'),
(25, 9, 'IBM Data Science Professional Certificate', 'Coursera/IBM', 'Covers data science tools and machine learning.', 'Python, Pandas, ML'),
(26, 9, 'Microsoft Certified: Data Scientist Associate', 'Microsoft', 'Certification for applied data science.', 'Azure ML, Python, Data Analysis'),
(27, 9, 'Google Cloud Professional Data Scientist', 'Google Cloud', 'Covers ML and AI deployment on GCP.', 'TensorFlow, MLops'),
(28, 10, 'Google Data Analytics Certificate', 'Google', 'Data analytics for business decision making.', 'SQL, Tableau, Excel'),
(29, 10, 'Microsoft Power BI Data Analyst Associate', 'Microsoft', 'Focus on dashboards and reporting.', 'Power BI, Data Visualization'),
(30, 10, 'Certified Business Data Analyst (CBDA)', 'IIBA', 'Global certification for data analysis.', 'Requirements, Analytics'),
(31, 11, 'TensorFlow Developer Certificate', 'Google', 'Certification in deep learning models.', 'TensorFlow, Neural Networks'),
(32, 11, 'AWS Certified Machine Learning – Specialty', 'Amazon AWS', 'Covers ML workflows on AWS.', 'SageMaker, MLops'),
(33, 11, 'Artificial Intelligence Engineer Certificate', 'Simplilearn', 'Professional AI engineer course.', 'Deep Learning, AI Models'),
(34, 12, 'Machine Learning Specialization', 'Coursera/Stanford', 'Taught by Andrew Ng on ML principles.', 'ML Algorithms, Supervised Learning'),
(35, 12, 'Certified Machine Learning Engineer', 'DataCamp', 'Certification in ML development.', 'Python, Scikit-learn'),
(36, 12, 'ML Ops Certification', 'Udacity', 'Productionizing ML pipelines.', 'CI/CD, ML Ops'),
(37, 13, 'Certified Business Intelligence Professional (CBIP)', 'TDWI', 'Certification in BI reporting.', 'BI, Analytics, Dashboards'),
(38, 13, 'Tableau Desktop Specialist', 'Tableau', 'Certification in data visualization.', 'Dashboards, BI Tools'),
(39, 13, 'Qlik Sense Business Analyst', 'Qlik', 'Certificate for BI dashboards.', 'Data Viz, BI'),
(40, 14, 'CompTIA Security+', 'CompTIA', 'Foundational cybersecurity certification.', 'Security, Networks, Threats'),
(41, 14, 'Certified Ethical Hacker (CEH)', 'EC-Council', 'Certification in penetration testing.', 'Hacking, Testing, Security'),
(42, 14, 'Cisco CyberOps Associate', 'Cisco', 'Cybersecurity operations certification.', 'SOC, Monitoring, Security'),
(43, 15, 'Cisco CCNA', 'Cisco', 'Networking certification.', 'TCP/IP, Routing, Switching'),
(44, 15, 'CompTIA Network+', 'CompTIA', 'General networking certificate.', 'Networking, Troubleshooting'),
(45, 15, 'Juniper JNCIA', 'Juniper', 'Network fundamentals certification.', 'Networking, Routing'),
(46, 16, 'CISM (Certified Information Security Manager)', 'ISACA', 'Certification in managing info security.', 'Security Policies, Governance'),
(47, 16, 'CISA (Certified Information Systems Auditor)', 'ISACA', 'Certification in auditing IS systems.', 'Auditing, Risk Management'),
(48, 16, 'ISO 27001 Lead Implementer', 'PECB', 'Certification in ISO security standards.', 'ISO, Security'),
(49, 17, 'Offensive Security Certified Professional (OSCP)', 'OffSec', 'Hands-on penetration testing.', 'Ethical Hacking, Linux'),
(50, 17, 'Certified Penetration Tester (CPT)', 'IACRB', 'Professional penetration testing cert.', 'PenTest, Kali Linux'),
(51, 17, 'GIAC Penetration Tester (GPEN)', 'GIAC', 'Certification in penetration testing.', 'Security, PenTest'),
(52, 18, 'AWS Certified Solutions Architect', 'Amazon AWS', 'Certification in cloud architecture.', 'AWS, Cloud'),
(53, 18, 'Microsoft Certified: Azure Solutions Architect', 'Microsoft', 'Azure cloud certification.', 'Azure, Cloud Infrastructure'),
(54, 18, 'Google Cloud Architect', 'Google', 'Certification for GCP cloud architecture.', 'GCP, Cloud'),
(55, 19, 'Linux Professional Institute Certification (LPIC-1)', 'LPI', 'Linux system administration certification.', 'Linux, Bash'),
(56, 19, 'Microsoft Certified: Windows Server Administrator', 'Microsoft', 'Windows server certification.', 'Windows Server, Admin'),
(57, 19, 'Red Hat Certified System Administrator (RHCSA)', 'Red Hat', 'Linux sysadmin certification.', 'Linux, Admin'),
(58, 20, 'Certified Kubernetes Administrator (CKA)', 'CNCF', 'Certification in Kubernetes admin.', 'Kubernetes, Containers'),
(59, 20, 'Docker Certified Associate', 'Docker', 'Certification for container tech.', 'Docker, Containers'),
(60, 20, 'Jenkins Engineer Certification', 'CloudBees', 'CI/CD pipeline certification.', 'Jenkins, CI/CD'),
(61, 21, 'CompTIA IT Fundamentals+', 'CompTIA', 'Entry-level IT support certificate.', 'IT Basics, Troubleshooting'),
(62, 21, 'HDI Support Center Analyst', 'HDI', 'Certification for IT support helpdesk.', 'Helpdesk, Communication'),
(63, 21, 'Microsoft 365 Certified: Modern Desktop Administrator Associate', 'Microsoft', 'Certification for IT support admins.', 'Windows, Office 365'),
(64, 22, 'Ethereum Developer Certification', 'Consensys', 'Blockchain smart contracts.', 'Ethereum, Solidity'),
(65, 22, 'Certified Blockchain Developer', 'Blockchain Council', 'Blockchain dev certification.', 'Blockchain, DApps'),
(66, 22, 'IBM Blockchain Foundation Developer', 'IBM', 'Certification for blockchain dev.', 'Hyperledger, Blockchain'),
(67, 23, 'Unity Certified VR Developer', 'Unity', 'Certification in VR dev.', 'Unity, VR, AR'),
(68, 23, 'Unreal AR/VR Developer Certificate', 'Epic Games', 'AR/VR dev certification.', 'Unreal, AR, VR'),
(69, 23, 'Google ARCore Certification', 'Google', 'AR/VR with ARCore.', 'AR, VR, Unity'),
(70, 24, 'IoT Developer Certification', 'IoT Academy', 'Certification in IoT dev.', 'IoT, Sensors'),
(71, 24, 'Cisco IoT Fundamentals', 'Cisco', 'Cisco IoT certificate.', 'IoT, Networking'),
(72, 24, 'Microsoft Azure IoT Developer', 'Microsoft', 'Certification in Azure IoT.', 'IoT, Cloud'),
(73, 25, 'PMP (Project Management Professional)', 'PMI', 'Certification in project management.', 'Project Planning, Agile'),
(74, 25, 'Certified ScrumMaster (CSM)', 'Scrum Alliance', 'Certification in Agile Scrum.', 'Agile, Scrum'),
(75, 25, 'PRINCE2 Foundation', 'AXELOS', 'Project management methodology.', 'PRINCE2, Agile');

-- --------------------------------------------------------

--
-- Table structure for table `career_roadmaps`
--

CREATE TABLE `career_roadmaps` (
  `id` int(11) NOT NULL,
  `career_id` int(11) NOT NULL,
  `step_number` int(11) NOT NULL,
  `step_title` varchar(255) NOT NULL,
  `step_detail` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `career_roadmaps`
--

INSERT INTO `career_roadmaps` (`id`, `career_id`, `step_number`, `step_title`, `step_detail`) VALUES
(11, 1, 1, 'Learn Programming Fundamentals', 'Master core programming concepts such as variables, data types, and control structures.'),
(12, 1, 2, 'Understand Object-Oriented Programming', 'Learn principles of OOP like classes, objects, inheritance, and polymorphism.'),
(13, 1, 3, 'Practice Version Control', 'Get familiar with Git and GitHub for managing code repositories.'),
(14, 1, 4, 'Build Small Projects', 'Create software applications to apply what you learned and build your portfolio.'),
(15, 1, 5, 'Apply for Internships', 'Gain real-world experience by working in software development internships.'),
(16, 2, 1, 'Learn HTML & CSS', 'Understand the basics of website structure and styling.'),
(17, 2, 2, 'Master JavaScript Fundamentals', 'Learn DOM manipulation and client-side scripting.'),
(18, 2, 3, 'Get Familiar with Frontend Frameworks', 'Explore React, Angular, or Vue.js.'),
(19, 2, 4, 'Learn Backend Technologies', 'Understand server-side languages like Node.js or PHP and databases.'),
(20, 2, 5, 'Deploy Websites', 'Practice deploying projects using Netlify, Heroku, or similar services.'),
(21, 3, 1, 'Learn Programming Basics', 'Focus on C# or C++ as primary game development languages.'),
(22, 3, 2, 'Understand Game Engines', 'Explore Unity or Unreal Engine for game creation.'),
(23, 3, 3, 'Study Game Design Principles', 'Learn about level design, storytelling, and mechanics.'),
(24, 3, 4, 'Build Simple Games', 'Create basic games to practice development skills.'),
(25, 3, 5, 'Test and Debug Games', 'Learn testing techniques and debugging for game applications.'),
(26, 4, 1, 'Learn Platform-specific Languages', 'Master Java/Kotlin for Android or Swift for iOS development.'),
(27, 4, 2, 'Understand Mobile UI/UX Design', 'Focus on creating user-friendly mobile interfaces.'),
(28, 4, 3, 'Learn Cross-Platform Tools', 'Explore Flutter or React Native for multi-platform development.'),
(29, 4, 4, 'Build Mobile Apps', 'Develop real-world mobile applications to add to your portfolio.'),
(30, 4, 5, 'Publish Apps', 'Learn the process of submitting apps to Google Play and App Store.'),
(31, 5, 1, 'Master Frontend Basics', 'Learn HTML, CSS, and JavaScript fundamentals.'),
(32, 5, 2, 'Learn Backend Development', 'Understand server-side programming using Node.js, PHP, or Python.'),
(33, 5, 3, 'Work with Databases', 'Gain skills in SQL and NoSQL databases like MongoDB.'),
(34, 5, 4, 'Build Full Projects', 'Create full stack applications integrating frontend and backend.'),
(35, 5, 5, 'Deploy Applications', 'Deploy apps using cloud services or VPS.'),
(36, 6, 1, 'Master HTML, CSS & JavaScript', 'Gain deep understanding of core frontend technologies.'),
(37, 6, 2, 'Learn Modern Frameworks', 'Explore React, Angular, or Vue.js.'),
(38, 6, 3, 'Practice Responsive Design', 'Make web apps work on all screen sizes and devices.'),
(39, 6, 4, 'Understand UX/UI Principles', 'Focus on creating user-friendly interfaces.'),
(40, 6, 5, 'Build Interactive Projects', 'Develop complex frontend projects to showcase skills.'),
(41, 7, 1, 'Learn Server-Side Languages', 'Focus on PHP, Python, or Node.js for backend programming.'),
(42, 7, 2, 'Understand Databases', 'Master SQL and NoSQL databases like MySQL and MongoDB.'),
(43, 7, 3, 'Develop APIs', 'Create RESTful or GraphQL APIs for communication between client and server.'),
(44, 7, 4, 'Handle Authentication & Security', 'Implement secure login, authorization, and data protection.'),
(45, 7, 5, 'Deploy Backend Services', 'Deploy backend servers on cloud or VPS.'),
(46, 8, 1, 'Learn Design Principles', 'Study color theory, typography, and layout.'),
(47, 8, 2, 'Master Design Tools', 'Get skilled in Figma, Adobe XD, or Sketch.'),
(48, 8, 3, 'Practice Wireframing & Prototyping', 'Create blueprints and interactive mockups.'),
(49, 8, 4, 'Understand User Research', 'Learn to gather user feedback and perform usability testing.'),
(50, 8, 5, 'Build Design Portfolio', 'Showcase UI/UX projects to demonstrate your skills.'),
(51, 9, 1, 'Learn Python or R', 'Master languages used for data analysis.'),
(52, 9, 2, 'Study Statistics and Probability', 'Understand fundamentals for analyzing data.'),
(53, 9, 3, 'Explore Machine Learning', 'Learn algorithms and build predictive models.'),
(54, 9, 4, 'Practice Data Visualization', 'Use tools like Matplotlib, Seaborn, or Tableau.'),
(55, 9, 5, 'Work on Real Datasets', 'Complete projects with datasets to showcase your skills.'),
(56, 10, 1, 'Learn Excel and SQL', 'Master spreadsheet skills and database querying.'),
(57, 10, 2, 'Understand Data Cleaning', 'Practice preparing datasets for analysis.'),
(58, 10, 3, 'Study Data Visualization Tools', 'Use Power BI, Tableau, or similar tools.'),
(59, 10, 4, 'Analyze Business Metrics', 'Learn to interpret KPIs and generate reports.'),
(60, 10, 5, 'Present Insights', 'Develop skills to communicate findings effectively.'),
(61, 11, 1, 'Master Python Programming', 'Focus on Python as main language for AI development.'),
(62, 11, 2, 'Learn Deep Learning Basics', 'Study neural networks and frameworks like TensorFlow.'),
(63, 11, 3, 'Explore Natural Language Processing', 'Understand techniques to process and analyze text data.'),
(64, 11, 4, 'Build AI Models', 'Create and train machine learning models for real tasks.'),
(65, 11, 5, 'Deploy AI Solutions', 'Learn how to integrate AI models into production.'),
(66, 12, 1, 'Learn Python and ML Libraries', 'Master scikit-learn, pandas, and NumPy.'),
(67, 12, 2, 'Understand ML Algorithms', 'Study supervised and unsupervised learning techniques.'),
(68, 12, 3, 'Practice Data Engineering', 'Handle large datasets and data pipelines.'),
(69, 12, 4, 'Implement ML Ops', 'Learn deployment and monitoring of ML models.'),
(70, 12, 5, 'Build Production Pipelines', 'Create scalable ML workflows.'),
(71, 13, 1, 'Learn SQL and Excel', 'Master data extraction and manipulation.'),
(72, 13, 2, 'Understand KPIs and Metrics', 'Know which metrics are important for business decisions.'),
(73, 13, 3, 'Master BI Tools', 'Use Power BI, Tableau, or Looker for data visualization.'),
(74, 13, 4, 'Build Dashboards', 'Create interactive business reports.'),
(75, 13, 5, 'Present Findings', 'Communicate insights to stakeholders.'),
(76, 14, 1, 'Learn Networking Basics', 'Understand TCP/IP, OSI model, and protocols.'),
(77, 14, 2, 'Study Security Fundamentals', 'Explore encryption, firewalls, and security policies.'),
(78, 14, 3, 'Practice Threat Detection', 'Use SIEM tools and learn to identify threats.'),
(79, 14, 4, 'Perform Vulnerability Assessments', 'Learn to scan and assess system vulnerabilities.'),
(80, 14, 5, 'Gain Certifications', 'Consider certifications like CompTIA Security+, CEH.'),
(81, 15, 1, 'Learn Networking Protocols', 'Master TCP/IP, DNS, DHCP, and routing basics.'),
(82, 15, 2, 'Get Familiar with Cisco and Windows Server', 'Understand enterprise network and server management.'),
(83, 15, 3, 'Practice Network Monitoring', 'Use tools to monitor and troubleshoot networks.'),
(84, 15, 4, 'Implement Security Policies', 'Manage firewall and VPN configurations.'),
(85, 15, 5, 'Maintain Network Hardware', 'Manage switches, routers, and network devices.'),
(86, 16, 1, 'Learn Risk Management', 'Understand risk assessment and mitigation strategies.'),
(87, 16, 2, 'Study Encryption Techniques', 'Master data protection methods and cryptography.'),
(88, 16, 3, 'Implement Security Standards', 'Apply ISO/IEC 27001 and other compliance frameworks.'),
(89, 16, 4, 'Deploy Security Tools', 'Manage antivirus, firewalls, and IDS/IPS systems.'),
(90, 16, 5, 'Conduct Security Audits', 'Evaluate and improve organizational security posture.'),
(91, 17, 1, 'Learn Networking and Security Basics', 'Understand network protocols and security fundamentals.'),
(92, 17, 2, 'Master Penetration Testing Tools', 'Use Kali Linux, Metasploit, and other tools.'),
(93, 17, 3, 'Practice Vulnerability Scanning', 'Identify weaknesses in systems and applications.'),
(94, 17, 4, 'Conduct Ethical Hacks', 'Perform authorized penetration tests to expose vulnerabilities.'),
(95, 17, 5, 'Get Certified', 'Aim for CEH or OSCP certifications.'),
(96, 18, 1, 'Learn Cloud Concepts', 'Understand IaaS, PaaS, SaaS and cloud service models.'),
(97, 18, 2, 'Get Hands-on with AWS, Azure, or GCP', 'Explore the major cloud providers.'),
(98, 18, 3, 'Master Infrastructure as Code', 'Use Terraform or CloudFormation.'),
(99, 18, 4, 'Learn Containerization', 'Understand Docker and Kubernetes basics.'),
(100, 18, 5, 'Implement CI/CD Pipelines', 'Automate deployments using Jenkins, GitLab CI, or others.'),
(101, 19, 1, 'Learn Linux and Windows Server Administration', 'Understand OS installation, configuration, and management.'),
(102, 19, 2, 'Master Shell Scripting', 'Automate tasks using Bash or PowerShell.'),
(103, 19, 3, 'Configure Network Services', 'Manage DNS, DHCP, and Active Directory.'),
(104, 19, 4, 'Implement Monitoring Solutions', 'Use Nagios, Zabbix, or similar tools.'),
(105, 19, 5, 'Manage Security and Backups', 'Ensure system security and data recovery.'),
(106, 20, 1, 'Learn Software Development and IT Operations Basics', 'Understand the DevOps culture and tools.'),
(107, 20, 2, 'Master CI/CD Tools', 'Use Jenkins, GitLab CI, or CircleCI for automation.'),
(108, 20, 3, 'Practice Containerization', 'Work with Docker and Kubernetes.'),
(109, 20, 4, 'Implement Infrastructure as Code', 'Use Terraform or Ansible.'),
(110, 20, 5, 'Monitor and Optimize Pipelines', 'Ensure reliability and efficiency of deployment workflows.'),
(111, 21, 1, 'Learn Database Fundamentals', 'Understand relational and non-relational databases.'),
(112, 21, 2, 'Master SQL', 'Write complex queries and optimize them.'),
(113, 21, 3, 'Practice Backup and Recovery', 'Ensure data availability and disaster recovery plans.'),
(114, 21, 4, 'Implement Security Measures', 'Manage access control and encryption.'),
(115, 21, 5, 'Monitor Database Performance', 'Use tools to optimize database efficiency.'),
(116, 22, 1, 'Learn Software Testing Basics', 'Understand different types of testing: manual, automated, regression.'),
(117, 22, 2, 'Master Testing Tools', 'Use Selenium, JIRA, or TestRail.'),
(118, 22, 3, 'Write Test Cases and Plans', 'Design thorough test scenarios and documentation.'),
(119, 22, 4, 'Practice Automated Testing', 'Develop scripts to automate test execution.'),
(120, 22, 5, 'Report and Track Bugs', 'Communicate issues clearly and track their resolution.'),
(121, 23, 1, 'Improve Writing Skills', 'Master clear, concise technical writing.'),
(122, 23, 2, 'Learn Documentation Tools', 'Use Markdown, Confluence, or MadCap Flare.'),
(123, 23, 3, 'Understand Technical Concepts', 'Gain basic understanding of software and hardware.'),
(124, 23, 4, 'Practice Creating Manuals and Guides', 'Develop user guides, API docs, and tutorials.'),
(125, 23, 5, 'Collaborate with Developers', 'Work closely with engineers to gather accurate information.'),
(126, 24, 1, 'Learn Project Management Fundamentals', 'Understand scope, time, cost, and quality management.'),
(127, 24, 2, 'Master Project Management Tools', 'Use MS Project, Jira, or Trello.'),
(128, 24, 3, 'Develop Leadership and Communication Skills', 'Manage teams effectively and communicate clearly.'),
(129, 24, 4, 'Understand Software Development Lifecycle', 'Know Agile, Scrum, and Waterfall methodologies.'),
(130, 24, 5, 'Manage Projects End-to-End', 'Plan, execute, and close IT projects successfully.'),
(131, 25, 1, 'Understand Business and IT Alignment', 'Learn how IT supports business goals.'),
(132, 25, 2, 'Analyze System Requirements', 'Gather and document requirements from stakeholders.'),
(133, 25, 3, 'Design IT Solutions', 'Create system designs that solve business problems.'),
(134, 25, 4, 'Coordinate with Developers and Users', 'Facilitate communication and implementation.'),
(135, 25, 5, 'Test and Improve Systems', 'Evaluate and optimize system performance.');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `career_id` int(11) NOT NULL,
  `certificate_title` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `skills` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `career_id`, `certificate_title`, `provider`, `description`, `skills`) VALUES
(1, 1, 'Oracle Certified Java Programmer', 'Oracle', 'Certification in Java programming for enterprise applications.', 'Java, OOP, Debugging'),
(2, 1, 'Microsoft Certified: Azure Developer Associate', 'Microsoft', 'Validates cloud application development skills using Azure.', 'Cloud Development, APIs, C#'),
(3, 1, 'Certified Software Development Professional (CSDP)', 'IEEE', 'Professional-level certification in software engineering practices.', 'SDLC, Design Patterns, Testing'),
(4, 2, 'FreeCodeCamp Responsive Web Design', 'FreeCodeCamp', 'Covers HTML, CSS, and responsive design principles.', 'HTML, CSS, Flexbox, Grid'),
(5, 2, 'Meta Front-End Developer Certificate', 'Coursera/Meta', 'Teaches building websites with React and modern front-end tools.', 'React, JavaScript, UI'),
(6, 2, 'W3C Front-End Web Developer Certificate', 'edX/W3C', 'Covers HTML5, CSS, and JavaScript fundamentals.', 'Web Standards, Accessibility, JS'),
(7, 3, 'Unity Certified Associate: Game Developer', 'Unity', 'Official certification for building games with Unity.', 'Unity, C#, Game Mechanics'),
(8, 3, 'Unreal Engine Developer Certification', 'Epic Games', 'Covers Unreal Engine game design and development.', 'Unreal, C++, Blueprints'),
(9, 3, 'Certified Game Designer', 'IGDA', 'Validates skills in storytelling, mechanics, and design.', 'Level Design, Storytelling, UX'),
(10, 4, 'Google Associate Android Developer', 'Google', 'Certification for building Android apps in Java/Kotlin.', 'Android SDK, Kotlin, UI'),
(11, 4, 'Apple Certified iOS App Developer', 'Apple', 'Professional certificate for building iOS apps.', 'Swift, iOS Frameworks, Xcode'),
(12, 4, 'Flutter Developer Certificate', 'Google/Udemy', 'Covers cross-platform app development using Flutter.', 'Dart, Widgets, Cross-Platform'),
(13, 5, 'MongoDB Certified Developer Associate', 'MongoDB', 'Certification in database development with MongoDB.', 'NoSQL, Databases, CRUD'),
(14, 5, 'The Odin Project Full Stack Certificate', 'The Odin Project', 'Covers front-end and back-end web development.', 'HTML, CSS, Node.js, SQL'),
(15, 5, 'Meta Full Stack Developer Certificate', 'Coursera/Meta', 'Full stack certificate program for web developers.', 'React, Node.js, API, SQL'),
(16, 6, 'Frontend Developer Nanodegree', 'Udacity', 'Covers advanced frontend development with JavaScript.', 'HTML, CSS, JavaScript, DOM'),
(17, 6, 'Google UX Design Certificate', 'Google', 'Focuses on UX principles and UI design.', 'Wireframes, Prototyping, UX Research'),
(18, 6, 'React Developer Certificate', 'Codecademy', 'Certification in React front-end development.', 'React, JSX, Hooks'),
(19, 7, 'Node.js Developer Certificate', 'OpenJS Foundation', 'Certification in Node.js server-side development.', 'Node.js, APIs, Express'),
(20, 7, 'PHP Zend Certified Engineer', 'Zend', 'Validates PHP and backend development skills.', 'PHP, SQL, Security'),
(21, 7, 'Python Backend Developer Certificate', 'Udemy', 'Backend development certification using Python.', 'Flask, Django, APIs'),
(22, 8, 'Adobe Certified Professional: UX Design', 'Adobe', 'Professional UI/UX certificate.', 'Adobe XD, Figma, UX'),
(23, 8, 'Google UX Design Professional Certificate', 'Google/Coursera', 'Foundational course in UX/UI.', 'User Research, Prototyping'),
(24, 8, 'Interaction Design Certificate', 'Interaction Design Foundation', 'Covers human-centered design.', 'UX, Usability, Prototyping'),
(25, 9, 'IBM Data Science Professional Certificate', 'Coursera/IBM', 'Covers data science tools and machine learning.', 'Python, Pandas, ML'),
(26, 9, 'Microsoft Certified: Data Scientist Associate', 'Microsoft', 'Certification for applied data science.', 'Azure ML, Python, Data Analysis'),
(27, 9, 'Google Cloud Professional Data Scientist', 'Google Cloud', 'Covers ML and AI deployment on GCP.', 'TensorFlow, MLops'),
(28, 10, 'Google Data Analytics Certificate', 'Google', 'Data analytics for business decision making.', 'SQL, Tableau, Excel'),
(29, 10, 'Microsoft Power BI Data Analyst Associate', 'Microsoft', 'Focus on dashboards and reporting.', 'Power BI, Data Visualization'),
(30, 10, 'Certified Business Data Analyst (CBDA)', 'IIBA', 'Global certification for data analysis.', 'Requirements, Analytics'),
(31, 11, 'TensorFlow Developer Certificate', 'Google', 'Certification in deep learning models.', 'TensorFlow, Neural Networks'),
(32, 11, 'AWS Certified Machine Learning – Specialty', 'Amazon AWS', 'Covers ML workflows on AWS.', 'SageMaker, MLops'),
(33, 11, 'Artificial Intelligence Engineer Certificate', 'Simplilearn', 'Professional AI engineer course.', 'Deep Learning, AI Models'),
(34, 12, 'Machine Learning Specialization', 'Coursera/Stanford', 'Taught by Andrew Ng on ML principles.', 'ML Algorithms, Supervised Learning'),
(35, 12, 'Certified Machine Learning Engineer', 'DataCamp', 'Certification in ML development.', 'Python, Scikit-learn'),
(36, 12, 'ML Ops Certification', 'Udacity', 'Productionizing ML pipelines.', 'CI/CD, ML Ops'),
(37, 13, 'Certified Business Intelligence Professional (CBIP)', 'TDWI', 'Certification in BI reporting.', 'BI, Analytics, Dashboards'),
(38, 13, 'Tableau Desktop Specialist', 'Tableau', 'Certification in data visualization.', 'Dashboards, BI Tools'),
(39, 13, 'Qlik Sense Business Analyst', 'Qlik', 'Certificate for BI dashboards.', 'Data Viz, BI'),
(40, 14, 'CompTIA Security+', 'CompTIA', 'Foundational cybersecurity certification.', 'Security, Networks, Threats'),
(41, 14, 'Certified Ethical Hacker (CEH)', 'EC-Council', 'Certification in penetration testing.', 'Hacking, Testing, Security'),
(42, 14, 'Cisco CyberOps Associate', 'Cisco', 'Cybersecurity operations certification.', 'SOC, Monitoring, Security'),
(43, 15, 'Cisco CCNA', 'Cisco', 'Networking certification.', 'TCP/IP, Routing, Switching'),
(44, 15, 'CompTIA Network+', 'CompTIA', 'General networking certificate.', 'Networking, Troubleshooting'),
(45, 15, 'Juniper JNCIA', 'Juniper', 'Network fundamentals certification.', 'Networking, Routing'),
(46, 16, 'CISM (Certified Information Security Manager)', 'ISACA', 'Certification in managing info security.', 'Security Policies, Governance'),
(47, 16, 'CISA (Certified Information Systems Auditor)', 'ISACA', 'Certification in auditing IS systems.', 'Auditing, Risk Management'),
(48, 16, 'ISO 27001 Lead Implementer', 'PECB', 'Certification in ISO security standards.', 'ISO, Security'),
(49, 17, 'Offensive Security Certified Professional (OSCP)', 'OffSec', 'Hands-on penetration testing.', 'Ethical Hacking, Linux'),
(50, 17, 'Certified Penetration Tester (CPT)', 'IACRB', 'Professional penetration testing cert.', 'PenTest, Kali Linux'),
(51, 17, 'GIAC Penetration Tester (GPEN)', 'GIAC', 'Certification in penetration testing.', 'Security, PenTest'),
(52, 18, 'AWS Certified Solutions Architect', 'Amazon AWS', 'Certification in cloud architecture.', 'AWS, Cloud'),
(53, 18, 'Microsoft Certified: Azure Solutions Architect', 'Microsoft', 'Azure cloud certification.', 'Azure, Cloud Infrastructure'),
(54, 18, 'Google Cloud Architect', 'Google', 'Certification for GCP cloud architecture.', 'GCP, Cloud'),
(55, 19, 'Linux Professional Institute Certification (LPIC-1)', 'LPI', 'Linux system administration certification.', 'Linux, Bash'),
(56, 19, 'Microsoft Certified: Windows Server Administrator', 'Microsoft', 'Windows server certification.', 'Windows Server, Admin'),
(57, 19, 'Red Hat Certified System Administrator (RHCSA)', 'Red Hat', 'Linux sysadmin certification.', 'Linux, Admin'),
(58, 20, 'Certified Kubernetes Administrator (CKA)', 'CNCF', 'Certification in Kubernetes admin.', 'Kubernetes, Containers'),
(59, 20, 'Docker Certified Associate', 'Docker', 'Certification for container tech.', 'Docker, Containers'),
(60, 20, 'Jenkins Engineer Certification', 'CloudBees', 'CI/CD pipeline certification.', 'Jenkins, CI/CD'),
(61, 21, 'CompTIA IT Fundamentals+', 'CompTIA', 'Entry-level IT support certificate.', 'IT Basics, Troubleshooting'),
(62, 21, 'HDI Support Center Analyst', 'HDI', 'Certification for IT support helpdesk.', 'Helpdesk, Communication'),
(63, 21, 'Microsoft 365 Certified: Modern Desktop Administrator', 'Microsoft', 'Certification for IT support admins.', 'Windows, Office 365'),
(64, 22, 'Ethereum Developer Certification', 'Consensys', 'Blockchain smart contracts.', 'Ethereum, Solidity'),
(65, 22, 'Certified Blockchain Developer', 'Blockchain Council', 'Blockchain dev certification.', 'Blockchain, DApps'),
(66, 22, 'IBM Blockchain Foundation Developer', 'IBM', 'Certification for blockchain dev.', 'Hyperledger, Blockchain'),
(67, 23, 'Unity Certified VR Developer', 'Unity', 'Certification in VR dev.', 'Unity, VR, AR'),
(68, 23, 'Unreal AR/VR Developer Certificate', 'Epic Games', 'AR/VR dev certification.', 'Unreal, AR, VR'),
(69, 23, 'Google ARCore Certification', 'Google', 'AR/VR with ARCore.', 'AR, VR, Unity'),
(70, 24, 'IoT Developer Certification', 'IoT Academy', 'Certification in IoT dev.', 'IoT, Sensors'),
(71, 24, 'Cisco IoT Fundamentals', 'Cisco', 'Cisco IoT certificate.', 'IoT, Networking'),
(72, 24, 'Microsoft Azure IoT Developer', 'Microsoft', 'Certification in Azure IoT.', 'IoT, Cloud'),
(73, 25, 'PMP (Project Management Professional)', 'PMI', 'Certification in project management.', 'Project Planning, Agile'),
(74, 25, 'Certified ScrumMaster (CSM)', 'Scrum Alliance', 'Certification in Agile Scrum.', 'Agile, Scrum'),
(75, 25, 'PRINCE2 Foundation', 'AXELOS', 'Project management methodology.', 'PRINCE2, Agile');

-- --------------------------------------------------------

--
-- Table structure for table `pending_users`
--

CREATE TABLE `pending_users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_phrase` varchar(255) NOT NULL,
  `verification_code` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_phrase` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verification_code` int(6) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `email`, `password`, `reset_phrase`, `created_at`, `verification_code`, `is_verified`) VALUES
(32, 'Christian John M. Amor', 'amorcj9@gmail.com', '$2y$10$YRTHNf/9hvyBj4ELTFRJPuJjm1pcPK7DpIaCXNeZaer638VGwE4tO', 'amore', '2025-10-09 07:19:41', 743747, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `careers`
--
ALTER TABLE `careers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `career_certificates`
--
ALTER TABLE `career_certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `career_roadmaps`
--
ALTER TABLE `career_roadmaps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `career_id` (`career_id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `career_id` (`career_id`);

--
-- Indexes for table `pending_users`
--
ALTER TABLE `pending_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `careers`
--
ALTER TABLE `careers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `career_certificates`
--
ALTER TABLE `career_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `career_roadmaps`
--
ALTER TABLE `career_roadmaps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `pending_users`
--
ALTER TABLE `pending_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `career_roadmaps`
--
ALTER TABLE `career_roadmaps`
  ADD CONSTRAINT `career_roadmaps_ibfk_1` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
