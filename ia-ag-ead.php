<?php

$obj = new Algorithm();

class Algorithm
{
  public function __construct()
  {
    $this->run();
  }   
  
  private $courses = [
    0 => 'Institucional',
    1 => 'Análise e desenvolvimento de sistemas',
    2 => 'Contabilidade',
  ];

  private $subjects = [
    0 => [
      'name' => 'Leitura e produção de textos',
      'depencies' => [],
      'students' => 86,
      'course' => 0,
    ],
    1 => [
      'name' => 'Introdução a computação',
      'depencies' => [0],
      'students' => 22,
      'course' => 1,
    ],
    2 => [
      'name' => 'Matemática e estatística aplicada',
      'depencies' => [0],
      'students' => 77,
      'course' => 0,
    ],
    3 => [
      'name' => 'Análise e modelagem de dados',
      'depencies' => [2],
      'students' => 12,
      'course' => 1,
    ],
    4 => [
      'name' => 'Sistemas operacionais',
      'depencies' => [3],
      'students' => 14,
      'course' => 1,
    ],
    5 => [
      'name' => 'Modelos de gestão',
      'depencies' => [0],
      'students' => 65,
      'course' => 2,
    ],
    6 => [
      'name' => 'Contabilidade para não iniciantes',
      'depencies' => [0],
      'students' => 45,
      'course' => 2,
    ],
    7 => [
      'name' => 'Cálculo de finanças',
      'depencies' => [6],
      'students' => 33,
      'course' => 2,
    ],
    8 => [
      'name' => 'Economia',
      'depencies' => [7],
      'students' => 23,
      'course' => 0,
    ],
  ];

  private $classrooms = [
    0 => [
        'number' => '101',
        'capability' => 30,
        'building' => 3,
    ],  
    1 => [
        'number' => '102',
        'capability' => 30,
        'building' => 3,
    ],
    2 => [
        'number' => '103',
        'capability' => 30,
        'building' => 3,
    ], 
    3 => [
        'number' => '104',
        'capability' => 30,
        'building' => 3,
    ],
    4 => [
        'number' => '105',
        'capability' => 30,
        'building' => 3,
    ],
    5 => [
        'number' => '101',
        'capability' => 30,
        'building' => 2,
    ],
    6 => [
        'number' => '102',
        'capability' => 30,
        'building' => 2,
    ],
    7 => [
        'number' => '103',
        'capability' => 30,
        'building' => 2,
    ],
    8 => [
        'number' => '104',
        'capability' => 30,
        'building' => 2,
    ],
    9 => [
        'number' => '105',
        'capability' => 30,
        'building' => 2,
    ],
  ];
  
  private $hours = [
    0 => '08:00',
    1 => '10:00',
    2 => '13:00',
    3 => '15:00',
  ];

  
  // disciplina - sala de aula - hora
  private $generations = [
    // 0 => [ 
    //   [0, 0, 0],
    //   [0, 1, 0],
    //   [0, 2, 0],
    // ]
  ];  
  
  private $COMBINATIONS = 200;
  private $MAXINDIVIDUALS = 300;
  private $MAXGENERATIONS = 100;
    
  public function run()
  {
    $this->generations[] = $this->generateIndividual();
    print_r($this->generations);
    
  }

  public function fitness()
  {
    
  }
  
  public function generatePopulation(){
    $generation = [];
    for($i = 0; $i < $this->MAXINDIVIDUALS; $i++){
      $individual = [];
      for($j = 0; $j < $this->COMBINATIONS; $j++){
        $individual[] = [
          $this->random(0, count($this->subjects) - 1),
          $this->random(0, count($this->classrooms) - 1),
          $this->random(0, count($this->hours) - 1),
        ];
      }
      $generation[] = $individual;
    }
    $this->generations[] = $generation;
    
  }
  
  private function random($min, $max){
    return (int) rand($min, $max);
  }
  
}

?>

