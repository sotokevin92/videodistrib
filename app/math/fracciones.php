<?php

/**
 * Implementación del algoritmo euclideano.
 * Devuelve el Máximo Común Divisor entre dos números
 */
    function mcd($a,$b) {
        // Pasar a valor absoluto
        $a = abs($a); $b = abs($b);

        // Determinar el mayor e invertir el orden si fuera necesario
        if ($a < $b) {
            list($b, $a) = [$a, $b];
        }

        // Si el menor es 0, devuelvo el otro número... porque no se puede divir por cero, no?
        if ($b == 0) {
            return $a;
        }

        // Calcular el resto de la división
        $resto = $a % $b;

        // Mientras tenga resto, seguir calculando
        while($resto > 0) {
            $a = $b;
            $b = $resto;
            $resto = $a % $b;
        }
        return $b;
    }

    /**
     * Simplificar una fracción dados sus numerador y denominador.
     * Devuelve un array con los dos componentes simplificados.
     */
    function simplificar($num, $denom) {
        $mcd = mcd($num, $denom);

        return [
            $num/$mcd,
            $denom/$mcd
        ];
    }
?>