# The Propel platform that will be used to determine how to build
# the SQL DDL, the PHP classes, etc.
propel.database = mysql

# The database PDO connection settings at builtime.
# This setting is required for the sql, reverse, and datasql tasks.
# Note that some drivers (e.g. mysql, oracle) require that you specify the
# username and password separately from the DSN, which is why they are
# available as options.
# Example PDO connection strings:
#   mysql:host=localhost;port=3307;dbname=testdb
#   sqlite:/opt/databases/mydb.sq3
#   sqlite::memory:
#   pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass
#   oci:dbname=//localhost:1521/mydb
propel.database.url = mysql:host=localhost;dbname=dev_phpindonesia
propel.database.user = travis
#propel.database.password = 
propel.mysql.tableType = "InnoDB"

# The database PDO connection settings at builtime for reverse engineer
# or data dump. The default is to use the database connection defined by the
# `propel.database.url` property.
propel.database.buildUrl = ${propel.database.url}

# The database PDO connection settings at builtime for creating a database.
# The default is to use the database connection defined by the
# `propel.database.url` property.
# Propel is unable to create databases for some vendors because they do not
# provide a SQL method for creation; therefore, it is usually recommended that
# you actually create your database by hand.
propel.database.createUrl = ${propel.database.url}

# The encoding to use for the database.
# This can affect things such as transforming charsets when exporting to XML, etc.
propel.database.encoding = utf8

# Add a prefix to all the table names in the database.
# This does not affect the tables phpName.
# This setting can be overridden on a per-database basis in the schema.
propel.tablePrefix = phpid_


# The name of your project.
# This affects names of generated files, etc.
propel.project = phpindonesia

# If you use namespaces in your schemas, this setting tells Propel to use the
# namespace attribute for the package. Consequently, the namespace attribute
# will also stipulate the subdirectory in which model classes get generated.
propel.namespace.autoPackage = true

# Which Propel validators to add to the generated schema,
# based on the database constraints.{none}|maxvalue|type|required|unique|all
# You can cherry-pick allowed validators by using a comma-separated value, e.g
#propel.addValidators = maxvalue,type,required
propel.addValidators = all


# The directory where Propel should output classes, sql, config, etc.
propel.output.dir = ${propel.project.dir}/app

# The directory where Propel should output generated object model classes.
propel.php.dir = ${propel.output.dir}/../

############### MIGRATION CONFIGURATION #############################
# Name of the table Propel creates to keep the latest migration date
propel.migration.table = phpid_migration
# Whether the comparison between the XML schema and the database structure
# cares for differences in case (e.g. 'my_table' and 'MY_TABLE')
propel.migration.caseInsensitive = true
# The directory where migration classes are generated and looked for
propel.migration.dir = ${propel.output.dir}/migrations