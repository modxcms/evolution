<?php

if (!function_exists('evolutionCMS')) {
    /**
     * @return DocumentParser
     */
    function evolutionCMS()
    {
        if (!defined('MODX_CLASS')) {
            if (!class_exists('\DocumentParser')) {
                throw new RuntimeException('MODX_CLASS not defined and EvolutionCMS\Core class not exists');
            }
            define('MODX_CLASS', '\DocumentParser');
        }

        global $modx;
        if ($modx === null) {
            try {
                $obj = new ReflectionClass(MODX_CLASS);
                $modx = $obj->newInstanceWithoutConstructor()->getInstance();
            } catch (ReflectionException $exception) {
                echo $exception->getMessage();
                exit($exception->getCode());
            }
        }

        return $modx;
    }
}

if (!function_exists('evo')) {
    /**
     * @return DocumentParser
     */
    function evo()
    {
        return evolutionCMS();
    }
}

if (!function_exists('genEvoSessionName')) {
    /**
     * @return string
     */
    function genEvoSessionName()
    {
        $_ = crc32(__FILE__);
        $_ = sprintf('%u', $_);

        return 'evo' . base_convert($_, 10, 36);
    }
}

if (!function_exists('startCMSSession')) {
    /**
     * @return void
     */
    function startCMSSession()
    {
        global $session_cookie_path, $session_cookie_domain;
        if (is_cli()) {
            return;
        }

        session_name(SESSION_COOKIE_NAME);
        removeInvalidCmsSessionIds(SESSION_COOKIE_NAME);
        session_cache_limiter('');
        if (isset ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') {
            $secure = true;
        } else {
            $secure = ($_SERVER['SERVER_PORT'] == HTTPS_PORT);
        }
        session_set_cookie_params(
            0
            , $session_cookie_path ? $session_cookie_path : MODX_BASE_URL
            , $session_cookie_domain ? $session_cookie_domain : ''
            , $secure
            , true
        );

        if (SESSION_STORAGE == 'redis' && class_exists('Redis')) {
            $redis = new Redis();
            if ($redis->connect(env('REDIS_HOST', '127.0.0.1'),
                    env('REDIS_PORT', 6379),
                    env('REDIS_TIMEOUT', 60),
                    NULL,
                    0,
                    0,
                    ['auth' => [env('REDIS_USER', null), env('REDIS_PASS', null)]])
                && $redis->select(env('REDIS_SESSION_DATABASE', 0))) {
                try {
                    $handler = new \suffi\RedisSessionHandler\RedisSessionHandler($redis);
                    session_set_save_handler($handler);
                } catch (RedisException $exception) {

                } catch (\Exception $exception) {

                }
            }
        }

        session_start();
        $key = "modx.mgr.session.cookie.lifetime";

        if (isset($_SESSION[$key]) && is_numeric($_SESSION[$key])) {
            setcookie(
                session_name()
                , session_id()
                , (int)$_SESSION[$key] ? $_SERVER['REQUEST_TIME'] + (int)$_SESSION[$key] : 0
                , $session_cookie_path ? $session_cookie_path : MODX_BASE_URL
                , $session_cookie_domain ? $session_cookie_domain : ''
                , $secure
                , true
            );
        }
        if (!isset($_SESSION['modx.session.created.time'])) {
            $_SESSION['modx.session.created.time'] = $_SERVER['REQUEST_TIME'];
        }
    }
}

if (!function_exists('removeInvalidCmsSessionFromStorage')) {
    /**
     * @param $storage
     * @param $session_name
     * @return void
     */
    function removeInvalidCmsSessionFromStorage(&$storage, $session_name)
    {
        if (isset($storage[$session_name]) && ($storage[$session_name] === '' || $storage[$session_name] === 'deleted')) {
            unset($storage[$session_name]);
        }
    }
}

if (!function_exists('removeInvalidCmsSessionIds')) {
    /**
     * @param $session_name
     * @return void
     */
    function removeInvalidCmsSessionIds($session_name)
    {
        if (is_cli()) {
            return;
        }
        // session ids is invalid iff it is empty string
        // storage priorioty can see in PHP source ext/session/session.c
        removeInvalidCmsSessionFromStorage($_COOKIE, $session_name);
        removeInvalidCmsSessionFromStorage($_GET, $session_name);
        removeInvalidCmsSessionFromStorage($_POST, $session_name);
    }
}

if (!function_exists('modx_sanitize_gpc')) {
    /**
     * @param array|string $values
     * @param int $depth
     * @return array|string
     */
    function modx_sanitize_gpc(&$values, $depth = 0)
    {
        if (200 < $depth) {
            exit('GPC Array nested too deep!');
        }

        if (!is_array($values)) {
            return getSanitizedValue($values);
        }

        $depth++;
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                modx_sanitize_gpc($value, $depth);
            } else {
                $values[$key] = getSanitizedValue($value);
            }
        }

        return $values;
    }
}

if (!function_exists('getSanitizedValue')) {
    /**
     * @param string $value
     * @return string
     */
    function getSanitizedValue($value = '')
    {
        if (empty($value)) {
            return $value;
        }

        $brackets = explode(' ', '[[ ]] [! !] [* *] [( )] {{ }} [+ +] [~ ~] [^ ^]');
        foreach ($brackets as $bracket) {
            if (strpos($value, $bracket) === false) {
                continue;
            }
            $sanitizedBracket = str_replace(
                '#',
                MODX_SANITIZE_SEED,
                sprintf('#%s#%s#', substr($bracket, 0, 1), substr($bracket, 1, 1))
            );
            $value = str_replace($bracket, $sanitizedBracket, $value);
        }
        $value = str_ireplace('<script', 'sanitized_by_modx<s cript', $value);
        $value = preg_replace('/&#(\d+);/', 'sanitized_by_modx& #$1', $value);

        return $value;
    }
}
if (!function_exists('removeSanitizeSeed')) {
    /**
     * @param string $string
     * @return string
     */
    function removeSanitizeSeed($string = '')
    {
        if (!$string || strpos($string, MODX_SANITIZE_SEED) === false) {
            return $string;
        }

        return str_replace(MODX_SANITIZE_SEED, '', $string);
    }
}
