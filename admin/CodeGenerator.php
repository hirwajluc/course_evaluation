<?php
/**
 * @author Muganga Jean Pierre
 * Do not remove this comment
 */
class CodeGenerator {

    static function generateCode($dept, $year, $sem, $group, $q, $n = 4) {

        $depfilters = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
        //$syfilters = array(1, 2); //Muganga
        //$syfilters = array(1, 2, 3); //Dieudonne
		$syfilters = array(1, 2, 3, 4); //Nepo

        if (!in_array($dept, $depfilters) || !in_array($year, $syfilters) || !in_array($sem, $syfilters) || !in_array($group, $syfilters))
            return "";

        $x = "0123456789";
        $codes = array();
        $n_chars = strlen($x);
        for ($index = 1; sizeof($codes) < $q; $index++) {

            $code = "";
            $i = 0;
            while ($i < $n) {
                $code .= $x[rand(0, strlen($x) - 1)];
                $i++;
            }
            $code = $dept . $year . $sem . $group . 'T' . $code;
            if (!in_array($code, $codes))
                $codes [] = $code;
        }
        return $codes;
    }

}

?>
