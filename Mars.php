<?php
namespace Mars;


use Robot;

class Mars
{

    const COMPOUND_SIZE = 5;
    private $surface;
    public function factory($num_robots){
        //make robots
        for($i=0;$i<$num_robots;$i++){
            $xPos = rand(0,(self::COMPOUND_SIZE) -1);
            $yPos = rand(0,(self::COMPOUND_SIZE -1));
            ${'robot'.$i} = new Robot($xPos,$yPos);
            yield ${'robot'.$i};
        }
    }

    public function findShortestBound(Robot $robot){
        //finds shortest Direction to face
        $sortBound[]=abs(($robot->x - 0)).'W';
        $sortBound[]=abs(($robot->x - self::COMPOUND_SIZE)).'E';
        $sortBound[] =abs(($robot->y - 0)).'S';
        $sortBound[] =abs(($robot->y - self::COMPOUND_SIZE)).'N';
        sort($sortBound);
        return substr($sortBound[0],1);
    }

    private function getSurface(){
        //collect all tiles to scan
        $surface =[];
        $surfaceReverse =[];
        for($i=0;$i<self::COMPOUND_SIZE;$i++){
            for($j=0;$j<self::COMPOUND_SIZE;$j++){
                if($i ==0 || $j== 0 ||
                    $i == (self::COMPOUND_SIZE-1) ||
                    $j == (self::COMPOUND_SIZE -1 ))
                    $surface[] = "$i $j";
            }
        }
        return $this->surface = $surface;
    }

    public function inBounds($robot){
        //checks if robot is within allowed space to move
        if(($robot->x >0 && $robot->x<(self::COMPOUND_SIZE -1 )
            && $robot->y >0 && $robot->y<(self::COMPOUND_SIZE -1 ))){
            print 'moving...';
            $robot->relocate('M');
        }

    }

    public function checkForScan($robot,$surface,$direction){
        if(in_array(substr($robot->outputPosition(),0,-2),$surface)){
            if(substr($robot->outputPosition(),-1,1) === $direction){
                //if robot is on valid tile facing outward do Scan
                $square = substr($robot->outputPosition(),0,-2);
                $surface = $robot->scan($square,$surface);
                echo 'surface remaining'.(count($surface)).'<br>';
            }
        }
        return $surface;
    }

    public function startProcess(){
        //create robots for process
        $result = iterator_to_array($this->factory(2));
        //calculate the surface that the robots are positioned
        $surface = $this->getSurface();
        $robot = $result[0];
        $loops =0;


        while(count($surface)>0 && $loops<200){

            $old_surface = $surface;
            //finds shortest Distance for robot to face
            $new_goal = $this->findShortestBound($robot,$surface);
            ECHO 'NEW GOAL'.$new_goal.'<br>';
            if($robot->direction != $new_goal){
                print 'turning...';
                $robot->relocate('L');
                $surface = $this->checkForScan($robot,$surface,$new_goal);
            }
            else{
                print 'same direction';
                $this->inBounds($robot);
                $surface = $this->checkForScan($robot,$surface,$new_goal);
            }

            if(count($old_surface) > count($surface)){
                //TODO goToNextSquare

                //repeat process
                $robot->relocate('L');
                $this->inBounds($robot);
                $surface = $this->checkForScan($robot,$surface,$new_goal);
            }

            //stop after 200 loops, for development reasons
            $loops++;
        }
    }
}