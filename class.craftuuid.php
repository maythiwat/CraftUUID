<?php
/*
Demza Development
CraftUUID version 1.0.0
*/
namespace Demza;
class CraftUUID
{
    public static function getOfflinePlayer($username) {
        $data = hex2bin(md5("OfflinePlayer:" . $username));
        $data[6] = chr(ord($data[6]) & 0x0f | 0x30);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return self::createJavaUuid(bin2hex($data));
    }
    
    public static function getOnlinePlayer($username) {
        $json = file_get_contents('https://api.mojang.com/users/profiles/minecraft/' . $username);
        if (!empty($json)) {
            $data = json_decode($json, true);
            if (is_array($data) and !empty($data)) {
                return self::createJavaUuid($data['id']);
            }
        }
        return false;
    }
    
    public static function createJavaUuid($striped) {
        $components = array(
            substr($striped, 0, 8),
            substr($striped, 8, 4),
            substr($striped, 12, 4),
            substr($striped, 16, 4),
            substr($striped, 20),
        );
        return implode('-', $components);
    }
}
?>