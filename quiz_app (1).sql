-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 03:21 PM
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
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` char(1) NOT NULL CHECK (`correct_answer` in ('A','B','C','D')),
  `explanation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `unit_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `explanation`) VALUES
(1, 1, 'What is 2 + 2?', '3', '4', '5', '6', 'B', NULL),
(2, 1, 'What is the value of x in x + 5 = 12?', '5', '6', '7', '8', 'C', NULL),
(3, 3, '1. An example of an inertial reference frame is: A. any reference frame that is at rest B. a reference frame attached to the center of the universe C. a reference frame attached to Earth D. a frame attached to a particle on which there are no forces', 'any reference frame that is at rest', 'a reference frame attached to the center of the universe', 'a reference frame attached to Earth', 'a frame attached to a particle on which there are no forces', 'A', NULL),
(4, 3, '2. Which of the following reasons is correct about the difficulty to walk in sand than on hard ground? A. The action of the feet on the sandy ground is greater... D. The feet can’t put in sufficient action...', 'The action of the feet on the sandy ground is greater...', 'Not specified in question', 'Not specified in question', 'The feet can’t put in sufficient action...', 'A', NULL),
(5, 3, '3. If a force F results an acceleration A when acting on a mass M, then doubling the mass & increasing force 4x results in acceleration: A. ? B. 2A C. A D. 4A', '?', '2A', 'A', '4A', 'A', NULL),
(6, 3, '4. Neglecting friction, find the tension in the thread connecting (diagram not shown). Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(7, 3, '5. Calculate the acceleration and tension in the cord when the system is released (diagram not shown). Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(8, 3, '6. Starting from rest, box reaches 2 m/s in 4 s. Coefficient of friction? A.0.399 B.0.07 C.0.2 D.0.1', '0.399', '0.07', '0.2', '0.1', 'A', NULL),
(9, 3, '7. Heavy ball suspended; jerk breaks lower string. Why? A force too small... C Ball has inertia...', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(10, 3, '8. Two forces 6N north, 8N west on 5kg crate. Magnitude of acceleration? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(11, 3, '9. 1000kg elevator rising & speed increasing at 3 m/s². Tension? A.6800N B.13000N C.600N D.800N', '6800N', '13000N', '600N', '800N', 'A', NULL),
(12, 3, '10. Two blocks connected by pulley (diagram missing). Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(13, 3, '11. 400 N block dragged on horizontal surface with kinetic friction μk. Moves constant velocity; find applied force. A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(14, 3, '12. Force F applied to masses m1 and m2 producing accelerations a1 and a2. Ratio m1/m2? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(15, 3, '13. Three objects on rough table with μk=0.350, masses 4,1,2 kg. Find accelerations & tensions. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(16, 3, '14. Two blocks 3.5kg & 8kg on frictionless inclines with string. Find acceleration & tension. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(17, 3, '15. Two 20N forces act as a couple 10 cm apart. Torque? A.2Nm B.8Nm C.16Nm D.4Nm', '2Nm', '8Nm', '16Nm', '4Nm', 'A', NULL),
(18, 3, '16. Two like parallel forces 30mm apart, resultant 60N, line of action 10mm from one force. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(19, 3, '17. Two forces on a particle in equilibrium must be? A equal & opposite B equal & same direction ...', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(20, 3, '18. Beam negligible mass, hinge + rope supports 400N at center. Find rope tension. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(21, 3, '19. Forces 2N,12N,5N on rod; find net torque. A.22Nm B.14Nm C.7Nm D.2Nm', '22Nm', '14Nm', '7Nm', '2Nm', 'A', NULL),
(22, 3, '20. Which is NOT correct about particle in equilibrium? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(23, 3, '21. Mass on rough incline μ, angle θ. If equilibrium, condition? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(24, 3, '22. Rocket engines lift rocket because gases: A Push air B Push earth C Heat air D React & push rocket', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(25, 3, '23. 0.2 kg object at rest, force (vector) applied for 6s; find velocity. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(26, 3, '24. 2kg block on floor, μs=0.4, applied 2.8N. Static friction is? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(27, 3, '25. Horizontal force on block on incline; find normal N. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(28, 3, '26. Person in elevator feels weightless when? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(29, 3, '27. Masses and pulley system, find acceleration & tension (diagram missing). Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(30, 3, '28. Mars gravity & weight of 65kg person. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(31, 3, '29. 44-kg chandelier suspended by three wires 2m long, tension? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(32, 3, '30. Ladder 6m, 400N, angle θ, friction floor, wall smooth; find reactions & μs. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(33, 3, '31. Scaffold 6m, 70kg + 50kg person at 1.5m; find rope tensions. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(34, 3, '32. 400N sign on uniform 4m strut 600N, supported by hinge + cable; find tension & hinge force. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(35, 3, '33. Force vector on particle moving from (x,y) to (x,y,z); work done? A 6J B13J C15J D9J', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(36, 3, '34. 50kg block on smooth 37° incline; 400N push up; acceleration? A2 B3 C5 D1', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(37, 3, '35. Which is not true? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(38, 3, '36. 0.5kg block collides with k=50 spring; max compression? A0.5 B0.15 C0.12 D1.5', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(39, 3, '37. First condition of equilibrium? A net force=0 B torques sum=0 ...', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(40, 3, '38. Ladder 10m, 40kg, 60°; wall smooth. Friction needed? A400N B48N C96N D40N', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(41, 3, '39. KE ratio 4:1, equal momentum; ratio of masses? A1:2 B1:1 C4:1 D1:4', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(42, 3, '40. Example of Newton’s 1st law? A soccer ball kicked B book on table ...', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(43, 3, '41. 1kg object sliding 10m down incline angle θ; work by gravity? A24J B36J C60J D50J', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(44, 3, '42. Conservative vs non-conservative? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(45, 3, '43. True about work-energy-force? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(46, 3, '44. Box pulled 25N for 15m at 30°; work? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(47, 3, '45. Normal force does no work because angle is? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(48, 3, '46. Which is not true? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(49, 3, '47. Which statement NOT correct about equilibrium? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(50, 3, '48. Force varies with x; work from 0 to x? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(51, 3, '49. Old car: v in 10s; new car: 2v in 10s. Ratio of power? A0.25 B0.5 C1 D2', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(52, 3, '50. Work done when particle moves in circle is? A positive B negative C zero D unknown', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(53, 3, '51. Object 12kg on balance & spring scale; on Moon new readings? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(54, 3, '52. Baseball flight: work by gravity & air resistance? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(55, 4, '1. An example of an inertial reference frame is: A. any reference frame that is at rest B. a reference frame attached to the center of the universe C. a reference frame attached to Earth D. a frame attached to a particle on which there are no forces', 'any reference frame that is at rest', 'a reference frame attached to the center of the universe', 'a reference frame attached to Earth', 'a frame attached to a particle on which there are no forces', 'A', NULL),
(56, 4, '2. Which of the following reasons is correct about the difficulty to walk in sand than on hard ground? A. The action of the feet on the sandy ground is greater... D. The feet can’t put in sufficient action...', 'The action of the feet on the sandy ground is greater...', 'Not specified in question', 'Not specified in question', 'The feet can’t put in sufficient action...', 'A', NULL),
(57, 4, '3. If a force F results an acceleration A when acting on a mass M, then doubling the mass & increasing force 4x results in acceleration: A. ? B. 2A C. A D. 4A', '?', '2A', 'A', '4A', 'A', NULL),
(58, 4, '4. Neglecting friction, find the tension in the thread connecting (diagram not shown). Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(59, 4, '5. Calculate the acceleration and tension in the cord when the system is released (diagram not shown). Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(60, 4, '6. Starting from rest, box reaches 2 m/s in 4 s. Coefficient of friction? A.0.399 B.0.07 C.0.2 D.0.1', '0.399', '0.07', '0.2', '0.1', 'A', NULL),
(61, 4, '7. Heavy ball suspended; jerk breaks lower string. Why? A force too small... C Ball has inertia...', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(62, 4, '8. Two forces 6N north, 8N west on 5kg crate. Magnitude of acceleration? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(63, 4, '9. 1000kg elevator rising & speed increasing at 3 m/s². Tension? A.6800N B.13000N C.600N D.800N', '6800N', '13000N', '600N', '800N', 'A', NULL),
(64, 4, '10. Two blocks connected by pulley (diagram missing). Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(65, 4, '11. 400 N block dragged on horizontal surface with kinetic friction μk. Moves constant velocity; find applied force. A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(66, 4, '12. Force F applied to masses m1 and m2 producing accelerations a1 and a2. Ratio m1/m2? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(67, 4, '13. Three objects on rough table with μk=0.350, masses 4,1,2 kg. Find accelerations & tensions. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(68, 4, '14. Two blocks 3.5kg & 8kg on frictionless inclines with string. Find acceleration & tension. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(69, 4, '15. Two 20N forces act as a couple 10 cm apart. Torque? A.2Nm B.8Nm C.16Nm D.4Nm', '2Nm', '8Nm', '16Nm', '4Nm', 'A', NULL),
(70, 4, '16. Two like parallel forces 30mm apart, resultant 60N, line of action 10mm from one force. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(71, 4, '17. Two forces on a particle in equilibrium must be? A equal & opposite B equal & same direction ...', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(72, 4, '18. Beam negligible mass, hinge + rope supports 400N at center. Find rope tension. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(73, 4, '19. Forces 2N,12N,5N on rod; find net torque. A.22Nm B.14Nm C.7Nm D.2Nm', '22Nm', '14Nm', '7Nm', '2Nm', 'A', NULL),
(74, 4, '20. Which is NOT correct about particle in equilibrium? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(75, 4, '21. Mass on rough incline μ, angle θ. If equilibrium, condition? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(76, 4, '22. Rocket engines lift rocket because gases: A Push air B Push earth C Heat air D React & push rocket', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(77, 4, '23. 0.2 kg object at rest, force (vector) applied for 6s; find velocity. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(78, 4, '24. 2kg block on floor, μs=0.4, applied 2.8N. Static friction is? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(79, 4, '25. Horizontal force on block on incline; find normal N. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(80, 4, '26. Person in elevator feels weightless when? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(81, 4, '27. Masses and pulley system, find acceleration & tension (diagram missing). Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(82, 4, '28. Mars gravity & weight of 65kg person. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(83, 4, '29. 44-kg chandelier suspended by three wires 2m long, tension? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(84, 4, '30. Ladder 6m, 400N, angle θ, friction floor, wall smooth; find reactions & μs. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(85, 4, '31. Scaffold 6m, 70kg + 50kg person at 1.5m; find rope tensions. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(86, 4, '32. 400N sign on uniform 4m strut 600N, supported by hinge + cable; find tension & hinge force. Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(87, 4, '33. Force vector on particle moving from (x,y) to (x,y,z); work done? A 6J B13J C15J D9J', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(88, 4, '34. 50kg block on smooth 37° incline; 400N push up; acceleration? A2 B3 C5 D1', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(89, 4, '35. Which is not true? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(90, 4, '36. 0.5kg block collides with k=50 spring; max compression? A0.5 B0.15 C0.12 D1.5', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(91, 4, '37. First condition of equilibrium? A net force=0 B torques sum=0 ...', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(92, 4, '38. Ladder 10m, 40kg, 60°; wall smooth. Friction needed? A400N B48N C96N D40N', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(93, 4, '39. KE ratio 4:1, equal momentum; ratio of masses? A1:2 B1:1 C4:1 D1:4', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(94, 4, '40. Example of Newton’s 1st law? A soccer ball kicked B book on table ...', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(95, 4, '41. 1kg object sliding 10m down incline angle θ; work by gravity? A24J B36J C60J D50J', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(96, 4, '42. Conservative vs non-conservative? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(97, 4, '43. True about work-energy-force? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(98, 4, '44. Box pulled 25N for 15m at 30°; work? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(99, 4, '45. Normal force does no work because angle is? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(100, 4, '46. Which is not true? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(101, 4, '47. Which statement NOT correct about equilibrium? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(102, 4, '48. Force varies with x; work from 0 to x? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(103, 4, '49. Old car: v in 10s; new car: 2v in 10s. Ratio of power? A0.25 B0.5 C1 D2', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(104, 4, '50. Work done when particle moves in circle is? A positive B negative C zero D unknown', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(105, 4, '51. Object 12kg on balance & spring scale; on Moon new readings? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(106, 4, '52. Baseball flight: work by gravity & air resistance? Options A–D.', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'Not specified in question', 'A', NULL),
(107, 5, '1. An example of an inertial reference frame is: A. any reference frame that is at rest B. a reference frame attached to the center of the universe C. a reference frame attached to Earth D. a frame attached to a particle on which there are no forces', 'any reference frame that is at rest', 'a reference frame attached to the center of the universe', 'a reference frame attached to Earth', 'a frame attached to a particle on which there are no forces', 'A', NULL),
(108, 5, '2. Which of the following reasons is correct about the difficulty to walk in sand than on hard ground? A. The action of the feet on the sandy ground is greater... D. The feet can’t put in sufficient action...', 'The action of the feet on the sandy ground is greater...', 'Not specified', 'Not specified', 'The feet can’t put in sufficient action...', 'A', NULL),
(109, 5, '3. If a force F results an acceleration A when acting on a mass M, then doubling the mass & increasing force 4x results in acceleration: A. ? B. 2A C. A D. 4A', '?', '2A', 'A', '4A', 'A', NULL),
(110, 5, '4. Neglecting friction, find the tension in the thread connecting (diagram not shown). Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(111, 5, '5. Calculate the acceleration and tension in the cord when the system is released (diagram not shown). Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(112, 5, '6. Starting from rest, box reaches 2 m/s in 4 s. Coefficient of friction? A.0.399 B.0.07 C.0.2 D.0.1', '0.399', '0.07', '0.2', '0.1', 'A', NULL),
(113, 5, '7. Heavy ball suspended; jerk breaks lower string. Why? A force too small... C Ball has inertia...', '..', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(114, 5, '8. Two forces 6N north, 8N west on 5kg crate. Magnitude of acceleration? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(115, 5, '9. 1000kg elevator rising & speed increasing at 3 m/s². Tension? A.6800N B.13000N C.600N D.800N', '6800N', '13000N', '600N', '800N', 'A', NULL),
(116, 5, '10. Two blocks connected by pulley (diagram missing). Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(117, 5, '11. 400 N block dragged on horizontal surface with kinetic friction μk. Moves constant velocity; find applied force. A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(118, 5, '12. Force F applied to masses m1 and m2 producing accelerations a1 and a2. Ratio m1/m2? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(119, 5, '13. Three objects on rough table with μk=0.350, masses 4,1,2 kg. Find accelerations & tensions. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(120, 5, '14. Two blocks 3.5kg & 8kg on frictionless inclines with string. Find acceleration & tension. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(121, 5, '15. Two 20N forces act as a couple 10 cm apart. Torque? A.2Nm B.8Nm C.16Nm D.4Nm', '2Nm', '8Nm', '16Nm', '4Nm', 'A', NULL),
(122, 5, '16. Two like parallel forces 30mm apart, resultant 60N, line of action 10mm from one force. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(123, 5, '17. Two forces on a particle in equilibrium must be? A equal & opposite B equal & same direction ...', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(124, 5, '18. Beam negligible mass, hinge + rope supports 400N at center. Find rope tension. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(125, 5, '19. Forces 2N,12N,5N on rod; find net torque. A.22Nm B.14Nm C.7Nm D.2Nm', '22Nm', '14Nm', '7Nm', '2Nm', 'A', NULL),
(126, 5, '20. Which is NOT correct about particle in equilibrium? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(127, 5, '21. Mass on rough incline μ, angle θ. If equilibrium, condition? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(128, 5, '22. Rocket engines lift rocket because gases: A Push air B Push earth C Heat air D React & push rocket', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(129, 5, '23. 0.2 kg object at rest, force (vector) applied for 6s; find velocity. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(130, 5, '24. 2kg block on floor, μs=0.4, applied 2.8N. Static friction is? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(131, 5, '25. Horizontal force on block on incline; find normal N. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(132, 5, '26. Person in elevator feels weightless when? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(133, 5, '27. Masses and pulley system, find acceleration & tension (diagram missing). Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(134, 5, '28. Mars gravity & weight of 65kg person. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(135, 5, '29. 44-kg chandelier suspended by three wires 2m long, tension? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(136, 5, '30. Ladder 6m, 400N, angle θ, friction floor, wall smooth; find reactions & μs. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(137, 5, '31. Scaffold 6m, 70kg + 50kg person at 1.5m; find rope tensions. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(138, 5, '32. 400N sign on uniform 4m strut 600N, supported by hinge + cable; find tension & hinge force. Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(139, 5, '33. Force vector on particle moving from (x,y) to (x,y,z); work done? A 6J B13J C15J D9J', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(140, 5, '34. 50kg block on smooth 37° incline; 400N push up; acceleration? A2 B3 C5 D1', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(141, 5, '35. Which is not true? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(142, 5, '36. 0.5kg block collides with k=50 spring; max compression? A0.5 B0.15 C0.12 D1.5', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(143, 5, '37. First condition of equilibrium? A net force=0 B torques sum=0 ...', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(144, 5, '38. Ladder 10m, 40kg, 60°; wall smooth. Friction needed? A400N B48N C96N D40N', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(145, 5, '39. KE ratio 4:1, equal momentum; ratio of masses? A1:2 B1:1 C4:1 D1:4', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(146, 5, '40. Example of Newton’s 1st law? A soccer ball kicked B book on table ...', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(147, 5, '41. 1kg object sliding 10m down incline angle θ; work by gravity? A24J B36J C60J D50J', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(148, 5, '42. Conservative vs non-conservative? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(149, 5, '43. True about work-energy-force? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(150, 5, '44. Box pulled 25N for 15m at 30°; work? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(151, 5, '45. Normal force does no work because angle is? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(152, 5, '46. Which is not true? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(153, 5, '47. Which statement NOT correct about equilibrium? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(154, 5, '48. Force varies with x; work from 0 to x? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(155, 5, '49. Old car: v in 10s; new car: 2v in 10s. Ratio of power? A0.25 B0.5 C1 D2', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(156, 5, '50. Work done when particle moves in circle is? A positive B negative C zero D unknown', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(157, 5, '51. Object 12kg on balance & spring scale; on Moon new readings? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(158, 5, '52. Baseball flight: work by gravity & air resistance? Options A–D.', 'Not specified', 'Not specified', 'Not specified', 'Not specified', 'A', NULL),
(159, 5, 'donkey', 'anymal', 'animal', 'animsal', 'adimsal', 'B', NULL),
(160, 5, 'what is _', 'ear', 'animal', 'eye', 'plant', 'A', NULL),
(161, 5, 'what is _', 'ear', 'animal', 'eye', 'plant', 'A', NULL),
(162, 5, 'what is _', 'ear', 'animal', 'eye', 'plant', 'A', NULL),
(163, 5, 'zznsba', '12', '32', '21', '43', 'A', 'abeba'),
(164, 15, 'ads', 'sd', 'as', 'as', 'as', 'C', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_scores`
--

