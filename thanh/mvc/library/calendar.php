<?php
class calendar{
    public $year;
    public function showOption($start, $end, $default, $step = 1){
    $op=array();
        for ($i = 0; $i <= $end-$start; $i += $step){
                $temp=array();
                $temp['at']=$i+$start;
                if($temp['at']!=$default){
                    $temp['selected']='';
                }else{
                    $temp['selected']='selected';
                }
                array_push($op,$temp);
            }
        return $op;
        }
    }


