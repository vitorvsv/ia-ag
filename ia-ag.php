<?php
/**
 *  Algortimo Genético para encontrar o ponto de máximo para a função f(x) = sen(x)
 *  f(x) resulta números entre 1 e -1 sendo 1 a solução perfeita e -1 a pior solução
 *
 *  Baseado em: https://integrada.minhabiblioteca.com.br/#/books/978-85-216-2936-8/cfi/6/56!/4/182/4@0:0
 *
 */

$pop = new Generations();

class Generations
{
    const POPULATION_LENGHT = 4;
    const GENERATIONS_LENGHT = 5;

    public function __construct()
    {
        $this->generations();
    }

    public function generations()
    {
//        $generations   = [];
//        $generations[][] = $this->firstPopulation();
        $generations[] = [
            0 => [
                '1001', // 9
                '0011',
                '1010',
                '0101',
            ],
//            0 => [
//                '0101',
//                '1010',
//                '1111',
//                '0000',
//            ],
        ];

        $totalFitness = 0;
        $arrFitness = [];
        $newGeneration = [];

        //segunda geração

        //Chromossome
        for ($c = 0; $c < self::POPULATION_LENGHT; $c++) {
            $fitness = $this->fitness($generations[0][0][$c]);
            $arrFitness[] = $fitness;
            $totalFitness += $fitness;
        }

        $taxFitness = $this->calcTaxFitness($arrFitness, $totalFitness);

        for ($c = 0; $c < self::POPULATION_LENGHT / 2; $c++) {
            $selectedChromossome      = $this->selectChromossome($taxFitness);
            $selectedOtherChromossome = $this->selectChromossome($taxFitness);

            $changedGenetic = $this->changeGenetic(
                $generations[0][0][$selectedChromossome],
                $generations[0][0][$selectedOtherChromossome]
            );

            $newGeneration[] = $changedGenetic[0];
            $newGeneration[] = $changedGenetic[1];
        }

        $generations[] = $newGeneration;

        //Fim da segunda geração

        //Gera as outras gerações

        for ($g = 1; $g < self::GENERATIONS_LENGHT - 2; $g++) {
            $totalFitness  = 0;
            $arrFitness    = [];
            $newGeneration = [];

            for ($c = 0; $c < self::POPULATION_LENGHT; $c++) {
                $fitness = $this->fitness($generations[$g][0][$c]);
                $arrFitness[] = $fitness;
                $totalFitness += $fitness;
            }

            $taxFitness = $this->calcTaxFitness($arrFitness, $totalFitness);

            for ($c = 0; $c < self::POPULATION_LENGHT / 2; $c++) {
                $selectedChromossome      = $this->selectChromossome($taxFitness);
                $selectedOtherChromossome = $this->selectChromossome($taxFitness);

                $changedGenetic = $this->changeGenetic(
                    $generations[0][0][$selectedChromossome],
                    $generations[0][0][$selectedOtherChromossome]
                );

                $newGeneration[] = $changedGenetic[0];
                $newGeneration[] = $changedGenetic[1];
            }

            $generations[] = $newGeneration;
        }

        var_dump("<pre>",$generations,"</pre>"); die;
    }

    // Generates the first generation with random numbers
    public function firstPopulation()
    {
        $firstGeneration = [];

        for ($i = 0; $i < self::POPULATION_LENGHT; $i++) {
            $firstGeneration[] = $this->generateChromosome();
        }

        return $firstGeneration;
    }

    //Generates Chromosome (binaries between 0 - 15)
    public function generateChromosome()
    {
        $rest      = rand(0, 15);
        $mod       = $rest % 2;
        $binNumber = [];

        while ($rest != 0) {
            $binNumber[] = $mod;
            $rest = floor($rest / 2);
            $mod = $rest % 2;
        }

        $binNumber = implode('', array_reverse($binNumber));
        return str_pad($binNumber, 4 , 0, 0);
    }

    //Converts binaries numbers to decimal numbers
    public function bin4Dec($bin)
    {
        $aux = 0;
        $dec = 0;

        for ($i = strlen($bin) - 1; $i >= 0; $i--) {
            if ($bin[$i] == 1) {
                $dec += 2**$aux;
            }
            $aux++;
        }

        return $dec;
    }

    //Function to avaliate the fitness of the chromossome
    public function fitness($x)
    {
        $fit = 50 * (sin($this->bin4Dec($x)) + 1);
        return round($fit, 2);
    }

    //Calculate the band on each fitness is
    public function calcTaxFitness($fitnessPerPopulation, $totalFitness)
    {
        $taxFitness = [];

        foreach ($fitnessPerPopulation as $fit) {
            $taxFitness[]  = round(($fit / $totalFitness) * 100, 2);
        }

        return $taxFitness;
    }

    //Select an chromossome to change their genetic with other chromossome
    public function selectChromossome($totalFitness)
    {
        $numberRandom = rand(0, 100);
        $sum = 0;

        for ($i = 0; $i < count($totalFitness); $i++) {
            if ($sum >= $numberRandom) {
                return $i;
            }

            $sum += $totalFitness[$i];
        }

        return 0;
    }

    //Modified the genetic to formate new chromossomes
    public function changeGenetic($selectedChromossome, $selectedOtherChromossome)
    {
        $pointer = round(strlen($selectedChromossome) / 2, 2);

        $geneticOnePartOne = substr($selectedChromossome, 0, $pointer);
        $geneticOnePartTwo = substr($selectedChromossome, $pointer, strlen($selectedChromossome));
        $geneticTwoPartOne = substr($selectedOtherChromossome, 0, $pointer);
        $geneticTwoPartTwo = substr($selectedOtherChromossome, $pointer, strlen($selectedOtherChromossome));

        $geneticModified = [
            $geneticOnePartOne.$geneticTwoPartTwo,
            $geneticTwoPartOne.$geneticOnePartTwo
        ];

        return $geneticModified;
    }
}