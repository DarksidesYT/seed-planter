<?php

namespace darksidesyt\SeedPlanter\Utils;

use darksidesyt\SeedPlanter\Main;

class Utils {

    /**
     * @param string $value
     * @return bool|array
     */
    public static function getIntoConfig(string $value) {
        $config = Main::getInstance()->getConfig();
        return $config->get($value);
    }
}
