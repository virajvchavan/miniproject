-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2016 at 09:45 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `miniproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `balances_of_users`
--

CREATE TABLE IF NOT EXISTS `balances_of_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `balance` varchar(10) NOT NULL DEFAULT '500',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `balances_of_users`
--

INSERT INTO `balances_of_users` (`id`, `user_id`, `balance`, `time`) VALUES
(1, 1, '500', '2016-08-16 07:02:10'),
(2, 1, '380', '2016-08-17 06:01:25'),
(3, 1, '270', '2016-08-17 06:01:25'),
(4, 2, '500', '2016-08-17 06:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `buying_orders`
--

CREATE TABLE IF NOT EXISTS `buying_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `no_of_shares` varchar(10) NOT NULL,
  `price` varchar(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `buying_orders`
--

INSERT INTO `buying_orders` (`id`, `company_id`, `user_id`, `no_of_shares`, `price`, `time`) VALUES
(1, 1, 1, '10', '20', '2016-08-16 09:33:49'),
(2, 2, 1, '40', '10', '2016-08-16 09:34:46'),
(3, 1, 1, '200', '20', '2016-08-16 09:41:24'),
(4, 1, 1, '200', '20', '2016-08-16 09:42:53'),
(5, 1, 1, '2', '20', '2016-08-16 09:43:31'),
(6, 1, 1, '2', '20', '2016-08-16 09:43:46'),
(7, 1, 1, '400', '20', '2016-08-16 09:44:18'),
(8, 1, 1, '400', '20', '2016-08-16 09:44:25'),
(9, 1, 1, '54', '20', '2016-08-16 09:44:49'),
(10, 1, 1, '2', '20', '2016-08-16 09:45:02'),
(11, 1, 1, '2', '400', '2016-08-16 09:45:13'),
(12, 1, 1, '465', '20', '2016-08-16 09:45:57'),
(13, 1, 1, '455', '20', '2016-08-16 09:46:57'),
(14, 2, 1, '20', '12', '2016-08-17 18:57:39');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abbrivation` varchar(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(500) NOT NULL,
  `total_shares` varchar(10) NOT NULL,
  `stock_price` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `abbrivation`, `name`, `description`, `total_shares`, `stock_price`) VALUES
(1, 'APL', 'Apple', ' WELCOME TO the Seventh Edition of Introduction to Programming Using Java, a free, on-line textbook on introductory programming, which uses Java as the language of instruction. This book is directed mainly towards beginning programmers, although it might also be useful for experienced programmers who want to learn something about Java. It is certainly not meant to provide complete coverage of the Java language.\r\n\r\nThe seventh edition requires Java 7, with just a couple brief mentions of Java 8. ', '500', '25'),
(2, 'SMG', 'Samsung', ' The basic building blocks of programs -- variables, expressions, assignment statements, and subroutine call statements -- were covered in the previous chapter. Starting with this chapter, we look at how these building blocks can be put together to build complex programs with more interesting behavior.\r\n\r\nSince we are still working on the level of &#34;programming in the small&#34; in this chapter, we are interested in the kind of complexity that can occur within a single subroutine. On this lev', '500', '12'),
(3, 'MSF', 'Microsoft', 'A switch statement allows you to test the value of an expression and, depending on that value, to jump directly to some location within the switch statement. Only expressions of certain types can be used. The value of the expression can be one of the primitive integer types int, short, or byte. It can be the primitive char type. It can be String. Or it can be an enum type (see Subsection 2.3.4 for an introduction to enums). In particular, note that the expression cannot be a double or float valu', '500', '14'),
(4, 'SNY', 'Sony', 'The break statements in the switch are technically optional. The effect of a break is to make the computer jump past the end of the switch statement, skipping over all the remaining cases. If you leave out the break statement, the computer will just forge ahead after completing one case and will execute the statements associated with the next case label. This is rarely what you want, but it is legal. (I will note here -- although you won&#39;t understand it until you get to the next chapter -- t', '500', '7'),
(5, 'NKA', 'Nokia', 'The problem is that in the code on the right, the computer can&#39;t tell that the variable y has definitely been assigned a value. When an if statement has no else part, the statement inside the if might or might not be executed, depending on the value of the condition. The compiler can&#39;t tell whether it will be executed or not, since the condition will only be evaluated when the program is running. For the code on the right above, as far as the compiler is concerned, it is possible that ne', '500', '4'),
(6, 'TGW', 'Thoughtworks', 'SOme damn description', '400', '11');

-- --------------------------------------------------------

--
-- Table structure for table `selling_orders`
--

CREATE TABLE IF NOT EXISTS `selling_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `price` varchar(10) NOT NULL,
  `no_of_shares` varchar(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `selling_orders`
--

INSERT INTO `selling_orders` (`id`, `company_id`, `user_id`, `price`, `no_of_shares`, `time`) VALUES
(1, 1, -1, '10', '500', '2016-08-16 06:49:04'),
(2, 2, -1, '8', '500', '2016-08-16 06:49:46'),
(3, 3, -1, '14', '500', '2016-08-16 06:50:37'),
(4, 4, -1, '9', '500', '2016-08-16 06:51:13'),
(5, 5, -1, '4', '500', '2016-08-16 06:52:07'),
(6, 6, -1, '11', '400', '2016-08-16 10:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `shares_distribution`
--

CREATE TABLE IF NOT EXISTS `shares_distribution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `no_of_shares` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `shares_distribution`
--

INSERT INTO `shares_distribution` (`id`, `user_id`, `company_id`, `no_of_shares`) VALUES
(1, -1, 6, '300'),
(2, 1, 6, '100'),
(3, -1, 1, '500'),
(4, -1, 2, '400'),
(5, -1, 3, '500'),
(6, -1, 4, '500'),
(7, -1, 5, '500'),
(8, 1, 2, '100');

-- --------------------------------------------------------

--
-- Table structure for table `stock_prices`
--

CREATE TABLE IF NOT EXISTS `stock_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `stock_price` varchar(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `stock_prices`
--

INSERT INTO `stock_prices` (`id`, `company_id`, `stock_price`, `time`) VALUES
(1, 1, '10', '2016-08-16 06:49:04'),
(2, 2, '8', '2016-08-16 06:49:46'),
(3, 3, '14', '2016-08-16 06:50:37'),
(4, 4, '9', '2016-08-16 06:51:13'),
(5, 5, '4', '2016-08-16 06:52:07'),
(6, 1, '20', '2016-08-16 06:53:04'),
(7, 2, '12', '2016-08-16 07:00:06'),
(8, 2, '12', '2016-08-16 07:01:19'),
(9, 4, '7', '2016-08-16 07:01:28'),
(10, 6, '11', '2016-08-16 10:22:00'),
(11, 1, '40', '2016-08-16 13:01:48'),
(12, 1, '25', '2016-08-17 19:26:33');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `no_of_shares` varchar(10) NOT NULL,
  `price` varchar(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `company_id`, `seller_id`, `buyer_id`, `no_of_shares`, `price`, `time`) VALUES
(1, 6, -1, 1, '100', '11', '2016-08-17 06:04:11'),
(2, 2, -1, 1, '100', '12', '2016-08-17 06:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `balance` varchar(20) NOT NULL DEFAULT '500',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `balance`) VALUES
(1, 'Viraj Chavan', 'v@v.com', '5f4dcc3b5aa765d61d8327deb882cf99', '270'),
(2, 'Harry Potter', 'h@h.com', '5f4dcc3b5aa765d61d8327deb882cf99', '500');

--
-- Triggers `users`
--
DROP TRIGGER IF EXISTS `register`;
DELIMITER //
CREATE TRIGGER `register` AFTER INSERT ON `users`
 FOR EACH ROW INSERT INTO balances_of_users(user_id) SELECT MAX(id) FROM users
//
DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
