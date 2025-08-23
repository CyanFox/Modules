<?php

namespace Modules\Announcements\app\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Modules\Announcements\app\Actions\CreateAnnouncementAction;
use Modules\Announcements\app\Actions\DeleteAnnouncementAction;
use Modules\Announcements\app\Actions\UpdateAnnouncementAction;
use Modules\Announcements\Models\Announcement;

#[Group('Announcements')]
class AnnouncementsController
{
    #[QueryParameter('per_page', description: 'Number of announcements per page', type: 'integer', default: 20, example: 10)]
    #[QueryParameter('show_dismissed', description: 'Show dismissed announcements', type: 'boolean', default: false, example: true)]
    public function getUserAnnouncements(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;
        $showDismissed = $request->query('show_dismissed', false);

        if ($showDismissed) {
            $announcements = Announcement::query()
                ->where('disabled', false)
                ->where(function ($query) use ($user) {
                    $query->whereHas('access', function ($q) use ($user) {
                        $q->where(function ($subquery) use ($user) {
                            $subquery->where('user_id', $user->id)
                                ->orWhereIn('group_id', $user->roles->pluck('id'))
                                ->orWhereIn('permission_id', $user->permissions->pluck('id'));
                        });
                    })
                        ->orWhereDoesntHave('access');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->query('per_page', 20));
        } else {
            $announcements = Announcement::query()
                ->where('disabled', false)
                ->whereDoesntHave('dismissed', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where(function ($query) use ($user) {
                    $query->whereHas('access', function ($q) use ($user) {
                        $q->where(function ($subquery) use ($user) {
                            $subquery->where('user_id', $user->id)
                                ->orWhereIn('group_id', $user->roles->pluck('id'))
                                ->orWhereIn('permission_id', $user->permissions->pluck('id'));
                        });
                    })
                        ->orWhereDoesntHave('access');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->query('per_page', 20));
        }

        return response()->json([
            'message' => 'User announcements retrieved successfully',
            'announcements' => $announcements,
        ]);
    }

    #[PathParameter('announcementId', description: 'ID of the announcement to dismiss', type: 'integer')]
    public function dismissAnnouncement(Request $request, $announcementId)
    {
        $user = $request->attributes->get('api_key')->user;

        $announcement = Announcement::find($announcementId);
        if (! $announcement) {
            return response()->json(['error' => 'Announcement not found'], 404);
        }

        if ($announcement->dismissed()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Announcement already dismissed'], 400);
        }

        if (! $announcement->dismissible) {
            return response()->json(['error' => 'Announcement is not dismissible'], 400);
        }

        $announcement->dismissed()->create([
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Announcement dismissed successfully',
            'announcement' => $announcement,
        ]);
    }

    #[QueryParameter('per_page', description: 'Number of announcements per page', type: 'integer', default: 20, example: 10)]
    public function getAnnouncements(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.announcements') || ! $request->attributes->get('api_key')->can('admin.announcements')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Announcements retrieved successfully',
            'announcements' => Announcement::orderBy('created_at')->with('access')->paginate($request->query('per_page', 20)),
        ]);
    }

    #[PathParameter('announcementId', description: 'ID of the announcement to view', type: 'integer')]
    public function getAnnouncement(Request $request, $announcementId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.announcements') || ! $request->attributes->get('api_key')->can('admin.announcements')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $announcement = Announcement::findOrFail($announcementId);

        return response()->json([
            'message' => 'Announcement retrieved successfully',
            'announcement' => $announcement,
        ]);
    }

    public function createAnnouncement(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.announcements.create') || ! $request->attributes->get('api_key')->can('admin.announcements.create')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $announcement = CreateAnnouncementAction::run($request->all());

        return response()->json([
            'message' => 'Announcement created successfully',
            'announcement' => $announcement,
        ]);
    }

    #[PathParameter('announcementId', description: 'ID of the announcement to update', type: 'integer')]
    public function updateAnnouncement(Request $request, $announcementId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.announcements.update') || ! $request->attributes->get('api_key')->can('admin.announcements.update')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|in:info,success,warning,danger',
            'icon' => 'nullable|string|max:255',
            'disabled' => 'nullable|boolean',
            'dismissible' => 'nullable|boolean',
            'access' => 'nullable|array',
        ]);

        $announcement = Announcement::find($announcementId);
        if (! $announcement) {
            return response()->json(['error' => 'Announcement not found'], 404);
        }

        UpdateAnnouncementAction::run($announcement, $request->all());

        return response()->json([
            'message' => 'Announcement updated successfully',
            'announcement' => $announcement->fresh(),
        ]);
    }

    #[PathParameter('announcementId', description: 'ID of the announcement to delete', type: 'integer')]
    public function deleteAnnouncement(Request $request, $announcementId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.announcements.delete') || ! $request->attributes->get('api_key')->can('admin.announcements.delete')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $announcement = Announcement::find($announcementId);
        if (! $announcement) {
            return response()->json(['error' => 'Announcement not found'], 404);
        }

        DeleteAnnouncementAction::run($announcement);

        return response()->json([
            'message' => 'Announcement deleted successfully',
        ]);
    }
}
