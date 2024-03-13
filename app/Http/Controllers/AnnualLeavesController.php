<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AnnualLeaves;
use Illuminate\Http\Request;

class AnnualLeavesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function calculateAnnualLeave($joinedDate)
    {
        $joinedDate = Carbon::parse($joinedDate);

        $januaryFirst = Carbon::parse('January 1');
        $aprilFirst = Carbon::parse('April 1');
        $julyFirst = Carbon::parse('July 1');
        $octoberFirst = Carbon::parse('October 1');

        if ($joinedDate->gte($januaryFirst) && $joinedDate->lt($aprilFirst)) {
            $annualLeave = 14;
        } elseif ($joinedDate->gte($aprilFirst) && $joinedDate->lt($julyFirst)) {
            $annualLeave = 10;
        } elseif ($joinedDate->gte($julyFirst) && $joinedDate->lt($octoberFirst)) {
            $annualLeave = 7;
        } elseif ($joinedDate->gte($octoberFirst) && $joinedDate->lte(Carbon::parse('December 31'))) {
            $annualLeave = 4;
        } else {
            $annualLeave = 0;
        }

        $maxLeave = 21;
        $annualLeave = min($annualLeave, $maxLeave);

        $joinedDate = '2023-03-15'; // Replace this with the actual hire date
        $annualLeave = $this->calculateAnnualLeave($joinedDate);


        return $annualLeave;
    }
}
