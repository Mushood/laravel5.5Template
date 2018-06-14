<?php

namespace App\Traits;

trait Exportable
{

    /**
     * Export entity to csv file
     *
     * @return mixed
     */
    public function exportToCSVFromModel($entities)
    {
        return Excel::create(
            'entity', function ($excel) use ($entities) {
            $excel->sheet(
                'entity', function ($sheet) use ($entities) {
                $sheet->fromModel($entities);
            }
            );
        }
        )->export('csv');
    }

    /**
     * Export entity to csv file
     *
     * @return mixed
     */
    public function exportToCSVCustom($entities, $filename, $mappings)
    {
        return Excel::create($filename, function($excel) use ($entities, $mappings) {
            $excel->sheet('$filename', function($sheet) use ($entities, $mappings) {
                $headers = array_keys($mappings);

                $sheet->row(1, $headers);
                $rowNo = 2;
                foreach ($entities as $entity){
                    $data = [];
                    foreach ($mappings as $map) {
                        array_push($data, $entity[$map]);
                    }

                    $sheet->row($rowNo, $data);
                    $rowNo++;
                }
            });
    }
}