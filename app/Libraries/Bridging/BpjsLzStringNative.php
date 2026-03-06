<?php

/**
 * Standard LZString Decompressor for BPJS V-Claim
 * This is a robust, minimal port specifically designed to decompress 
 * strings returned by BPJS Kesehatan V-Claim API without using external Composer packages.
 */
class BpjsLzStringNative
{
    private static $keyStrUriSafe = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+-$";
    private static $baseReverseDic = [];

    private static function getBaseValue($character)
    {
        if (empty(self::$baseReverseDic)) {
            $len = strlen(self::$keyStrUriSafe);
            for ($i = 0; $i < $len; $i++) {
                self::$baseReverseDic[self::$keyStrUriSafe[$i]] = $i;
            }
        }
        return isset(self::$baseReverseDic[$character]) ? self::$baseReverseDic[$character] : null;
    }

    public static function decompressFromEncodedURIComponent($input)
    {
        if ($input === null) return "";
        if ($input === "") return null;
        $input = str_replace(' ', '+', $input);
        return self::_decompress(strlen($input), 32, function ($index) use ($input) {
            return self::getBaseValue($input[$index]);
        });
    }

    public static function decompress($input)
    {
        if ($input === null) return "";
        if ($input === "") return null;
        return self::_decompress(mb_strlen($input, 'UTF-8'), 16384, function ($index) use ($input) {
            return ord(mb_substr($input, $index, 1, 'UTF-8'));
        });
    }

    private static function _decompress($length, $resetValue, $getNextValue)
    {
        $dictionary = [];
        $next = 0;
        $enlargeIn = 4;
        $dictSize = 4;
        $numBits = 3;
        $entry = "";
        $result = [];
        $i = 0;
        $w = "";
        $bits = 0;
        $resb = 0;
        $maxpower = 0;
        $power = 1;
        $c = "";
        
        $data = [
            'val'      => $getNextValue(0),
            'position' => $resetValue,
            'index'    => 1
        ];

        for ($i = 0; $i < 3; $i += 1) {
            $dictionary[$i] = $i;
        }

        $bits = 0;
        $maxpower = pow(2, 2);
        $power = 1;
        while ($power != $maxpower) {
            $resb = $data['val'] & $data['position'];
            $data['position'] >>= 1;
            if ($data['position'] == 0) {
                $data['position'] = $resetValue;
                $data['val'] = $getNextValue($data['index']++);
            }
            $bits |= ($resb > 0 ? 1 : 0) * $power;
            $power <<= 1;
        }

        switch ($next = $bits) {
            case 0:
                $bits = 0;
                $maxpower = pow(2, 8);
                $power = 1;
                while ($power != $maxpower) {
                    $resb = $data['val'] & $data['position'];
                    $data['position'] >>= 1;
                    if ($data['position'] == 0) {
                        $data['position'] = $resetValue;
                        $data['val'] = $getNextValue($data['index']++);
                    }
                    $bits |= ($resb > 0 ? 1 : 0) * $power;
                    $power <<= 1;
                }
                $c = mb_chr($bits, 'UTF-8');
                break;
            case 1:
                $bits = 0;
                $maxpower = pow(2, 16);
                $power = 1;
                while ($power != $maxpower) {
                    $resb = $data['val'] & $data['position'];
                    $data['position'] >>= 1;
                    if ($data['position'] == 0) {
                        $data['position'] = $resetValue;
                        $data['val'] = $getNextValue($data['index']++);
                    }
                    $bits |= ($resb > 0 ? 1 : 0) * $power;
                    $power <<= 1;
                }
                $c = mb_chr($bits, 'UTF-8');
                break;
            case 2:
                return "";
        }
        
        $dictionary[3] = $c;
        $w = $c;
        array_push($result, $c);

        while (true) {
            if ($data['index'] > $length) {
                return "";
            }

            $bits = 0;
            $maxpower = pow(2, $numBits);
            $power = 1;
            while ($power != $maxpower) {
                $resb = $data['val'] & $data['position'];
                $data['position'] >>= 1;
                if ($data['position'] == 0) {
                    $data['position'] = $resetValue;
                    $data['val'] = $getNextValue($data['index']++);
                }
                $bits |= ($resb > 0 ? 1 : 0) * $power;
                $power <<= 1;
            }

            switch ($c = $bits) {
                case 0:
                    $bits = 0;
                    $maxpower = pow(2, 8);
                    $power = 1;
                    while ($power != $maxpower) {
                        $resb = $data['val'] & $data['position'];
                        $data['position'] >>= 1;
                        if ($data['position'] == 0) {
                            $data['position'] = $resetValue;
                            $data['val'] = $getNextValue($data['index']++);
                        }
                        $bits |= ($resb > 0 ? 1 : 0) * $power;
                        $power <<= 1;
                    }

                    $dictionary[$dictSize++] = mb_chr($bits, 'UTF-8');
                    $c = $dictSize - 1;
                    $enlargeIn--;
                    break;
                case 1:
                    $bits = 0;
                    $maxpower = pow(2, 16);
                    $power = 1;
                    while ($power != $maxpower) {
                        $resb = $data['val'] & $data['position'];
                        $data['position'] >>= 1;
                        if ($data['position'] == 0) {
                            $data['position'] = $resetValue;
                            $data['val'] = $getNextValue($data['index']++);
                        }
                        $bits |= ($resb > 0 ? 1 : 0) * $power;
                        $power <<= 1;
                    }
                    $dictionary[$dictSize++] = mb_chr($bits, 'UTF-8');
                    $c = $dictSize - 1;
                    $enlargeIn--;
                    break;
                case 2:
                    return implode('', $result);
            }

            if ($enlargeIn == 0) {
                $enlargeIn = pow(2, $numBits);
                $numBits++;
            }

            if (isset($dictionary[$c])) {
                $entry = $dictionary[$c];
            } else {
                if ($c === $dictSize) {
                    $entry = $w . mb_substr($w, 0, 1, 'UTF-8');
                } else {
                    return null;
                }
            }
            
            array_push($result, $entry);

            // Add w+entry[0] to the dictionary.
            $dictionary[$dictSize++] = $w . mb_substr($entry, 0, 1, 'UTF-8');
            $enlargeIn--;

            $w = $entry;

            if ($enlargeIn == 0) {
                $enlargeIn = pow(2, $numBits);
                $numBits++;
            }
        }
    }
}
