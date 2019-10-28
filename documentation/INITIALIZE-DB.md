Installation Instructions:
---------------------------

Run the following SQL to create the database.
This creates the initial structure.
Please note that this also creates an admin user, which you should re-intialize with a new password using the interface.

You will also have to rename this file: 
https://github.com/spokenweb/swallow/blob/master/Model/db-dist.config.php
to db.config.php
and configure the credentials for DB access in that file.



```
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `swallow`
--

-- --------------------------------------------------------

--
-- Table structure for table `cataloguer`
--

CREATE TABLE `cataloguer` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `pwd` varchar(256) NOT NULL,
  `institution` varchar(64) NOT NULL,
  `role` smallint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cataloguer` (`id`, `name`, `lastname`, `email`, `pwd`, `institution`, `role`) VALUES
(1, 'admin', 'admin', '--INSERT--YOUR--ADMIN--EMAIL', '', '', 1);
-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `id` int(11) NOT NULL,
  `partner_institution` varchar(64) DEFAULT NULL,
  `contributing_unit` varchar(128) DEFAULT NULL,
  `source_collection` varchar(128) DEFAULT NULL,
  `source_collection_description` text,
  `source_collection_ID` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `cataloguer_id` int(11) NOT NULL,
  `collection_id` int(11) DEFAULT NULL,
  `schema_version` varchar(8) DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `locked` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cataloguer`
--
ALTER TABLE `cataloguer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cataloguer`
--
ALTER TABLE `cataloguer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

```
