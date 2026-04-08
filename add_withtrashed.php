<?php

$replacements = [
    'app/Models/SupportTicket.php' => [
        "belongsTo(SupportTicketCategory::class, 'category_id', 'category_id');" => "belongsTo(SupportTicketCategory::class, 'category_id', 'category_id')->withTrashed();",
        "belongsTo(Priority::class, 'priority_id', 'priority_id');" => "belongsTo(Priority::class, 'priority_id', 'priority_id')->withTrashed();",
        "belongsTo(SupportTicketStatus::class, 'status_id', 'status_id');" => "belongsTo(SupportTicketStatus::class, 'status_id', 'status_id')->withTrashed();",
    ],
    'app/Models/Asset.php' => [
        "belongsTo(AssetCategory::class, 'category_id', 'category_id');" => "belongsTo(AssetCategory::class, 'category_id', 'category_id')->withTrashed();",
        "belongsTo(AssetStatus::class, 'status_id', 'status_id');" => "belongsTo(AssetStatus::class, 'status_id', 'status_id')->withTrashed();",
    ],
    'app/Models/HrLeave.php' => [
        "belongsTo(LeaveType::class, 'leave_type_id', 'leave_type_id');" => "belongsTo(LeaveType::class, 'leave_type_id', 'leave_type_id')->withTrashed();",
    ],
    'app/Models/SupportService.php' => [
        "belongsTo(SupportServiceCategory::class, 'category_id', 'category_id');" => "belongsTo(SupportServiceCategory::class, 'category_id', 'category_id')->withTrashed();",
    ],
    'app/Models/Communication.php' => [
        "belongsTo(CommunicationType::class, 'communication_type_id', 'communication_type_id');" => "belongsTo(CommunicationType::class, 'communication_type_id', 'communication_type_id')->withTrashed();",
    ],
    'app/Models/CommunicationRequest.php' => [
        "belongsTo(CommunicationType::class, 'communication_type_id', 'communication_type_id');" => "belongsTo(CommunicationType::class, 'communication_type_id', 'communication_type_id')->withTrashed();",
    ]
];

foreach ($replacements as $file => $reps) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        foreach ($reps as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
