<?php

########################################################################
# Extension Manager/Repository config file for ext: "adodb"
#
# Auto generated 27-12-2005 16:18
#
# Manual updates:
# Only the data in the array - anything else is removed by next write
########################################################################

$EM_CONF[$_EXTKEY] = Array (
	'title' => 'ADOdb',
	'description' => 'This extension just includes a current version of ADOdb, a database abstraction library for PHP, for further use in TYPO3',
	'category' => 'misc',
	'shy' => 1,
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'TYPO3_version' => '3.7.2-0.0.2',
	'PHP_version' => '0.0.2-0.0.2',
	'module' => '',
	'state' => 'beta',
	'internal' => 0,
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Karsten Dambekalns',
	'author_email' => 'karsten@typo3.org',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'private' => 0,
	'download_password' => '',
	'version' => '1.0.3',	// Don't modify this! Managed automatically during upload to repository.
	'_md5_values_when_last_written' => 'a:241:{s:25:"checkconnectionwizard.php";s:4:"795b";s:27:"class.tx_adodb_tceforms.php";s:4:"e53a";s:26:"datasource_flexform_ds.xml";s:4:"96fb";s:12:"ext_icon.gif";s:4:"c778";s:17:"ext_localconf.php";s:4:"b58b";s:31:"locallang_datasource_config.xml";s:4:"f43f";s:20:"locallang_wizard.xml";s:4:"0ed9";s:26:"adodb/adodb-csvlib.inc.php";s:4:"5290";s:28:"adodb/adodb-datadict.inc.php";s:4:"e859";s:25:"adodb/adodb-error.inc.php";s:4:"d319";s:32:"adodb/adodb-errorhandler.inc.php";s:4:"3af6";s:29:"adodb/adodb-errorpear.inc.php";s:4:"b290";s:30:"adodb/adodb-exceptions.inc.php";s:4:"cbb5";s:28:"adodb/adodb-iterator.inc.php";s:4:"2a7e";s:23:"adodb/adodb-lib.inc.php";s:4:"ff7b";s:25:"adodb/adodb-pager.inc.php";s:4:"0e2b";s:24:"adodb/adodb-pear.inc.php";s:4:"8b6a";s:24:"adodb/adodb-perf.inc.php";s:4:"9e7a";s:24:"adodb/adodb-php4.inc.php";s:4:"79e0";s:24:"adodb/adodb-time.inc.php";s:4:"a770";s:29:"adodb/adodb-xmlschema.inc.php";s:4:"aac6";s:19:"adodb/adodb.inc.php";s:4:"f36b";s:17:"adodb/license.txt";s:4:"8bd7";s:24:"adodb/pivottable.inc.php";s:4:"b193";s:16:"adodb/readme.txt";s:4:"856d";s:22:"adodb/rsfilter.inc.php";s:4:"94ea";s:16:"adodb/server.php";s:4:"6ce1";s:22:"adodb/toexport.inc.php";s:4:"218f";s:20:"adodb/tohtml.inc.php";s:4:"c3c9";s:19:"adodb/xmlschema.dtd";s:4:"26f3";s:27:"adodb/perf/perf-db2.inc.php";s:4:"bc62";s:32:"adodb/perf/perf-informix.inc.php";s:4:"34a9";s:29:"adodb/perf/perf-mssql.inc.php";s:4:"c275";s:29:"adodb/perf/perf-mysql.inc.php";s:4:"25fe";s:28:"adodb/perf/perf-oci8.inc.php";s:4:"3aed";s:32:"adodb/perf/perf-postgres.inc.php";s:4:"fba4";s:22:"adodb/perf/CVS/Entries";s:4:"4a34";s:25:"adodb/perf/CVS/Repository";s:4:"dbc5";s:19:"adodb/perf/CVS/Root";s:4:"b923";s:38:"adodb/session/adodb-compress-bzip2.php";s:4:"652e";s:37:"adodb/session/adodb-compress-gzip.php";s:4:"bab4";s:36:"adodb/session/adodb-cryptsession.php";s:4:"6829";s:38:"adodb/session/adodb-encrypt-mcrypt.php";s:4:"ce8f";s:35:"adodb/session/adodb-encrypt-md5.php";s:4:"216a";s:38:"adodb/session/adodb-encrypt-secret.php";s:4:"5309";s:36:"adodb/session/adodb-encrypt-sha1.php";s:4:"5c92";s:28:"adodb/session/adodb-sess.txt";s:4:"260b";s:36:"adodb/session/adodb-session-clob.php";s:4:"e4f0";s:31:"adodb/session/adodb-session.php";s:4:"2e4a";s:38:"adodb/session/adodb-sessions.mysql.sql";s:4:"42fe";s:44:"adodb/session/adodb-sessions.oracle.clob.sql";s:4:"3c64";s:39:"adodb/session/adodb-sessions.oracle.sql";s:4:"08d0";s:27:"adodb/session/crypt.inc.php";s:4:"4dad";s:32:"adodb/session/session_schema.xml";s:4:"6443";s:40:"adodb/session/old/adodb-cryptsession.php";s:4:"d7a2";s:40:"adodb/session/old/adodb-session-clob.php";s:4:"4e67";s:35:"adodb/session/old/adodb-session.php";s:4:"23df";s:31:"adodb/session/old/crypt.inc.php";s:4:"5a4e";s:29:"adodb/session/old/CVS/Entries";s:4:"7273";s:32:"adodb/session/old/CVS/Repository";s:4:"0805";s:26:"adodb/session/old/CVS/Root";s:4:"b923";s:25:"adodb/session/CVS/Entries";s:4:"0f66";s:28:"adodb/session/CVS/Repository";s:4:"7999";s:22:"adodb/session/CVS/Root";s:4:"b923";s:26:"adodb/pear/readme.Auth.txt";s:4:"4970";s:35:"adodb/pear/Auth/Container/ADOdb.php";s:4:"d121";s:37:"adodb/pear/Auth/Container/CVS/Entries";s:4:"3037";s:40:"adodb/pear/Auth/Container/CVS/Repository";s:4:"4fb1";s:34:"adodb/pear/Auth/Container/CVS/Root";s:4:"b923";s:27:"adodb/pear/Auth/CVS/Entries";s:4:"38cc";s:30:"adodb/pear/Auth/CVS/Repository";s:4:"b224";s:24:"adodb/pear/Auth/CVS/Root";s:4:"b923";s:22:"adodb/pear/CVS/Entries";s:4:"ccc4";s:25:"adodb/pear/CVS/Repository";s:4:"a002";s:19:"adodb/pear/CVS/Root";s:4:"b923";s:34:"adodb/drivers/adodb-access.inc.php";s:4:"148a";s:31:"adodb/drivers/adodb-ado.inc.php";s:4:"1142";s:32:"adodb/drivers/adodb-ado5.inc.php";s:4:"24ab";s:38:"adodb/drivers/adodb-ado_access.inc.php";s:4:"5752";s:37:"adodb/drivers/adodb-ado_mssql.inc.php";s:4:"cbc9";s:41:"adodb/drivers/adodb-borland_ibase.inc.php";s:4:"44b2";s:31:"adodb/drivers/adodb-csv.inc.php";s:4:"c303";s:31:"adodb/drivers/adodb-db2.inc.php";s:4:"2778";s:33:"adodb/drivers/adodb-fbsql.inc.php";s:4:"77c1";s:36:"adodb/drivers/adodb-firebird.inc.php";s:4:"48c0";s:33:"adodb/drivers/adodb-ibase.inc.php";s:4:"e5fc";s:36:"adodb/drivers/adodb-informix.inc.php";s:4:"a21f";s:38:"adodb/drivers/adodb-informix72.inc.php";s:4:"c545";s:32:"adodb/drivers/adodb-ldap.inc.php";s:4:"4ae2";s:33:"adodb/drivers/adodb-mssql.inc.php";s:4:"98d2";s:35:"adodb/drivers/adodb-mssqlpo.inc.php";s:4:"5954";s:33:"adodb/drivers/adodb-mysql.inc.php";s:4:"247c";s:34:"adodb/drivers/adodb-mysqli.inc.php";s:4:"18a2";s:34:"adodb/drivers/adodb-mysqlt.inc.php";s:4:"9dbb";s:35:"adodb/drivers/adodb-netezza.inc.php";s:4:"bdbf";s:32:"adodb/drivers/adodb-oci8.inc.php";s:4:"de93";s:34:"adodb/drivers/adodb-oci805.inc.php";s:4:"d1d9";s:34:"adodb/drivers/adodb-oci8po.inc.php";s:4:"5999";s:32:"adodb/drivers/adodb-odbc.inc.php";s:4:"9ae2";s:38:"adodb/drivers/adodb-odbc_mssql.inc.php";s:4:"5cdd";s:39:"adodb/drivers/adodb-odbc_oracle.inc.php";s:4:"67b2";s:33:"adodb/drivers/adodb-odbtp.inc.php";s:4:"3dab";s:41:"adodb/drivers/adodb-odbtp_unicode.inc.php";s:4:"81d2";s:34:"adodb/drivers/adodb-oracle.inc.php";s:4:"1b05";s:31:"adodb/drivers/adodb-pdo.inc.php";s:4:"78ed";s:37:"adodb/drivers/adodb-pdo_mssql.inc.php";s:4:"150e";s:37:"adodb/drivers/adodb-pdo_mysql.inc.php";s:4:"d636";s:35:"adodb/drivers/adodb-pdo_oci.inc.php";s:4:"a490";s:37:"adodb/drivers/adodb-pdo_pgsql.inc.php";s:4:"992a";s:36:"adodb/drivers/adodb-postgres.inc.php";s:4:"2356";s:38:"adodb/drivers/adodb-postgres64.inc.php";s:4:"e138";s:37:"adodb/drivers/adodb-postgres7.inc.php";s:4:"a6b5";s:37:"adodb/drivers/adodb-postgres8.inc.php";s:4:"3b16";s:33:"adodb/drivers/adodb-proxy.inc.php";s:4:"309b";s:33:"adodb/drivers/adodb-sapdb.inc.php";s:4:"edef";s:39:"adodb/drivers/adodb-sqlanywhere.inc.php";s:4:"b092";s:34:"adodb/drivers/adodb-sqlite.inc.php";s:4:"4851";s:36:"adodb/drivers/adodb-sqlitepo.inc.php";s:4:"4387";s:34:"adodb/drivers/adodb-sybase.inc.php";s:4:"2234";s:38:"adodb/drivers/adodb-sybase_ase.inc.php";s:4:"bd80";s:31:"adodb/drivers/adodb-vfp.inc.php";s:4:"8cd1";s:25:"adodb/drivers/CVS/Entries";s:4:"8988";s:28:"adodb/drivers/CVS/Repository";s:4:"7121";s:22:"adodb/drivers/CVS/Root";s:4:"b923";s:27:"adodb/lang/adodb-ar.inc.php";s:4:"6f2a";s:27:"adodb/lang/adodb-bg.inc.php";s:4:"666d";s:31:"adodb/lang/adodb-bgutf8.inc.php";s:4:"6316";s:27:"adodb/lang/adodb-ca.inc.php";s:4:"96da";s:27:"adodb/lang/adodb-cn.inc.php";s:4:"155e";s:27:"adodb/lang/adodb-cz.inc.php";s:4:"6964";s:27:"adodb/lang/adodb-da.inc.php";s:4:"2ea2";s:27:"adodb/lang/adodb-de.inc.php";s:4:"26c5";s:27:"adodb/lang/adodb-en.inc.php";s:4:"0820";s:27:"adodb/lang/adodb-es.inc.php";s:4:"de07";s:34:"adodb/lang/adodb-esperanto.inc.php";s:4:"32b9";s:27:"adodb/lang/adodb-fr.inc.php";s:4:"dd47";s:27:"adodb/lang/adodb-hu.inc.php";s:4:"f308";s:27:"adodb/lang/adodb-it.inc.php";s:4:"15e2";s:27:"adodb/lang/adodb-nl.inc.php";s:4:"ed3d";s:27:"adodb/lang/adodb-pl.inc.php";s:4:"333e";s:30:"adodb/lang/adodb-pt-br.inc.php";s:4:"e973";s:27:"adodb/lang/adodb-ro.inc.php";s:4:"779e";s:31:"adodb/lang/adodb-ru1251.inc.php";s:4:"e828";s:27:"adodb/lang/adodb-sv.inc.php";s:4:"81fa";s:31:"adodb/lang/adodb-uk1251.inc.php";s:4:"3203";s:22:"adodb/lang/CVS/Entries";s:4:"dfda";s:25:"adodb/lang/CVS/Repository";s:4:"a65e";s:19:"adodb/lang/CVS/Root";s:4:"b923";s:25:"adodb/tests/benchmark.php";s:4:"903d";s:31:"adodb/tests/cf~testsessions.php";s:4:"06ba";s:22:"adodb/tests/client.php";s:4:"62db";s:19:"adodb/tests/pdo.php";s:4:"124c";s:18:"adodb/tests/rr.htm";s:4:"01a5";s:29:"adodb/tests/test-datadict.php";s:4:"e9c4";s:25:"adodb/tests/test-perf.php";s:4:"b95e";s:27:"adodb/tests/test-pgblob.php";s:4:"62b6";s:25:"adodb/tests/test-php5.php";s:4:"181d";s:30:"adodb/tests/test-xmlschema.php";s:4:"1ff1";s:20:"adodb/tests/test.php";s:4:"b2dc";s:21:"adodb/tests/test2.php";s:4:"384b";s:21:"adodb/tests/test3.php";s:4:"ea46";s:21:"adodb/tests/test4.php";s:4:"1ba1";s:21:"adodb/tests/test5.php";s:4:"5c2f";s:29:"adodb/tests/test_rs_array.php";s:4:"1ccf";s:25:"adodb/tests/testcache.php";s:4:"3273";s:33:"adodb/tests/testdatabases.inc.php";s:4:"7507";s:25:"adodb/tests/testgenid.php";s:4:"1576";s:25:"adodb/tests/testmssql.php";s:4:"ea68";s:24:"adodb/tests/testoci8.php";s:4:"9563";s:30:"adodb/tests/testoci8cursor.php";s:4:"1c02";s:26:"adodb/tests/testpaging.php";s:4:"a82e";s:24:"adodb/tests/testpear.php";s:4:"71e0";s:28:"adodb/tests/testsessions.php";s:4:"92f1";s:20:"adodb/tests/time.php";s:4:"b158";s:22:"adodb/tests/tmssql.php";s:4:"2933";s:31:"adodb/tests/xmlschema-mssql.xml";s:4:"5c70";s:25:"adodb/tests/xmlschema.xml";s:4:"19ca";s:23:"adodb/tests/CVS/Entries";s:4:"5729";s:26:"adodb/tests/CVS/Repository";s:4:"5257";s:20:"adodb/tests/CVS/Root";s:4:"b923";s:35:"adodb/cute_icons_for_site/adodb.gif";s:4:"9430";s:36:"adodb/cute_icons_for_site/adodb2.gif";s:4:"f540";s:37:"adodb/cute_icons_for_site/CVS/Entries";s:4:"0aa2";s:40:"adodb/cute_icons_for_site/CVS/Repository";s:4:"10d9";s:34:"adodb/cute_icons_for_site/CVS/Root";s:4:"b923";s:38:"adodb/datadict/datadict-access.inc.php";s:4:"fcc7";s:35:"adodb/datadict/datadict-db2.inc.php";s:4:"c3dd";s:40:"adodb/datadict/datadict-firebird.inc.php";s:4:"77eb";s:39:"adodb/datadict/datadict-generic.inc.php";s:4:"39aa";s:37:"adodb/datadict/datadict-ibase.inc.php";s:4:"5cd0";s:40:"adodb/datadict/datadict-informix.inc.php";s:4:"3dbb";s:37:"adodb/datadict/datadict-mssql.inc.php";s:4:"3ac8";s:37:"adodb/datadict/datadict-mysql.inc.php";s:4:"ad57";s:36:"adodb/datadict/datadict-oci8.inc.php";s:4:"eb0d";s:40:"adodb/datadict/datadict-postgres.inc.php";s:4:"4f5d";s:37:"adodb/datadict/datadict-sapdb.inc.php";s:4:"4ed3";s:38:"adodb/datadict/datadict-sybase.inc.php";s:4:"aaa3";s:26:"adodb/datadict/CVS/Entries";s:4:"c0b8";s:29:"adodb/datadict/CVS/Repository";s:4:"9e19";s:23:"adodb/datadict/CVS/Root";s:4:"b923";s:30:"adodb/contrib/toxmlrpc.inc.php";s:4:"4c40";s:25:"adodb/contrib/CVS/Entries";s:4:"5bc1";s:28:"adodb/contrib/CVS/Repository";s:4:"3c38";s:22:"adodb/contrib/CVS/Root";s:4:"b923";s:29:"adodb/xsl/convert-0.1-0.2.xsl";s:4:"f2a0";s:29:"adodb/xsl/convert-0.2-0.1.xsl";s:4:"5d27";s:24:"adodb/xsl/remove-0.2.xsl";s:4:"0b2b";s:21:"adodb/xsl/CVS/Entries";s:4:"149e";s:24:"adodb/xsl/CVS/Repository";s:4:"3cb3";s:18:"adodb/xsl/CVS/Root";s:4:"b923";s:25:"adodb/docs/docs-adodb.htm";s:4:"ebc7";s:28:"adodb/docs/docs-datadict.htm";s:4:"1311";s:26:"adodb/docs/docs-oracle.htm";s:4:"fc24";s:24:"adodb/docs/docs-perf.htm";s:4:"4d38";s:27:"adodb/docs/docs-session.htm";s:4:"1fa7";s:28:"adodb/docs/old-changelog.htm";s:4:"3158";s:21:"adodb/docs/readme.htm";s:4:"9a0e";s:32:"adodb/docs/tips_portable_sql.htm";s:4:"4027";s:19:"adodb/docs/tute.htm";s:4:"691e";s:22:"adodb/docs/CVS/Entries";s:4:"f8bd";s:25:"adodb/docs/CVS/Repository";s:4:"ad28";s:19:"adodb/docs/CVS/Root";s:4:"b923";s:17:"adodb/CVS/Entries";s:4:"a6ed";s:20:"adodb/CVS/Repository";s:4:"cd1a";s:14:"adodb/CVS/Root";s:4:"b923";s:26:"pi1/class.tx_adodb_pi1.php";s:4:"0414";s:15:"pi1/CVS/Entries";s:4:"563a";s:18:"pi1/CVS/Repository";s:4:"745a";s:12:"pi1/CVS/Root";s:4:"b923";s:23:"res/checkconnection.gif";s:4:"1760";s:15:"res/CVS/Entries";s:4:"5bbd";s:18:"res/CVS/Repository";s:4:"442c";s:12:"res/CVS/Root";s:4:"b923";s:18:"doc/468.DBAL.patch";s:4:"7740";s:10:"doc/README";s:4:"fc91";s:15:"doc/CVS/Entries";s:4:"428d";s:18:"doc/CVS/Repository";s:4:"f6db";s:12:"doc/CVS/Root";s:4:"b923";s:11:"CVS/Entries";s:4:"0720";s:14:"CVS/Repository";s:4:"bccf";s:8:"CVS/Root";s:4:"b923";}',
);

?>