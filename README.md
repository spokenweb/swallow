# Swallow Metadata Management System

This is the source code and documentation repository for the Swallow Metadata Management System.

## SpokenWeb Metadata Scheme

SpokenWeb Metadata Scheme and Cataloguing Process is described here: https://spokenweb-metadata-scheme.readthedocs.io/en/latest/

## Installation of Swallow

Installation Requirements:
--------------------------

* PHP 
* MySQL MySQL 5.7.8 (or higher)

Swallow was developed on PHP 7.2.10 on Ubuntu 18.04

* [Initialize the MySQL database structure](documentation/INITIALIZE-DB.md)

## Software Architecture
![Swallow architecture](documentation/swallow_current_state.png)

The most distinctive characteristic of the Swallow architecture is the complete decoupling of the metadata schema from the database and the system. This is possible by storing the metadata information in no-SQL format and implementing an engine to generate the user interface from a configuration file. As well, in configuration files, there are maps that allow Swallow to batch ingest and export data from and to different systems. These configuration files are defined as JSON objects.

In the figure above we see the overall system diagram for Swallow. White boxes indicate the core modules of Swallow: 
* Cataloguers Management
* Collections Management
* Items management.
* Import and export.

## User Interface

### User Profile

![user profile](/documentation/UI-profile.png)

### Dashboard

![dashboard](/documentation/UI-dashboard.png)

### Cataloguers

### Collections

### Items

#### Institution & Collection

#### Item Description

#### Rights

#### Creators/Contributors

#### Material Description

#### Digital File Description

#### Dates

#### Location

#### Content

#### Notes

#### Related Works

### Import

### Export


# License

[BSD 3-Clause License](LICENSE). 
