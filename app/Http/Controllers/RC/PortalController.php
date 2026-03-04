<?php

namespace App\Http\Controllers\RC;

use App\Http\Controllers\Controller;
use App\Models\Atp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortalController extends Controller
{
    /**
     * Guard — ATP must be logged in via session.
     */
    private function getAtp(): ?Atp
    {
        $atpId = session('atp_id');
        if (!$atpId)
            return null;
        return Atp::with(['status', 'emirate', 'category', 'type', 'contacts'])->find($atpId);
    }

    // ─── Dashboard ────────────────────────────────────────────────────────────
    public function dashboard()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        // Active (pending/open) requests from atps_list_requests
        $activeRequests = collect();
        try {
            $activeRequests = DB::table('atps_list_requests')
                ->where('atp_id', $atp->atp_id)
                ->where('is_finished', 0)
                ->orderBy('request_id', 'desc')
                ->get();
        } catch (\Exception $e) {
        }

        // Registration forms submitted by this ATP
        $initForm = DB::table('atps_form_init')
            ->where('atp_id', $atp->atp_id)
            ->first();

        // Logs (recent 5)
        $recentLogs = DB::table('atps_list_logs')
            ->where('atp_id', $atp->atp_id)
            ->orderBy('log_id', 'desc')
            ->limit(5)
            ->get();

        return view('rc.portal.dashboard', compact('atp', 'activeRequests', 'initForm', 'recentLogs'));
    }

    // ─── Accreditation Page (new_accreditation.php equivalent) ───────────────
    public function wizardStep1()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        // All phases ordered
        $phases = DB::table('a_registration_phases')->orderBy('phase_id')->get();

        $currentPhaseId = (int) $atp->phase_id;
        $is_phase_ok = (int) $atp->is_phase_ok;

        // Current phase details
        $currentPhase = $phases->firstWhere('phase_id', $currentPhaseId);

        // form_status & rc_comment from the current phase's table_check
        $form_status = '';
        $rc_comment = '';
        if ($currentPhase && $currentPhase->table_check) {
            $row = DB::table($currentPhase->table_check)
                ->where('atp_id', $atp->atp_id)
                ->select('form_status', 'rc_comment')
                ->first();
            if ($row) {
                $form_status = $row->form_status ?? '';
                $rc_comment = $row->rc_comment ?? '';
            }
        }

        // Todos for current phase (not hidden)
        $todos = collect();
        $showTodos = in_array($form_status, ['review', 'pending_submission']) || $is_phase_ok == 1;

        if ($showTodos) {
            $rawTodos = DB::table('a_registration_phases_todos')
                ->where('phase_id', $currentPhaseId)
                ->where('is_hidden', 0)
                ->get();

            $allGood = true;
            foreach ($rawTodos as $todo) {
                $isDone = false;

                if ($todo->is_submit == 0 && $todo->table_check) {
                    if (!$todo->col_check) {
                        $count = DB::table($todo->table_check)
                            ->where('atp_id', $atp->atp_id)
                            ->count();
                        $isDone = $count > 0;
                    } else {
                        $val = DB::table($todo->table_check)
                            ->where('atp_id', $atp->atp_id)
                            ->value($todo->col_check);
                        $isDone = !empty($val);
                    }
                    if (!$isDone)
                        $allGood = false;
                }

                // Map old PHP todo_id to new Laravel routes
                $todoRouteMap = [
                    1 => route('rc.portal.accreditation.initial_form'),
                    3 => route('rc.portal.accreditation.qualifications'),
                    4 => route('rc.portal.accreditation.faculty'),
                    5 => route('rc.portal.accreditation.learners'),
                    6 => route('rc.portal.accreditation.electronic_systems'),
                    7 => route('rc.portal.accreditation.attachments'),
                    8 => route('rc.portal.accreditation.submit'),
                    10 => route('rc.portal.program_registration.faculty'),
                    11 => route('rc.portal.program_registration.qualification_mapping'),
                    12 => route('rc.portal.program_registration.submit'),
                ];
                $resolvedLink = $todoRouteMap[$todo->todo_id] ?? '#';

                $todos->push((object) [
                    'todo_id' => $todo->todo_id,
                    'title' => $todo->todo_title,
                    'is_submit' => $todo->is_submit,
                    'todo_link' => $resolvedLink,
                    'isDone' => $isDone,
                    'allGood' => $allGood,
                ]);
            }
        }

        return view('rc.portal.wizard.step1', compact(
            'atp',
            'phases',
            'currentPhaseId',
            'is_phase_ok',
            'currentPhase',
            'form_status',
            'rc_comment',
            'todos',
            'showTodos'
        ));
    }

    public function submitStep1(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');
        return redirect()->route('rc.portal.dashboard')->with('success', 'Form Submitted');
    }
}
