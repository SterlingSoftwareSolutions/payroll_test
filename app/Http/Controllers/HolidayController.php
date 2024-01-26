<?php
namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Brian2694\Toastr\Facades\Toastr;

class HolidayController extends Controller
{
    public function holiday()
    {
        $holidays = Holiday::all();
       // dd($holidays);
        $next_id = IdGenerator::generate(['table' => 'holidays', 'length' => 5, 'prefix' => 'H']);

        return view('form.holidays', compact('holidays', 'next_id'));
    }


    // save record
    public function saveRecord(Request $request)
    {
        $request->validate([
            'holidayId'=> 'required|string|max:255',
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
            Toastr::success('Create new holiday successfully :)','Success');
            return redirect()->back();
            
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Holiday fail :)','Error');
            return redirect()->back();
        }
    }
    // update
    public function updateRecord( Request $request)
    {
        DB::beginTransaction();
        try{
            $id           = $request->id;
            $holidayId    = $request->holidayId;
            $holidayName  = $request->holidayName;
            $holidayDate  = $request->holidayDate;

            $update = [

                'id'           => $id,
                'holiday_id'   => $holidayId,
                'name_holiday' => $holidayName,
                'date_holiday' => $holidayDate,
            ];

            Holiday::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Holiday updated successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Holiday update fail :)','Error');
            return redirect()->back();
        }
    }
}
