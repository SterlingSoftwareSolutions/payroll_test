<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Holiday;
use DB;

class HolidayController extends Controller
{
    // holidays
    public function holiday()
    {
        $holiday = Holiday::all();
        $maxId = Holiday::max('id');

        if ($maxId) {
            $user = Holiday::find($maxId);

            if ($user) {
                $userId = $user->holiday_id;
                $nextUserId = 'H' . str_pad((int)substr($userId, 2) + 1, 6, '0', STR_PAD_LEFT);
            } else {
                $nextUserId = 'H000001';
            }
        } else {
            $nextUserId = 'H000001';
        }
        return view('form.holidays', compact('holiday', 'nextUserId'));
    }
    // save record
    public function saveRecord(Request $request)
    {
        $request->validate([
            'holidayId' => 'required|string|max:255',
            'nameHoliday' => 'required|string|max:255',
            'holidayDate' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $holiday = new Holiday;
            $holiday->holiday_id = $request->holidayId;
            $holiday->name_holiday = $request->nameHoliday;
            $holiday->date_holiday  = $request->holidayDate;
            $holiday->save();

            DB::commit();
            Toastr::success('Create new holiday successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Add Holiday fail :)', 'Error');
            return redirect()->back();
        }
    }
    // update
    public function updateRecord(Request $request)
    {
        // dd($request);
        DB::beginTransaction();
        try {

            // $id           = $request->id;
            $holidayId    = $request->holidayId;
            $holidayName  = $request->holidayName;
            $holidayDate  = $request->holidayDate;

            $update = [

                // 'id'           => $id,
                'holiday_id'   => $holidayId,
                'name_holiday' => $holidayName,
                'date_holiday' => $holidayDate,
            ];
            Holiday::where('holiday_id', $holidayId)->update($update);
            DB::commit();
            Toastr::success('Holiday updated successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Holiday update fail :)', 'Error');
            return redirect()->back();
        }
    }
    // delete
    public function deleteRecord(Request $request)
    {
        // Uncomment this line for debugging to see the entire request
        // dd($request);

        DB::beginTransaction();
        try {
            $holiday_id = $request->id;

            // Find the Holiday record with the given 'holiday_id' and delete it
            $holiday = Holiday::where('holiday_id', $holiday_id)->first();

            // Uncomment the following line for debugging to see the found record
            // dd($holiday);

            if ($holiday) {
                $holiday->delete();
            } else {
                Toastr::error('Holiday not found :(', 'Error');
                return redirect()->back();
            }

            DB::commit();

            Toastr::success('Holiday deleted successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Holiday delete failed :(', 'Error');
            return redirect()->back();
        }
    }
}