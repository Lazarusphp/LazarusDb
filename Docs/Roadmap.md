# LazarusDb Roadmap and future plans
Follow this read me for any future Plans and changes to the site System.

## CodeBase Restructure
Plans to do a full codebase Folder restructure are in the works, the plan is to make the codebase easier to manage and navigate around

as part of the new structuring the class categories ie Database Querybuilder and SchemeBuilder Will all have version numbers starting 1.0.0 except Database this will start at 1.1.2 due to its release history and origin.

* LazarusDb
    * src
        * Database
            * CoreFiles
            * Traits
            * Interfaces
        * SchemaBuilder
            * CoreFiles
            * Traits
            * Interfaces
        * QueryBuilder
            * CoreFiles
            * Traits
            * Interfaces
        * SharedAssets
            * Intefaces
            * Traits
    * Docs
        * SchemaBuilder
        * Database
        * QueryBuilder
        * Roadmap.md

Shared Assets Will contain methods that will be used between all the Classes these will be created as traits and interfaces.


- [x] Codebase Restructure completed

Start Date : 18/05/2025

Completion Date : 18/05/2025

## Codebase Restructure
The next plan is to make a universal Database Management Package Making all classes work seamlessly with each other, this task will be done in three parts

1.  -[x] Restructure the Database Class, allowing for support for both persistant and non persistant classes.
2. - [] Restructure the QueryBuilder to support new Changes from the Database Class
3. Restructure the SchemaBuilder class to support new changes with the database as well as a new codebase structure for the schemaBuilder itself.

- [ ] Restructure completed on 

Started on : 18/05/2025
Completed on :