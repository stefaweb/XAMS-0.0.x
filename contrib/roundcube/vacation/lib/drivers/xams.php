<?php

/*
 +-----------------------------------------------------------------------+
 | lib/drivers/xams.php                                                  |
 | Driver for Vacation/Autoreply stored in XAMS database                 |
 |                                                                       |
 | Copyright (C) 2009                                                    |
 | Licensed under the GNU GPL                                            |
 |                                                                       |
 | By Stephane Leclerc <sleclerc@actionweb.fr>                           |
 | Based on sql.php by Boris HUISGEN <bhuisgen@hbis.fr>                  |
 |                                                                       |
 +-----------------------------------------------------------------------+
 */

/*
 * Define xams config file.
 */
define('_XAMS_CONFIG_FILE', '/etc/xams/xams.conf');

/*
 * Read driver function.
 *
 * @param array $data the array of data to get and set.
 *
 * @return integer the status code.
 */
function vacation_read(array &$data)
{
	$rcmail = rcmail::get_instance();

	$ini = parse_ini_file(_XAMS_CONFIG_FILE, true);
	if (!$ini) die('Couldn\'t load '. _XAMS_CONFIG_FILE);

	$dsn = array();
	$dsn['new_link'] = true;
	$dsn['phptype'] = 'mysql';
	$dsn['hostspec'] = $ini['GUI']['DBHost'];
	$dsn['database'] = $ini['GUI']['DB'];
	$dsn['username'] = $ini['GUI']['DBUser'];
 	$dsn['password'] = $ini['GUI']['DBPass'];

	$db = new rcube_mdb2($dsn, '', FALSE);
	$db->set_debug((bool)$rcmail->config->get('sql_debug'));
	$db->db_connect('r');

	if ($err = $db->is_error())
	{
		return PLUGIN_ERROR_CONNECT;
	}

	$vacation_sql_read =
        array("SELECT pm_users.AutoReplySubject AS vacation_subject, " .
              "pm_users.AutoReplyText AS vacation_message, " .
              "pm_users.AutoReply AS vacation_enable " .
              "FROM xams.pm_sites " .
              "INNER JOIN xams.pm_domains ON pm_sites.id = pm_domains.siteid " .
              "INNER JOIN xams.pm_users ON pm_sites.id = pm_users.siteid " .
              "WHERE pm_users.name = %email_local AND pm_domains.name = %email_domain;"
        );

	// Read database queries
	foreach($vacation_sql_read as $query)
	{
		$search = array('%username', '%email_local', '%email_domain', '%email',
				'%vacation_enable', '%vacation_subject',
				'%vacation_message');
		$replace = array($db->quote($data['username']), $db->quote($data['email_local']),
		$db->quote($data['email_domain']), $db->quote($data['email']),
		$db->quote($data['vacation_enable']), $db->quote($data['vacation_subject']),
		$db->quote($data['vacation_message'])
		);
		$query = str_replace($search, $replace, $query);

		$sql_result = $db->query($query);
		if ($err = $db->is_error())
		{
			return PLUGIN_ERROR_PROCESS;
		}
			
		$sql_arr = $db->fetch_assoc($sql_result);
		if (empty($sql_arr))
		{
			continue;
		}

		if (isset($sql_arr['email']))
		{
			$data['email'] = $sql_arr['email'];
		}

		if (isset($sql_arr['email_local']))
		{
			$data['email_local'] = $sql_arr['email_local'];
		}

		if (isset($sql_arr['email_domain']))
		{
			$data['email_domain'] = $sql_arr['email_domain'];
		}

		if (isset($sql_arr['vacation_enable']))
		{
			if ($sql_arr['vacation_enable'] == "true")
                	{
                        	$data['vacation_enable'] = 1;
                	}
                	if ($sql_arr['vacation_enable'] == "false")	
                	{
                        	$data['vacation_enable'] = 0;
                	}
		}

		if (isset($sql_arr['vacation_subject']))
		{
			if ($sql_arr['vacation_subject'] == '')
			{
				$data['vacation_subject'] = $rcmail->config->get('vacation_gui_vacationsubject_default');
			}
			else
			{
				$data['vacation_subject'] = $sql_arr['vacation_subject'];
			}
		}

		if (isset($sql_arr['vacation_message']))
		{
			$data['vacation_message'] = $sql_arr['vacation_message'];
		}
	}

	return PLUGIN_NOERROR;
}

/*
 * Write driver function.
 *
 * @param array $data the array of data to get and set.
 *
 * @return integer the status code.
 */
function vacation_write(array &$data)
{
	$rcmail = rcmail::get_instance();

        $rcmail = rcmail::get_instance();

        $ini = parse_ini_file(_XAMS_CONFIG_FILE, true);
        if (!$ini) die('Couldn\'t load '. _XAMS_CONFIG_FILE);

        $dsn = array();
        $dsn['new_link'] = true;
        $dsn['phptype'] = 'mysql';
        $dsn['hostspec'] = $ini['GUI']['DBHost'];
        $dsn['database'] = $ini['GUI']['DB'];
        $dsn['username'] = $ini['GUI']['DBUser'];
        $dsn['password'] = $ini['GUI']['DBPass'];

        $db = new rcube_mdb2($dsn, '', FALSE);
        $db->set_debug((bool)$rcmail->config->get('sql_debug'));
        $db->db_connect('w');

	if ($err = $db->is_error())
	{
		return PLUGIN_ERROR_CONNECT;
	}

	// Write database queries
	$vacation_sql_write =
        array("UPDATE xams.pm_users " .
              "INNER JOIN xams.pm_sites ON pm_sites.id = pm_users.siteid " .
              "INNER JOIN xams.pm_domains ON pm_sites.id = pm_domains.siteid " .
              "SET pm_users.AutoReplySubject = %vacation_subject, " .
              "pm_users.AutoReplyText = %vacation_message, " .
              "pm_users.AutoReply = %vacation_enable " .
              "WHERE pm_users.name = %email_local AND pm_domains.name = %email_domain;"
        );

	foreach($vacation_sql_write as $query)
	{
	$vacation_enable_status = "false";
                if ($data['vacation_enable'] == 0)
                {
			$vacation_enable_status = "false";
                }
               	if ($data['vacation_enable'] == 1) 
                {
			$vacation_enable_status = "true";
                }
		$search = array('%username', '%email_local', '%email_domain', '%email',
				'%vacation_enable', '%vacation_subject',
				'%vacation_message');
		$replace = array($db->quote($data['username']), $db->quote($data['email_local']),
		$db->quote($data['email_domain']), $db->quote($data['email']),
		$db->quote($vacation_enable_status), $db->quote($data['vacation_subject']),
		$db->quote($data['vacation_message'])
		);
		$query = str_replace($search, $replace, $query);
		$sql_result = $db->query($query);

		if ($err = $db->is_error())
		{
			return PLUGIN_ERROR_PROCESS;
		}
	}

	return PLUGIN_NOERROR;
}

?>
