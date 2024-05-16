<?php
/**
 * @author Muganga Jean Pierre
 * Do not remove this comment
 */
class CodeGenerator {

    static function generateCode($dept, $year, $sem, $group, $q=50, $n = 12) {

        $depfilters = array(1, 2, 3);
        $syfilters = array(1, 2); //Muganga
        $syfilters = array(1, 2, 3, 4); //Dieudonne

        if (!in_array($dept, $depfilters) || !in_array($year, $syfilters) || !in_array($sem, $syfilters) || !in_array($group, $syfilters))
            return "";

        $x = "0123456789";
        $m = strlen($x) - 1;
        $codes = array();
        $n_chars = strlen($x);
        for ($index = 1; sizeof($codes) < $q; $index++) {

            $code = "";
            $i = 0;
            while ($i < $n) {
                $rand = microtime () + rand(0, $m);
                $code .= substr($x, $rand, 1);
                $i++;
            }
            $code = $dept . $year . $sem . $group . $code;
            if (!in_array($code, $codes))
                $codes [] = $code;
        }
        return $codes;
    }

}

?>
