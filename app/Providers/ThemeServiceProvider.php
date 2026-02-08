<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\UserTheme;
use Illuminate\Support\Facades\Auth;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user = Auth::user();
            $themeColor = '#004F68'; // Default brand color
            $themeSecondary = '#00384a'; // Default darker shade

            if ($user) {
                 $val = $user->user_theme_id;
                 \Illuminate\Support\Facades\Log::info('ThemeServiceProvider: User ID: ' . $user->user_id . ', User Theme ID: ' . $val);
                 
                 // Try manual fetch
                 $manualTheme = \App\Models\UserTheme::where('user_theme_id', $val)->first();
                 \Illuminate\Support\Facades\Log::info('Manual fetch result: ' . ($manualTheme ? 'Found: '.$manualTheme->theme_name : 'Null'));
            }

            if ($user && $manualTheme) {
                 // Use manual theme for now
                $user->setRelation('theme', $manualTheme);
            }

            if ($user && $user->theme) {
                // Assuming color_primary is stored without # or needed format
                // The legacy code used color_secondary mostly
                $color = $user->theme->color_secondary; 
                \Illuminate\Support\Facades\Log::info('Theme detected for user ' . $user->user_id . ': ' . $color);
                if($color) {
                    $themeColor = '#' . $color;
                    // Create a darker shade for the gradient
                    // $themeSecondary = $this->adjustBrightness($themeColor, -20);
                     $themeSecondary = '#' . $user->theme->color_primary;
                }
            } else {
                 \Illuminate\Support\Facades\Log::info('No theme detected for user ' . ($user ? $user->user_id : 'guest'));
            }

            $view->with('themeColor', $themeColor);
            $view->with('themeSecondary', $themeSecondary);
        });
    }

    private function adjustBrightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, Positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color = hexdec($color); // Convert to decimal
            $color = max(0, min(255, $color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }
}
