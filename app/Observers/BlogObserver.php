<?php

namespace App\Observers;

use App\Models\Blog;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BlogObserver
{
    // app/Observers/BlogObserver.php

    public function saved(Blog $blog): void
    {
        // Use the getRawOriginal or check the attribute directly 
        // to avoid Laravel thinking it's a relationship method
        $imagePath = $blog->featured_image;

        if ($imagePath && $blog->isDirty('featured_image')) {

            $path = storage_path('app/public/' . $imagePath);

            if (file_exists($path)) {
                // Check if it's already a webp to prevent double-processing
                if (pathinfo($path, PATHINFO_EXTENSION) === 'webp') {
                    return;
                }

                try {
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($path);

                    $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);

                    // Save WebP version (keeping the original JPG intact)
                    $image->toWebp(85)->save($webpPath);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("WebP Conversion Error: " . $e->getMessage());
                }
            }
        }
    }
    /**
     * Handle the Blog "created" event.
     */
    public function created(Blog $blog): void
    {
        //
    }

    /**
     * Handle the Blog "updated" event.
     */
    public function updated(Blog $blog): void
    {
        //
    }

    /**
     * Handle the Blog "deleted" event.
     */
    public function deleted(Blog $blog): void
    {
        if ($blog->featured_image) {
            $path = storage_path('app/public/' . $blog->featured_image);
            $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);

            if (File::exists($path)) File::delete($path);
            if (File::exists($webpPath)) File::delete($webpPath);
        }
    }

    /**
     * Handle the Blog "restored" event.
     */
    public function restored(Blog $blog): void
    {
        //
    }

    /**
     * Handle the Blog "force deleted" event.
     */
    public function forceDeleted(Blog $blog): void
    {
        //
    }
}
