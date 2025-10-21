<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EventsApiRequest;
use App\Http\Requests\Api\PublicApiRequest;
use App\Http\Requests\Api\VacanciesApiRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\GrantResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\VacancyResource;
use App\Models\Event;
use App\Models\Grant;
use App\Models\News;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;

class PublicApiController extends Controller
{
    /**
     * Get paginated list of events
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getEvents(EventsApiRequest $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 15), 100);
        $search = $request->get('search');
        $category = $request->get('category');
        $type = $request->get('type');
        $settlement = $request->get('settlement');

        $query = Event::with(['user'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($settlement) {
            $query->where('settlement', 'like', "%{$settlement}%");
        }

        $events = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => EventResource::collection($events->items()),
            'pagination' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    /**
     * Get single event by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getEvent(int $id): JsonResponse
    {
        $event = Event::with(['user'])
            ->where('status', 'approved')
            ->find($id);

        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new EventResource($event)
        ]);
    }

    /**
     * Get paginated list of news
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNews(PublicApiRequest $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 15), 100);
        $search = $request->get('search');
        $category = $request->get('category');

        $query = News::with(['user'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $news = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => NewsResource::collection($news->items()),
            'pagination' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ]
        ]);
    }

    /**
     * Get single news by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getNewsItem(int $id): JsonResponse
    {
        $news = News::with(['user'])
            ->where('status', 'approved')
            ->find($id);

        if (!$news) {
            return response()->json([
                'status' => 'error',
                'message' => 'News not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new NewsResource($news)
        ]);
    }

    /**
     * Get paginated list of grants
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getGrants(EventsApiRequest $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 15), 100);
        $search = $request->get('search');
        $category = $request->get('category');
        $settlement = $request->get('settlement');

        $query = Grant::with(['user'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($settlement) {
            $query->where('settlement', 'like', "%{$settlement}%");
        }

        $grants = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => GrantResource::collection($grants->items()),
            'pagination' => [
                'current_page' => $grants->currentPage(),
                'last_page' => $grants->lastPage(),
                'per_page' => $grants->perPage(),
                'total' => $grants->total(),
            ]
        ]);
    }

    /**
     * Get single grant by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getGrant(int $id): JsonResponse
    {
        $grant = Grant::with(['user'])
            ->where('status', 'approved')
            ->find($id);

        if (!$grant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Grant not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new GrantResource($grant)
        ]);
    }

    /**
     * Get paginated list of vacancies
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getVacancies(VacanciesApiRequest $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 15), 100);
        $search = $request->get('search');
        $category = $request->get('category');
        $type = $request->get('type');
        $experience = $request->get('experience');
        $salaryFrom = $request->get('salary_from');
        $salaryTo = $request->get('salary_to');

        $query = Vacancy::with(['user'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('org_name', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($experience) {
            $query->where('experience', $experience);
        }

        if ($salaryFrom) {
            $query->where('salary_from', '>=', $salaryFrom);
        }

        if ($salaryTo) {
            $query->where('salary_to', '<=', $salaryTo);
        }

        $vacancies = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => VacancyResource::collection($vacancies->items()),
            'pagination' => [
                'current_page' => $vacancies->currentPage(),
                'last_page' => $vacancies->lastPage(),
                'per_page' => $vacancies->perPage(),
                'total' => $vacancies->total(),
            ]
        ]);
    }

    /**
     * Get single vacancy by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getVacancy(int $id): JsonResponse
    {
        $vacancy = Vacancy::with(['user'])
            ->where('status', 'approved')
            ->find($id);

        if (!$vacancy) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vacancy not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new VacancyResource($vacancy)
        ]);
    }
}
