<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WsrChangeReq;
use App\UserShiftPattern;
use App\ShiftPattern;
use App\DayType;
use \Carbon\Carbon;
use App\Shared\UserHelper;

class WorkSchedRuleController extends Controller
{
  public function wsrPage(Request $req){
    if($req->filled('page')){
      if($req->page == 'myc'){
        return $this->myCalendar($req);
      } elseif ($req->page == 'teamc') {
        return $this->teamCalendar($req);
      } elseif ($req->page == 'reqs') {
        return $this->listChangeWsr($req);
      } else {
        return $this->wsrMainPage($req);
      }
    } else {
      return $this->wsrMainPage($req);
    }
  }

  private function wsrMainPage(Request $req){
    $cbdate = new Carbon;
    $currwsr = UserHelper::GetWorkSchedRule($req->user()->id, $cbdate);

    $currwsr = WsrChangeReq::where('user_id', $req->user()->id)
      ->where('status', 'Approved')
      ->whereDate('end_date', '>=', $cbdate)
      ->orderBy('start_date', 'desc')
      ->first();

    if($currwsr){

    } else {
      // no approved change req for that date
      // find the data from SAP
      $currwsr = UserShiftPattern::where('user_id', $req->user()->id)
        ->whereDate('start_date', '<=', $cbdate)
        ->whereDate('end_date', '>=', $cbdate)
        ->orderBy('start_date', 'desc')
        ->first();

        if($currwsr){

        } else {
          // also not found. just return OFF1 as default
          $sptr = ShiftPattern::where('code', 'OFF1')->first();

          $sdate = new Carbon;
          $edate = Carbon::maxValue();
          $cspid = $sptr->id;
        }
    }

    if($currwsr){
      $sdate = new Carbon($currwsr->start_date);
      $edate = new Carbon($currwsr->end_date);
      $cspid = $currwsr->shift_pattern_id;
    }

    $planlist = ShiftPattern::where('is_weekly', true)->get();


    return view('staff.workschedulemain', [
      'cspid' => $cspid,
      'sdate' => $sdate,
      'edate' => $edate,
      'planlist' => $planlist
    ]);
  }

  public function doEditWsr(Request $req){
    // check for overlapping dates
    $overlap = WsrChangeReq::where('user_id', $req->user()->id)
      ->whereDate('start_date', '<', $req->end_date)
      ->whereDate('end_date', '>', $req->start_date)
      ->first();

    if($overlap){
      return redirect(route('staff.worksched', [], false))->with([
        'feedback' => true,
        'feedback_title' => 'Error',
        'feedback_text' => 'Date range overlapped with existing request.'
      ]);
    }

    // create the request entry
    $wsreq = new WsrChangeReq;
    $wsreq->user_id = $req->user()->id;
    $wsreq->shift_pattern_id = $req->spid;
    $wsreq->start_date = $req->start_date;
    $wsreq->end_date = $req->end_date;
    $wsreq->superior_id = $req->user()->reptto;
    $wsreq->status = 'New';
    $wsreq->save();

    return redirect(route('staff.worksched', [], false))->with([
      'feedback' => true,
      'feedback_title' => 'Successfully Submit',
      'feedback_text' => 'You have successfully submit the request to your approver. Your new work schedule will take effect one approved.'
    ]);
  }

  public function myCalendar(Request $req){

  }

  public function teamCalendar(Request $req){

  }

  public function listChangeWsr(Request $req){
    $pendingapp = WsrChangeReq::where('superior_id', $req->user()->id)
      ->where('status', 'Pending Approval')->get();
    $myown = WsrChangeReq::withTrashed()->where('user_id', $req->user()->id)->get();

    return view('staff.workschedulereqs', [
      'requests' => $pendingapp,
      'mine' => $myown
    ]);

  }

  public function doApproveWsr(Request $req){

  }

  public function doRejectWsr(Request $req){

  }

  public function ApiGetWsrDays(Request $req){
    $datsp = ShiftPattern::find($req->id);
    $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
    if($datsp){
      $retv = [];
      foreach($datsp->ListDays as $oneday){
        $dayindex = $oneday->day_seq % 7;
        if($oneday->Day->is_work_day == true){
          $stime = new Carbon($oneday->Day->start_time);
          $etime = new Carbon($oneday->Day->start_time);
          $etime->addMinutes($oneday->Day->total_minute);
          array_push($retv, [
            'day' => $dowMap[$dayindex],
            'time' => $stime->format('H:i') . ' - ' . $etime->format('H:i')
          ]);
        } else {
          array_push($retv, [
            'day' => $dowMap[$oneday->day_seq],
            'time' => $oneday->Day->description
          ]);
        }

      }
      return $retv;
    } else {
      return [];
    }
  }
}
