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
    const GENERATIONS_LENGHT = 3;

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
                '1001',
                '0011',
                '1010',
                '0101',
            ],
        ];

        //Generations
        for ($g = 0; $g < self::GENERATIONS_LENGHT + 1; $g++) {

            $newGeneration = [];

            //Populations
            for ($p = 0; $p < self::POPULATION_LENGHT; $p++) {
                $fitness = [];
                $totalFitness = 0;

                //Chromossome
                for ($c = 0; $c < self::POPULATION_LENGHT; $c++) {
                    $fitnessUnique = $this->fitness($generations[$g][$p][$c]);
                    $fitness[]     = $fitnessUnique;
                    $totalFitness  += $fitnessUnique;
                }

                $taxFitness = $this->calcTaxFitness($fitness, $totalFitness);

                for ($i = 0; $i < (self::POPULATION_LENGHT / 2); $i++) {
                    //Selected chromossomes to change genetic
                    $selectedChromossome      = $this->selectChromossome($taxFitness);
                    $selectedOtherChromossome = $this->selectChromossome($taxFitness);

                    $changed = $this->changeGenetic(
                        $generations[$g][$p][$selectedChromossome],
                        $generations[$g][$p][$selectedOtherChromossome]
                    );

                    $newGeneration[] = $changed[0];
                    $newGeneration[] = $changed[1];
                }
            }

            //Esta gerando uma população de 16!
            
            var_dump("<pre>",$newGeneration,"</pre>"); die;

            $generations[] = $newGeneration;
        }

        var_dump("<pre>",$generations,"</pre>"); die;
    }

    public function population($populations)
    {
        $fitness       = [];
        $totalFitness  = 0;
        $taxFitnes     = [];

        //Populations
        for ($j = 0; $j < self::POPULATION_LENGHT; $j++) {
             //Chromossome / individual
            for ($k = 0; $k < self::POPULATION_LENGHT; $k++) {
                //Fitness of chromossome
//                $fitnessUnique        = $this->fitness($generations[$i][$j]);
//                $fitness[$i][$j][$k]  = $fitnessUnique;
//                $totalFitness        += $fitnessUnique;

            }
            $taxFitnes[] = $this->calcTaxFitness($fitness[$i], $totalFitness);
            $totalFitness = 0;
        }

//        var_dump("<pre>",$generations[0], $fitness[0], $taxFitnes[0],"</pre>"); die;
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
    public function calcTaxFitness($fitness, $totalFitness)
    {
        $taxFitness = [];

        foreach ($fitness as $fit) {
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