CREATE TABLE `student_scores` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `correct` int(11) NOT NULL,
  `missed` int(11) NOT NULL,
  `total` int(11) GENERATED ALWAYS AS (`correct` + `missed`) VIRTUAL,
  `percentage` decimal(5,2) GENERATED ALWAYS AS (round(`correct` * 100.0 / (`correct` + `missed`),2)) STORED,
  `taken_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_scores`
--

INSERT INTO `student_scores` (`id`, `student_id`, `unit_id`, `correct`, `missed`, `taken_at`) VALUES
(1, 5, 2, 0, 0, '2025-11-23 05:01:24'),
(2, 5, 2, 0, 0, '2025-11-23 05:05:15'),
(3, 5, 2, 0, 0, '2025-11-23 05:09:50'),
(4, 5, 3, 17, 35, '2025-11-23 05:23:29'),
(5, 5, 1, 2, 0, '2025-11-23 05:25:09'),
(6, 6, 1, 1, 1, '2025-12-14 00:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `grade` int(11) NOT NULL CHECK (`grade` in (9,10,11,12)),
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `grade`, `name`) VALUES
(6, 9, 'Biology'),
(13, 9, 'Chemistry'),
(18, 9, 'doooooofff'),
(2, 9, 'English'),
(15, 9, 'Geography'),
(12, 9, 'histlogy'),
(5, 9, 'History'),
(1, 9, 'Mathematics'),
(11, 10, 'bio'),
(4, 10, 'English'),
(8, 11, 'Biology'),
(10, 11, 'Physics'),
(7, 12, 'Biology');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `subject_id`, `name`) VALUES
(1, 1, 'Algebra Basics'),
(2, 1, 'unit 3'),
(8, 2, 'Ancient ethiopians'),
(6, 2, 'unit 3'),
(3, 4, 'unit 1'),
(13, 4, 'unit 3'),
(4, 5, 'Ancient ethiopians'),
(5, 6, 'UNIT !'),
(9, 7, 'unit 3'),
(10, 8, 'unit 3'),
(12, 10, 'Ancient ethiopians'),
(11, 10, 'unit 1'),
(14, 11, 'unit 3'),
(15, 18, 'unit 3');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'ad', '$2y$10$5z8fK8x9q2vL5rT9pQ3x8u8XbF7kL2mN9vR4tY6uI0oP8eW3sA1d2', 'admin', '2025-11-23 04:40:39'),
(2, 'student1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '2025-11-23 04:40:39'),
(3, 'yyy', '$2y$10$p4bc8UveO5yETTykOq.e9.Lfk1jlMCLXEZ9LTSJoKx0BeOHL0fQ1G', 'student', '2025-11-23 04:41:08'),
(4, 'yab', '$2y$10$NHbkariCa9eNhny8gBpmmuJgbEXLEI152poMua/tefSrjBhPaMoX2', 'admin', '2025-11-23 04:41:38'),
(5, 'yb', '$2y$10$HuvQ5rKMzWBy5N8rXf.V5uTHzKSNk9UP9UNFfDcz3HULCygBl2CTe', 'student', '2025-11-23 05:01:11'),
(6, 'ppt', '$2y$10$5.tQ8jjFXlEa/3SNVNd5AeRhsNnpPjb142EdnIStugAGfv2RCMczy', 'student', '2025-12-13 19:21:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `student_scores`
--
ALTER TABLE `student_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attempt` (`student_id`,`unit_id`,`taken_at`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_subject_per_grade` (`grade`,`name`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_unit_per_subject` (`subject_id`,`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `student_scores`
--
ALTER TABLE `student_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_scores`
--
ALTER TABLE `student_scores`
  ADD CONSTRAINT `student_scores_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_scores_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
