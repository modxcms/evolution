<?php
$this->old = new OldFunctions();

class OldFunctions
{
    /**
     * @deprecated
     *
     * return @void
     */
    public function dbConnect()
    {
        global $modx;
        $modx->db->connect();
        $modx->rs = $modx->db->conn;
    }

    /**
     * @deprecated
     *
     * @param $sql
     * @return bool|mysqli_result|resource
     */
    public function dbQuery($sql)
    {
        global $modx;

        return $modx->db->query($sql);
    }

    /**
     * @deprecated
     *
     * @param $rs
     * @return int
     */
    public function recordCount($rs)
    {
        global $modx;

        return $modx->db->getRecordCount($rs);
    }

    /**
     * @deprecated
     *
     * @param $rs
     * @param string $mode
     * @return array|bool|mixed|object|stdClass
     */
    public function fetchRow($rs, $mode = 'assoc')
    {
        global $modx;

        return $modx->db->getRow($rs, $mode);
    }

    /**
     * @deprecated
     *
     * @param $rs
     * @return int
     */
    public function affectedRows($rs)
    {
        global $modx;

        return $modx->db->getAffectedRows($rs);
    }

    /**
     * @deprecated
     *
     * @param $rs
     * @return int|mixed
     */
    public function insertId($rs)
    {
        global $modx;

        return $modx->db->getInsertId($rs);
    }

    /**
     * @deprecated
     *
     * @return void
     */
    public function dbClose()
    {
        global $modx;
        $modx->db->disconnect();
    }

    /**
     * @deprecated
     *
     * @param array $array
     * @param string $ulroot
     * @param string $ulprefix
     * @param string $type
     * @param bool $ordered
     * @param int $tablevel
     * @return string
     */
    public function makeList($array, $ulroot = 'root', $ulprefix = 'sub_', $type = '', $ordered = false, $tablevel = 0)
    {
        // first find out whether the value passed is an array
        if (!is_array($array)) {
            return "<ul><li>Bad list</li></ul>";
        }
        if (!empty ($type)) {
            $typestr = " style='list-style-type: $type'";
        } else {
            $typestr = "";
        }
        $tabs = "";
        for ($i = 0; $i < $tablevel; $i++) {
            $tabs .= "\t";
        }
        $listhtml = $ordered == true ? $tabs . "<ol class='$ulroot'$typestr>\n" : $tabs . "<ul class='$ulroot'$typestr>\n";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $listhtml .= $tabs . "\t<li>" . $key . "\n" . $this->makeList($value, $ulprefix . $ulroot, $ulprefix,
                        $type, $ordered, $tablevel + 2) . $tabs . "\t</li>\n";
            } else {
                $listhtml .= $tabs . "\t<li>" . $value . "</li>\n";
            }
        }
        $listhtml .= $ordered == true ? $tabs . "</ol>\n" : $tabs . "</ul>\n";

        return $listhtml;
    }

    /**
     * @deprecated
     *
     * @return array
     */
    public function getUserData()
    {
        $client = array();
        $client['ip'] = $_SERVER['REMOTE_ADDR'];
        $client['ua'] = $_SERVER['HTTP_USER_AGENT'];

        return $client;
    }

    /**
     * Returns true, install or interact when inside manager
     *
     * @deprecated
     *
     * @return bool|string
     */
    public function insideManager()
    {
        $m = false;
        if (defined('IN_MANAGER_MODE') && IN_MANAGER_MODE === true) {
            $m = true;
            if (defined('SNIPPET_INTERACTIVE_MODE') && SNIPPET_INTERACTIVE_MODE == 'true') {
                $m = "interact";
            } else {
                if (defined('SNIPPET_INSTALL_MODE') && SNIPPET_INSTALL_MODE == 'true') {
                    $m = "install";
                }
            }
        }

        return $m;
    }

    /**
     * @deprecated
     *
     * @param $chunkName
     * @return bool|string
     */
    public function putChunk($chunkName)
    { // alias name >.<
        global $modx;

        return $modx->getChunk($chunkName);
    }

    /**
     * @deprecated
     *
     * @return array|string
     */
    public function getDocGroups()
    {
        global $modx;

        return $modx->getUserDocGroups();
    }

    /**
     * @deprecated
     *
     * @param string $o
     * @param string $n
     * @return bool|string
     */
    public function changePassword($o, $n)
    {
        global $modx;

        return $modx->changeWebUserPassword($o, $n);
    }

    /**
     * @deprecated
     *
     * @return array|bool
     */
    public function userLoggedIn()
    {
        global $modx;
        $userdetails = array();
        if ($modx->isFrontend() && isset ($_SESSION['webValidated'])) {
            // web user
            $userdetails['loggedIn'] = true;
            $userdetails['id'] = $_SESSION['webInternalKey'];
            $userdetails['username'] = $_SESSION['webShortname'];
            $userdetails['usertype'] = 'web'; // added by Raymond

            return $userdetails;
        } else {
            if ($modx->isBackend() && isset ($_SESSION['mgrValidated'])) {
                // manager user
                $userdetails['loggedIn'] = true;
                $userdetails['id'] = $_SESSION['mgrInternalKey'];
                $userdetails['username'] = $_SESSION['mgrShortname'];
                $userdetails['usertype'] = 'manager'; // added by Raymond

                return $userdetails;
            } else {
                return false;
            }
        }
    }

    /**
     * @deprecated
     *
     * @param string $method
     * @param string $prefix
     * @param string $trim
     * @param $REQUEST_METHOD
     * @return array|bool
     */
    public function getFormVars($method = "", $prefix = "", $trim = "", $REQUEST_METHOD)
    {
        //  function to retrieve form results into an associative array
        global $modx;
        $results = array();
        $method = strtoupper($method);
        if ($method == "") {
            $method = $REQUEST_METHOD;
        }
        if ($method == "POST") {
            $method = &$_POST;
        } elseif ($method == "GET") {
            $method = &$_GET;
        } else {
            return false;
        }
        reset($method);
        foreach ($method as $key => $value) {
            if (($prefix != "") && (substr($key, 0, strlen($prefix)) == $prefix)) {
                if ($trim) {
                    $pieces = explode($prefix, $key, 2);
                    $key = $pieces[1];
                    $results[$key] = $value;
                } else {
                    $results[$key] = $value;
                }
            } elseif ($prefix == "") {
                $results[$key] = $value;
            }
        }

        return $results;
    }

    /**
     * Displays a javascript alert message in the web browser
     *
     * @deprecated
     *
     * @param string $msg Message to show
     * @param string $url URL to redirect to
     */
    public function webAlert($msg, $url = "")
    {
        global $modx;
        $msg = addslashes($modx->db->escape($msg));
        if (substr(strtolower($url), 0, 11) == "javascript:") {
            $act = "__WebAlert();";
            $fnc = "function __WebAlert(){" . substr($url, 11) . "};";
        } else {
            $act = ($url ? "window.location.href='" . addslashes($url) . "';" : "");
        }
        $html = "<script>$fnc window.setTimeout(\"alert('$msg');$act\",100);</script>";
        if ($modx->isFrontend()) {
            $modx->regClientScript($html);
        } else {
            echo $html;
        }
    }

    ########################################
    // END New database functions - rad14701
    ########################################
}
