# SnorkelWeb DBManager

SnorkelWeb DBmanager is a Work in progress (wip) script that allows users to Create, Read, Update and Delete Content from the the selected database (crud).

this script can be used as a standalone script as well as part of the SnorkelWeb Framework which is again (wip)

# Installation

This documentation will be updated soon!

# Usage

This script Has Some pre-Requirements in order to start using the DBManager

<ul>
<li>A .ini file which will be used to hold your mysql Database Details.

```
type=""
hostname=""
username=""
password=""
dbname=""
```
</li>


<li>A Constant Names "Database" Which will point to you ini file which must be placed in your file structure and included.

An example is shown below.

```
<?php >
define("BasePath","/var/www/website1");
define("DATABASE",BasePath."/App/Config/Ini/Connection.ini");
define("INI",BasePath."/App/Config/Ini");
?>
```
</li>
</ul>

if you choose not to load an ini



