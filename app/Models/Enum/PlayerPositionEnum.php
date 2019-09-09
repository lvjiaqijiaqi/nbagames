<?php

namespace App\Models\Enum;

class PlayerPositionEnum
{
    // 状态类别
    const PLAYER_POSITION_C = 1<<0;
    const PLAYER_POSITION_PF = 1<<1;
    const PLAYER_POSITION_SF = 1<<2;
    const PLAYER_POSITION_SG = 1<<3;
    const PLAYER_POSITION_PG = 1<<4;

    public static function getPlayerPosition($position){
        $positionType = 0;
        if(strpos($position,'中锋') !== false){ 
            $positionType = $positionType | self::PLAYER_POSITION_C;
            if(strpos($position,'前锋') !== false){ 
                $positionType = $positionType | self::PLAYER_POSITION_PF;
            }
        }else if(strpos($position,'后卫') !== false){ 
            $positionType = $positionType | self::PLAYER_POSITION_SG;
            if(strpos($position,'前锋') !== false){ 
                $positionType = $positionType | self::PLAYER_POSITION_SF;
            }else{
                $positionType = $positionType | self::PLAYER_POSITION_PG;
            }
        }else if(strpos($position,'前锋') !== false){ 
            $positionType = $positionType | self::PLAYER_POSITION_PF;
            $positionType = $positionType | self::PLAYER_POSITION_SF;
        }
        return $positionType;
    } 
}