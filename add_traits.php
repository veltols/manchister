<?php

$models = [
    'SupportTicketCategory.php',
    'LeaveType.php',
    'Priority.php',
    'IncidentType.php',
    'AssetCategory.php',
    'SupportServiceCategory.php',
    'CommunicationType.php',
    'UsersListTheme.php',
    'SupportTicketStatus.php',
];

foreach ($models as $filename) {
    if (!file_exists('app/Models/' . $filename)) {
        continue;
    }
    
    $content = file_get_contents('app/Models/' . $filename);
    
    // Add trait use if not present
    if (strpos($content, 'SoftDeletes') === false) {
        // Add use Illuminate\Database\Eloquent\SoftDeletes;
        $content = str_replace(
            "use Illuminate\Database\Eloquent\Model;",
            "use Illuminate\Database\Eloquent\Model;\nuse Illuminate\Database\Eloquent\SoftDeletes;",
            $content
        );
        
        // Add use SoftDeletes;
        $content = str_replace(
            "use HasFactory;",
            "use HasFactory, SoftDeletes;",
            $content
        );
        
        file_put_contents('app/Models/' . $filename, $content);
        echo "Updated $filename\n";
    }
}
