<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19/3/2019
 * Time: 4:29 μμ
 */

namespace App;

class Robot
{
    public static $horizonPoints = ['N','E','S','W'];

    private static $actions = ['M','L','R'];

    public $x,$y, $direction;


    public function __construct($x,$y)
    {
        $this->x = $x;
        $this->y = $y;
        $this->direction = self::$horizonPoints[rand(0,3)];
    }

    public function relocate($command){
        self::doCommand($command);
        echo $this->outputPosition() .'<br>';
    }
    private function doCommand($command){
        if($command === 'M'){
            self::moveForward();
            echo 'moved forward'.'<br>';
        }else{
            self::turn($command);
            echo 'turned'. '<br>';
        }
    }

    public function outputPosition(){
        return $this->x." ".$this->y." ".$this->direction;
    }

    private function moveForward(){
        /*$error = $this->validateParameters($this->direction,null);*/
            switch ($this->direction) {
                case 'N':
                    $this->y = $this->y + 1;
                    break;
                case 'S':
                    $this->y =$this->y - 1;

                    break;
                case 'W':
                    $this->x = $this->x - 1;

                    break;
                case 'E':
                    $this->x  = $this->x + 1;
                    break;
            }
    }

    /**
     * @param string $side
     * @return mixed
     */
    private function turn($side = 'L'){
            if($side = 'R'){
                for($i = 0;$i<2;$i++){
                    $change_val = array_shift(self::$horizonPoints);
                    self::$horizonPoints[] = $change_val;
                }
            }
            $this->direction = $this->calculateTurn();
            echo 'NEW DIRECTION'.$this->direction;
    }

    private function calculateTurn(){
        switch ($this->direction) {
            case 'E':
                return 'N';
            case 'S':
                return 'E';
            case 'W':
                return 'S';
            case 'N':
                return 'W';
        }
    }


    public function scan($square, $surface){
        echo 'scanned '.$this->outputPosition();
        return (array_diff($surface,[$square]));
    }

}

