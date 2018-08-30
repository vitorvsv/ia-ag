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
    const GENERATIONS_LENGHT = 50;

    public function __construct()
    {
        $this->generations();
    }

    public function generations()
    {
        //Guarda as gerações
        $generations[]   = $this->firstPopulation();

        //Guarda as gerações em decimal
        $generationDec   = [];

        //Guarda os fitness
        $arrFitnessTotal = [];

        //Guarda as taxas de fitenss
        $taxFitnessTotal = [];

        $totalFitness  = 0;
        $arrFitness    = [];
        $newGeneration = [];

        $cont = 0;

        //Gera as outras gerações
        for ($g = 0; $g < self::GENERATIONS_LENGHT - 1; $g++) {
            $totalFitness  = 0;
            $arrFitness    = [];
            $newGeneration = [];

            for ($c = 0; $c < self::POPULATION_LENGHT; $c++) {
                $fitness = $this->fitness($generations[$g][$c]);
                $arrFitness[] = $fitness;
                $totalFitness += $fitness;
            }

            //Guarda em um array com os totais dos fitness
            $arrFitnessTotal[] = $arrFitness;

            $taxFitness = $this->calcTaxFitness($arrFitness, $totalFitness);

            //Guarda em um array com os totais das faixas de fitness
            $taxFitnessTotal[] = $taxFitness;

            for ($c = 0; $c < self::POPULATION_LENGHT / 2; $c++) {
                $selectedChromossome      = $this->selectChromossome($taxFitness);
                $selectedOtherChromossome = $this->selectChromossome($taxFitness);

                $changedGenetic = $this->changeGenetic(
                    $generations[0][$selectedChromossome],
                    $generations[0][$selectedOtherChromossome]
                );

                $newGeneration[] = $changedGenetic[0];
                $newGeneration[] = $changedGenetic[1];
            }

            $generations[] = $newGeneration;
        }

        //Gera as populações em decimais
        foreach ($generations as $g => $generation) {
            $population = [];

            foreach ($generation as $chromossome) {
                $population[] = $this->bin4Dec($chromossome);
            }

            $generationDec[] = $population;
        }

        echo "<table border='1' style='text-align: center;'>";
        echo "<tr>";
            echo "<th>Geração</th>";
            echo "<th>Valor x</th>";
            echo "<th>Valor em decimal</th>";
            echo "<th>Valor em sin(x)</th>";
            echo "<th>Fitness</th>";
            echo "<th>Taxa de Fitness</th>";
        echo "</tr>";

        for ($i = 0; $i < self::GENERATIONS_LENGHT - 1; $i++) {

            $mediaFitness = 0;

            for ($j = 0; $j < 4; $j++) {
                echo "<tr>";
                    echo "<td>{$i}</td>";
                    echo "<td>{$generations[$i][$j]}</td>";
                    echo "<td>{$generationDec[$i][$j]}</td>";
                    echo "<td>" . sin($generationDec[$i][$j]) . "</td>";
                    echo "<td>{$arrFitnessTotal[$i][$j]}</td>";
                    echo "<td>{$taxFitnessTotal[$i][$j]}</td>";
                echo "</tr>";

                $mediaFitness += $arrFitnessTotal[$i][$j];
            }

            $mediaFitness = round($mediaFitness / 4, 2);

            echo "<tr><td colspan='6'>Média Fitness: {$mediaFitness}</td><tr>";

            echo "<tr><td colspan='6'>&nbsp;</td><tr>";
        }
        echo "</table>";
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