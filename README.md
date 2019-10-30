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

The most distinctive characteristic of the Swallow architecture is the complete decoupling of the metadata schema from the database and the system. This is possible by storing the metadata information in no-SQL format and implementing an engine to generate the user interface from a [configuration files](Workflow/3).

### User/Cataloguer Profile

Editing a user profile, these are simple fields for username and password:

![user profile](/documentation/UI-profile.png)

### Dashboard

![dashboard](/documentation/UI-dashboard.png)

### Cataloguers

Browsing cataloguers (for admins):

![cataloguer browse screen for admins](/documentation/UI-cataloguers.png)

### Collections

Browsing collections:

![collection browse screen](/documentation/UI-collections.png)

Editing a collection-level metadata:

![collection editing metadata](/documentation/UI-collections-edit.png)

### Items

Swallow can be configured to support multiple schema.  The following are examples of cataloguing screens generated based on the SpokenWeb Schema that it currently includes by default ([configuration files](Workflow/3)). 

Browsing Items encoded in SpokenWeb Schema:

![item browsing](/documentation/UI-items.png)

Limiting by institution, cataloguer, collection.  Sorting. Simple search.

#### Institution & Collection

![item institution/collection](/documentation/UI-items-institution-collection.png)

#### Item Description

![item description](/documentation/UI-items-description.png)

#### Rights

The Swallow schema specification functionality currently allows for the inclusion of [URIs alongside metadata values](Workflow/3/Vocabulary/Rights.json) that are a part of the SpokenWeb schema, such as links to CreativeCommons and Rights Statements.  

![rights](/documentation/UI-item-rights.png)

#### Creators/Contributors

The spokenweb schema includes URIs for authority records (VIAF in example below), and multiple roles:

![creators/contributors](/documentation/UI-creators.png)

Idigeneous ontology nation names can also be added to describe creators/contributors:

![indigenous nations ontology add-on widget](/documentation/UI-indigenous_nations.png)

#### Material Description

The spokenweb schema includes many specialized fields for the material description, for exmaple:

![material description](/documentation/UI-material_description.png)

#### Location

![location](/documentation/UI-location.png)

#### Content

The SpokenWeb schema supports XML encoded metadata.  This is required for storing Avalon XML formatted structural metadata (https://wiki.dlib.indiana.edu/display/VarVideo/Adding+Structure+to+Files+Using+the+Graphical+XML+Editor)

![content field](/documentation/UI-content.png)

#### Related Works

The SpokenWeb schema includes a mulitple field for related citations and URIs

![related works](/documentation/UI-related_works.png)

#### Other fields

Other fields in the SpokenWeb schema include Digital File Description, Dates and Notes.

### Import

Importing UI allows user to select an import mapping.  A CSV mapping and the two mapping for the SpokenWeb schema versions 2 and 3 are included.

![import](/documentation/UI-import.png)

### Export


# License

[BSD 3-Clause License](LICENSE). 
