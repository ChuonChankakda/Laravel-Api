<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter { 
    protected $safeParms = []; 

    protected $columnMap = []; 

    protected $operatorMap = []; 

    public function transform(Request $request){
        $eloQuery = []; 

        foreach($this->safeParms as $parms => $operators){
            $query = $request-> query($parms);

            if(!isset($query)){
                continue;
            }
            
            $column = $this->columnMap[$parms] ?? $parms;
            
            foreach ($operators as $operator){
                if(isset($query[$operator])){
                    $eloQuery[] = [$column, $this->operatorMap[$operator],$query[$operator]];
                }
            }

        }
        return $eloQuery;
    }
}
