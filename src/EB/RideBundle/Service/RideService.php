<?php

namespace EB\RideBundle\Service;

class RideService
{
    public function computeAverage(array $terms = array())
    {
        $sum = 0;
        foreach ($terms as $term) {
            $sum += $term;
        }
        $average = number_format(floatval($sum / count($terms)), 2);

        return $average;
    }
}
