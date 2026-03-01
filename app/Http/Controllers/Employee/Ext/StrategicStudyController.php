<?php

namespace App\Http\Controllers\Employee\Ext;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StrategicStudy;
use App\Models\StrategicStudyPage;
use Illuminate\Support\Facades\Auth;

class StrategicStudyController extends Controller
{
    public function index()
    {
        $studies = StrategicStudy::orderBy('study_id', 'desc')->paginate(12);
        return view('emp.ext.strategies_self_studies.index', compact('studies'));
    }

    public function create()
    {
        return view('emp.ext.strategies_self_studies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'study_title'    => 'required|string|max:255',
            'study_overview' => 'required|string',
        ]);

        $count = StrategicStudy::count() + 1;
        $ref   = 'SS-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        StrategicStudy::create([
            'study_ref'       => $ref,
            'study_title'     => $request->study_title,
            'study_overview'  => $request->study_overview,
            'study_status_id' => 1,
            'added_by'        => Auth::id() ?? 0,
            'added_date'      => now(),
        ]);

        return redirect()->route('emp.ext.strategies.self_studies.index')
            ->with('success', 'Study created successfully.');
    }

    public function show($id)
    {
        $study = StrategicStudy::findOrFail($id);
        $introPages = StrategicStudyPage::where('study_id', $id)
            ->where('page_type', 'introductry')
            ->orderBy('page_id')
            ->get();
        $sections = StrategicStudyPage::where('study_id', $id)
            ->where('page_type', 'section')
            ->orderBy('page_id')
            ->get();
        return view('emp.ext.strategies_self_studies.show', compact('study', 'introPages', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $study = StrategicStudy::findOrFail($id);
        $study->update($request->only(['study_title', 'study_overview']));
        return redirect()->route('emp.ext.strategies.self_studies.show', $id)
            ->with('success', 'Study updated.');
    }

    public function storePage(Request $request, $studyId)
    {
        $request->validate([
            'page_title'   => 'required|string|max:255',
            'page_type'    => 'required|in:introductry,section',
            'page_content' => 'nullable|string',
        ]);

        StrategicStudyPage::create([
            'study_id'     => $studyId,
            'page_title'   => $request->page_title,
            'page_type'    => $request->page_type,
            'page_content' => $request->page_content ?? '',
            'added_by'     => Auth::id() ?? 0,
            'added_date'   => now(),
        ]);

        return redirect()->route('emp.ext.strategies.self_studies.show', $studyId)
            ->with('success', 'Page added.');
    }

    public function updatePage(Request $request, $studyId, $pageId)
    {
        $page = StrategicStudyPage::where('study_id', $studyId)->findOrFail($pageId);
        $page->update($request->only(['page_title', 'page_content']));
        return redirect()->route('emp.ext.strategies.self_studies.show', $studyId)
            ->with('success', 'Page updated.');
    }

    public function destroyPage($studyId, $pageId)
    {
        $page = StrategicStudyPage::where('study_id', $studyId)->findOrFail($pageId);
        $page->delete();
        return redirect()->route('emp.ext.strategies.self_studies.show', $studyId)
            ->with('success', 'Page deleted.');
    }
}
