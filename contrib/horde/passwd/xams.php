<?php
/**
 * xams.php
 *
 * Copyright (c) 2006 The XAMS development team <info@xams.org>
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: xams.php 944 2006-01-17 22:05:48Z siegmar $
 *
 * Some code borrowed from Horde's Passwd_Driver_sql.
 *
 * @package Passwd
 */

require_once dirname(__FILE__) . '/sql.php';

class Passwd_Driver_xams extends Passwd_Driver_sql {

    /**
     * Constructs a new Passwd_Driver_xams object.
     *
     * @param array $params  A hash containing connection parameters.
     */
    function Passwd_Driver_xams($params = array())
    {
        if (isset($params['phptype'])) {
            $this->_params['phptype'] = $params['phptype'];
        } else {
            return PEAR::raiseError(_("Required 'phptype' not specified in Passwd XAMS configuration."));
        }

        /* Use defaults from Horde, but allow overriding in backends.php. */
        $this->_params = array_merge(Horde::getDriverConfig('', 'xams'), $params);

        /* These default to matching the Auth_sql defaults. */
        $this->_params['encryption'] = isset($params['encryption']) ? $params['encryption'] : 'md5';
        $this->_params['show_encryption'] = isset($params['show_encryption']) ? $params['show_encryption'] : false;
    }

    /**
     * Find out if a username and password is valid.
     *
     * @param string $userID        The userID to check.
     * @param string $old_password  An old password to check.
     *
     * @return boolean  True on valid or PEAR_Error on invalid.
     */
    function _lookup($user, $old_password)
    {
        /* Connect to the database */
        $res = $this->_connect();
        if (is_a($res, 'PEAR_Error')) {
            return $res;
        }

        /* Build the SQL query. */
        if (strpos($user, '@') === false)
        {
            $sql = 'SELECT     pm_users.id,
                               pm_users.password
                    FROM       pm_sites
                    INNER JOIN pm_domains
                    ON         pm_sites.id = pm_domains.siteid
                    INNER JOIN pm_users
                    ON         pm_sites.id = pm_users.siteid
                    WHERE      pm_users.uniquename = ?';
            $values = array($user);
        }
        else
        {
            $sql = 'SELECT     pm_users.id,
                               pm_users.password
                    FROM       pm_sites
                    INNER JOIN pm_domains
                    ON         pm_sites.id = pm_domains.siteid
                    INNER JOIN pm_users
                    ON         pm_sites.id = pm_users.siteid
                    WHERE      pm_users.name = ?
                    AND        pm_domains.name = ?';
            $values = explode('@', $user);
        }

        Horde::logMessage('SQL Query by Passwd_Driver_sql::_lookup(): ' . $sql, __FILE__, __LINE__, PEAR_LOG_DEBUG);

        /* Execute the query. */
        $result = $this->_db->query($sql, $values);
        if (!is_a($result, 'PEAR_Error')) {
            $row = $result->fetchRow(DB_FETCHMODE_ASSOC);
            $result->free();
            if (is_array($row)) {
                /* Get the password from the database. */
                $current_password = $row['password'];

                /* Check the passwords match. */
                $ret = $this->comparePasswords($current_password, $old_password);

                return ($ret === true) ? $row['id'] : $ret;
            }
        }
        return PEAR::raiseError(_("User not found"));
    }

    /**
     * Modify (update) a mysql password record for a user.
     *
     * @param string $user          The user whose record we will udpate.
     * @param string $new_password  The new password value to set.
     *
     * @return boolean  True or False based on success of the modify.
     */
    function _modify($user, $new_password)
    {
        /* Connect to the database. */
        $res = $this->_connect();
        if (is_a($res, 'PEAR_Error')) {
            return $res;
        }

        /* Encrypt the password. */
        $new_password = $this->encryptPassword($new_password, $this->_params['show_encryption']);

        /* Build the SQL query. */
        $sql = 'UPDATE pm_users
                SET    password = ?
                WHERE  id = ?';
        $values = array($new_password, $user);
        Horde::logMessage('SQL Query by Passwd_Driver_sql::_modify(): ' . $sql, __FILE__, __LINE__, PEAR_LOG_DEBUG);

        /* Execute the query. */
        $result = $this->_db->query($sql, $values);

        if (is_a($result, 'PEAR_Error')) {
            return $result;
        }

        return true;
    }

    /**
     * Change the user's password.
     *
     * @param string $username      The user for which to change the password.
     * @param string $old_password  The old (current) user password.
     * @param string $new_password  The new user password to set.
     *
     * @return boolean  True or false based on success of the change.
     */
    function changePassword($username,  $old_password, $new_password)
    {
        /* Check the current password. */
        $res = $this->_lookup($username, $old_password);
        if (is_a($res, 'PEAR_Error'))  {
            return $res;
        }

        return $this->_modify($res, $new_password);
    }

}
