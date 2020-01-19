<?php

class Core
{
    public $tiles = [];

    public $total;
    public $turns;
    public $score;
    public $fours;
    public $sumTiles;
    
    private $_tileValues = [
        0 => 0,
        2 => 0,
        4 => 4
    ];
    
    public function __construct() {
        for ($i = 0; $i < 4; $i++)
        {
            $inputs = explode(" ", fgets(STDIN));
            for ($j = 0; $j < 4; $j++)
            {
                $tile = $this->_validate($inputs[$j] ?? 0);
                
                $this->tiles[$i][$j] = $tile;
                $this->sumTiles += $tile;
            }
        }

        $this->fours = intval(fgets(STDIN));
    }
    
    public function output() {
        echo $this->total;
        echo PHP_EOL;
        echo $this->turns;
    }
    
    public function calculateTotal() {
        $total = 0;
        
        foreach ($this->tiles as $line) {
            foreach ($line as $tile) {
                $total += $this->_calcTilePoints($tile);
            }
        }
        
        // already generated fours mean you don`t achieve anything
        $this->total = $total - $this->_tileValues[4] * $this->fours;
        
        return $this;
    }

    /**
     * Calculate users moves
     * 
     * @return int count of moves in game
     */
    public function calculateTurns() {
        
        $this->turns = $this->sumTiles / 2 // turns if only dueces are in game
            - $this->fours // minus fours
            - 2; // minus two tile from start of game
            
        return $this;
    }
    
    /**
     * Calculate points achieved to create requested tile
     * means tile`s value plus values of all previous tiles
     * 
     * @param int $tile
     * 
     * @response int achieved points
     * 
     */
    private function _calcTilePoints(int $tile) : int {
        $points = 0;

        if ( ! isset($this->_tileValues[$tile])) {
         
            $points = $tile + 2 * $this->_calcTilePoints($tile / 2);
            
            $this->_tileValues[$tile] = $points;
        } else {
            $points = $this->_tileValues[$tile];
        }
        
        return $points;
    }
    
    private function _validate($tile) {
        $result = 0;
    
        $i = 2;
        while ($i <= 2**31) {
            if ($i > $tile) {
                break;
            } elseif ($i == $tile) {
                $result = $tile;
                break;
            }
            $i *= 2;
        }
        
        return intval($result);
    }
}

$core= new Core;

$core->calculateTotal()
    ->calculateTurns()
    ->output();